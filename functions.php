<?php
/**
 * Nova Import — functions.php
 * Tema base a medida, pensado para funcionar con el plugin WooCommerce.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function novaimport_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Menú principal', 'nova-import' ),
		'footer'  => __( 'Menú de pie de página', 'nova-import' ),
	) );
}
add_action( 'after_setup_theme', 'novaimport_setup' );

function novaimport_assets() {
	wp_enqueue_style(
		'novaimport-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,500;0,600;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'novaimport-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_script( 'novaimport-main', get_template_directory_uri() . '/js/main.js', array(), filemtime( get_stylesheet_directory() . '/js/main.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'novaimport_assets' );

/**
 * Cantidad de ítems en el carrito, para el ícono del header.
 * Si WooCommerce todavía no está activo, muestra 0 sin romper el sitio.
 */
function novaimport_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return WC()->cart->get_cart_contents_count();
	}
	return 0;
}

/**
 * WooCommerce ya trae su propio wrapper (div#primary / main#main) cuando
 * el tema declara add_theme_support('woocommerce'), así que no hace falta
 * sobreescribir archive-product.php ni single-product.php: el estilo se
 * aplica por CSS (ver la sección "WOOCOMMERCE MAPPING" en style.css).
 */

/**
 * Menú de respaldo mientras no haya un menú asignado en Apariencia → Menús.
 */
function novaimport_menu_fallback() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	echo '<ul>
		<li><a href="' . esc_url( $shop ) . '">Catálogo</a></li>
		<li><a href="' . esc_url( home_url( '/mayoristas/' ) ) . '">Mayoristas</a></li>
		<li><a href="' . esc_url( home_url( '/#envios' ) ) . '">Envíos</a></li>
		<li><a href="' . esc_url( home_url( '/#contacto' ) ) . '">Contacto</a></li>
	</ul>';
}

/**
 * Árbol de categorías: rubros principales (Bicicleta, Moto) con sus subcategorías.
 * Solo categorías con productos y sin "Sin categorizar".
 *
 * @return array<int, array{term: WP_Term, children: WP_Term[]}>
 */
function novaimport_cat_tree() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}
	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		'orderby'    => 'name',
	);
	$parents = get_terms( $args + array( 'parent' => 0 ) );
	if ( is_wp_error( $parents ) ) {
		return array();
	}
	$tree = array();
	foreach ( $parents as $parent ) {
		$children = get_terms( $args + array( 'parent' => $parent->term_id ) );
		$tree[]   = array(
			'term'     => $parent,
			'children' => is_wp_error( $children ) ? array() : $children,
		);
	}
	return $tree;
}

/**
 * Subcategorías de primer nivel (Neumáticos de bici, Cámaras de bici, ...).
 *
 * @return WP_Term[]
 */
function novaimport_product_cats() {
	$out = array();
	foreach ( novaimport_cat_tree() as $node ) {
		$out = array_merge( $out, $node['children'] ? $node['children'] : array( $node['term'] ) );
	}
	return $out;
}

/**
 * Opciones del buscador de la portada, armadas a partir de los productos reales.
 * Estructura: tipo (bici|moto) → rubros → medidas/modelos, cada una con su URL de búsqueda.
 *
 * @return array
 */
function novaimport_finder_data() {
	$cached = get_transient( 'novaimport_finder' );
	if ( false !== $cached ) {
		return $cached;
	}

	// slug de categoría => [tipo, rubro, etiqueta del segundo filtro, regex que extrae la medida/modelo del título].
	$rules = array(
		'neumaticos-de-bici' => array( 'bici', 'Neumático', 'Rodado', '/\b(\d{2}(?:\.\d)?)x/u' ),
		'camaras-de-bici'    => array( 'bici', 'Cámara', 'Rodado', '/\b(\d{2}(?:\.\d)?)x/u' ),
		'neumaticos-de-moto' => array( 'moto', 'Neumático', 'Medida', '/\b(\d{2,3}(?:\/\d{2,3})?-\d{2})\b/u' ),
		'corona-y-pinon'     => array( 'moto', 'Kit corona y piñón', 'Modelo', '/Pi(?:ñ|n)(?:ó|o)n\s+(.+?)\s*\(/iu' ),
		'cadenas'            => array( 'moto', 'Cadena', 'Medida', '/\b(\d{3}h-\d{2,3})\b/iu' ),
	);

	$data = array( 'bici' => array(), 'moto' => array() );
	foreach ( $rules as $slug => $rule ) {
		list( $tipo, $rubro, $filtro, $regex ) = $rule;
		if ( ! function_exists( 'wc_get_products' ) || ! term_exists( $slug, 'product_cat' ) ) {
			continue;
		}
		$products = wc_get_products(
			array(
				'status'   => 'publish',
				'limit'    => -1,
				'category' => array( $slug ),
				'return'   => 'objects',
			)
		);
		$values = array();
		foreach ( $products as $product ) {
			if ( preg_match( $regex, $product->get_name(), $m ) ) {
				$values[ trim( $m[1] ) ] = true;
			}
		}
		if ( ! $values ) {
			continue;
		}
		$keys = array_keys( $values );
		natcasesort( $keys );
		$options = array();
		foreach ( $keys as $value ) {
			$query     = 'Rodado' === $filtro ? $value . 'x' : $value;
			$options[] = array(
				'label' => 'Rodado' === $filtro ? $value . '"' : $value,
				'url'   => add_query_arg(
					array(
						's'           => $query,
						'post_type'   => 'product',
						'product_cat' => $slug,
					),
					home_url( '/' )
				),
			);
		}
		$data[ $tipo ][] = array(
			'rubro'   => $rubro,
			'filtro'  => $filtro,
			'all'     => get_term_link( $slug, 'product_cat' ),
			'options' => $options,
		);
	}

	set_transient( 'novaimport_finder', $data, DAY_IN_SECONDS );
	return $data;
}

// El buscador se rearma cuando cambian los productos.
add_action(
	'save_post_product',
	function () {
		delete_transient( 'novaimport_finder' );
	}
);

/**
 * IDs para "Productos destacados": los marcados como destacados y,
 * si son menos de 4, se completa con los últimos cargados.
 *
 * @return int[]
 */
function novaimport_featured_ids() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$ids = wc_get_products(
		array(
			'status'   => 'publish',
			'limit'    => 4,
			'featured' => true,
			'return'   => 'ids',
		)
	);
	if ( count( $ids ) < 4 ) {
		$more = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 4 - count( $ids ),
				'exclude' => $ids,
				'orderby' => 'date',
				'order'   => 'DESC',
				'return'  => 'ids',
			)
		);
		$ids = array_merge( $ids, $more );
	}
	return $ids;
}

/**
 * La tienda muestra 4 columnas y 24 productos por página.
 */
add_filter( 'loop_shop_columns', function () { return 4; } );
add_filter( 'loop_shop_per_page', function () { return 24; } );

/**
 * Datos de contacto del negocio (un solo lugar para cambiarlos).
 *
 * @return array<string,string>
 */
function novaimport_contact() {
	return array(
		'direccion'  => '1º de Mayo 3043, Santa Fe',
		'cp'         => 'S3002EIK',
		'maps'       => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( 'Nova Import, 1º de Mayo 3043, Santa Fe, Argentina' ),
		'maps_embed' => 'https://maps.google.com/maps?q=' . rawurlencode( '1º de Mayo 3043, Santa Fe, Argentina' ) . '&z=16&output=embed',
		'telefono'   => '0342 597-0539',
		'whatsapp'   => 'https://wa.me/5493425970539?text=' . rawurlencode( '¡Hola Nova Import! Quiero hacer una consulta.' ),
		'email'      => 'novaimport.info@gmail.com',
		'instagram'  => 'https://www.instagram.com/novaimport.oficial/',
		'ig_user'    => '@novaimport.oficial',
		'horario'    => 'Lunes a viernes de 8:30 a 16 hs',
	);
}

/**
 * La tienda vende solo en Argentina: el país viene elegido en el checkout.
 */
add_filter( 'default_checkout_billing_country', function () { return 'AR'; } );
add_filter( 'default_checkout_shipping_country', function () { return 'AR'; } );

/**
 * El contador del carrito se actualiza por AJAX: así no queda viejo en las páginas cacheadas.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( function_exists( 'WC' ) ) {
			wp_enqueue_script( 'wc-cart-fragments' );
		}
	},
	30
);
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		$fragments['span.cart-count'] = '<span class="cart-count">' . esc_html( novaimport_cart_count() ) . '</span>';
		return $fragments;
	}
);

/**
 * En una categoría con subcategorías, accesos rápidos a cada una arriba de los productos.
 */
add_action(
	'woocommerce_archive_description',
	function () {
		if ( ! is_tax( 'product_cat' ) ) {
			return;
		}
		$children = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => get_queried_object_id(),
				'hide_empty' => true,
			)
		);
		if ( is_wp_error( $children ) || ! $children ) {
			return;
		}
		echo '<nav class="subcat-chips" aria-label="Subcategorías">';
		foreach ( $children as $term ) {
			printf( '<a href="%s">%s <span>%d</span></a>', esc_url( get_term_link( $term ) ), esc_html( $term->name ), (int) $term->count );
		}
		echo '</nav>';
	},
	20
);

/**
 * El tema no usa barra lateral: sin esto WooCommerce imprime los widgets por defecto
 * de WordPress (Buscar, Páginas, Archivos, Categorías) al pie de la tienda.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

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
	wp_enqueue_script( 'novaimport-main', get_template_directory_uri() . '/js/main.js', array(), '1.0', true );
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

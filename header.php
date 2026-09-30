<?php
/**
 * Nova Import — header.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-mode="minorista">
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="header-top">
    <div class="logo">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#fff;">
        NOVA IMPORT<small>bici · moto · repuestos</small>
      </a>
    </div>

    <nav class="main-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'nova-import' ); ?>">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'fallback_cb'    => function() {
          echo '<ul>
            <li><a href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '#' ) . '">Catálogo</a></li>
            <li><a href="#mayoristas">Mayoristas</a></li>
            <li><a href="#envios">Envíos</a></li>
            <li><a href="#contacto">Contacto</a></li>
          </ul>';
        },
      ) );
      ?>
    </nav>

    <form role="search" method="get" class="search-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" name="s" placeholder="Buscar por medida o modelo..." value="<?php echo esc_attr( get_search_query() ); ?>">
    </form>

    <div class="header-actions">
      <div class="mode-toggle" role="group" aria-label="Modo de compra">
        <button type="button" class="active" data-mode-btn="minorista">Minorista</button>
        <button type="button" data-mode-btn="mayorista">Mayorista</button>
      </div>
      <a class="cart-btn" href="<?php echo function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '#'; ?>" aria-label="Carrito">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <?php echo esc_html( novaimport_cart_count() ); ?>
      </a>
    </div>
  </div>

  <div class="cat-strip">
    <div class="wrap">
      <?php
      $categorias = array( 'Neumáticos de bici', 'Cámaras de bici', 'Neumáticos de moto', 'Corona y piñón', 'Cadenas', 'Bujías' );
      if ( taxonomy_exists( 'product_cat' ) ) {
        $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'number' => 6 ) );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
          foreach ( $terms as $i => $term ) {
            printf( '<a href="%s"%s>%s</a>', esc_url( get_term_link( $term ) ), $i === 0 ? ' class="active"' : '', esc_html( $term->name ) );
          }
        } else {
          foreach ( $categorias as $i => $cat ) {
            printf( '<a href="#"%s>%s</a>', $i === 0 ? ' class="active"' : '', esc_html( $cat ) );
          }
        }
      }
      ?>
    </div>
  </div>
</header>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$novaimport_menu = array(
	'theme_location' => 'primary',
	'container'      => false,
	'fallback_cb'    => 'novaimport_menu_fallback',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0B1F3A">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="header-top">
    <button type="button" class="nav-toggle" aria-controls="mobile-panel" aria-expanded="false" aria-label="Abrir menú">
      <span></span><span></span><span></span>
    </button>

    <div class="logo">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        NOVA IMPORT<small>bici · moto · repuestos</small>
      </a>
    </div>

    <nav class="main-nav" aria-label="Menú principal">
      <?php wp_nav_menu( $novaimport_menu ); ?>
    </nav>

    <form role="search" method="get" class="search-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <label class="screen-reader-text" for="search-desktop">Buscar productos</label>
      <input id="search-desktop" type="search" name="s" placeholder="Buscar por medida o modelo..." value="<?php echo esc_attr( get_search_query() ); ?>">
      <input type="hidden" name="post_type" value="product">
    </form>

    <div class="header-actions">
      <?php if ( function_exists( 'nova_may_is_wholesale' ) && nova_may_is_wholesale() ) : ?>
        <a class="wholesale-badge" href="<?php echo esc_url( home_url( '/pedido-mayorista/' ) ); ?>" title="Cargar pedido mayorista">Mayorista</a>
      <?php endif; ?>
      <a class="account-btn" href="<?php echo function_exists( 'wc_get_page_permalink' ) ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : '#'; ?>" aria-label="Mi cuenta">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
      </a>
      <a class="cart-btn" href="<?php echo function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '#'; ?>" aria-label="Carrito">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="cart-count"><?php echo esc_html( novaimport_cart_count() ); ?></span>
      </a>
    </div>
  </div>

  <div class="mobile-panel" id="mobile-panel" hidden>
    <form role="search" method="get" class="search-box search-box--mobile" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <label class="screen-reader-text" for="search-mobile">Buscar productos</label>
      <input id="search-mobile" type="search" name="s" placeholder="Buscar por medida o modelo..." value="<?php echo esc_attr( get_search_query() ); ?>">
      <input type="hidden" name="post_type" value="product">
    </form>
    <nav class="mobile-nav" aria-label="Menú principal">
      <?php wp_nav_menu( $novaimport_menu ); ?>
    </nav>
  </div>

  <?php $novaimport_tree = novaimport_cat_tree(); ?>
  <?php if ( $novaimport_tree ) : ?>
    <nav class="cat-strip" aria-label="Categorías">
      <div class="wrap">
        <?php
        $current   = is_tax( 'product_cat' ) ? get_queried_object_id() : 0;
        $ancestors = $current ? get_ancestors( $current, 'product_cat' ) : array();
        foreach ( $novaimport_tree as $node ) {
          $groups = array_merge( array( $node['term'] ), $node['children'] );
          echo '<span class="cat-strip__group">';
          foreach ( $groups as $i => $term ) {
            $classes = array();
            if ( 0 === $i ) {
              $classes[] = 'is-parent';
            }
            if ( $current === (int) $term->term_id || in_array( (int) $term->term_id, $ancestors, true ) ) {
              $classes[] = 'active';
            }
            printf(
              '<a href="%s"%s%s>%s</a>',
              esc_url( get_term_link( $term ) ),
              $classes ? ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' : '',
              $current === (int) $term->term_id ? ' aria-current="page"' : '',
              esc_html( $term->name )
            );
          }
          echo '</span>';
        }
        ?>
      </div>
    </nav>
  <?php endif; ?>
</header>

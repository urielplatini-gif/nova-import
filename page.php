<?php
/**
 * Páginas comunes y las de WooCommerce (Carrito, Finalizar compra, Mi cuenta).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main class="site-main page-main">
  <div class="wrap">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class( 'page-article' ); ?>>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <div class="page-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>

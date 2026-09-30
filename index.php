<?php
/**
 * Nova Import — index.php
 * Plantilla de respaldo (blog, búsqueda, y cualquier vista sin plantilla propia).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main class="site-main">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class(); ?> style="margin-bottom:32px;">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <p><?php esc_html_e( 'No se encontró contenido.', 'nova-import' ); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>

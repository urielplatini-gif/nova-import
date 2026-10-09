<?php
/**
 * Nova Import — front-page.php
 * Página de inicio. El catálogo completo vive en la página Tienda de WooCommerce.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$finder   = novaimport_finder_data();
?>

<section class="hero">
  <div class="wrap">
    <div class="hero-copy">
      <span class="badge-pill">IMPORTACIÓN DIRECTA · SIN INTERMEDIARIOS</span>
      <h1>Neumáticos y repuestos <span class="hl">para bici y moto</span></h1>
      <p class="lede">Cámaras, neumáticos, kits de corona y piñón y cadenas. Compra minorista o mayorista desde el mismo sitio.</p>
      <div class="hero-ctas">
        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary">Ver catálogo</a>
        <a href="<?php echo esc_url( home_url( '/mayoristas/' ) ); ?>" class="btn btn-ghost">Soy vendedor mayorista</a>
      </div>
    </div>

    <?php if ( ! empty( $finder['bici'] ) || ! empty( $finder['moto'] ) ) : ?>
      <div class="finder-card" data-finder>
        <h2 class="finder-title">Buscá tu producto</h2>
        <div class="sub">Elegí el tipo, el rubro y la medida</div>
        <div class="finder-tabs" role="tablist">
          <?php
          $first = true;
          foreach ( array( 'bici' => 'Bicicleta', 'moto' => 'Moto' ) as $tipo => $label ) :
            if ( empty( $finder[ $tipo ] ) ) {
              continue;
            }
            ?>
            <button type="button" role="tab" class="<?php echo $first ? 'active' : ''; ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" data-finder-tipo="<?php echo esc_attr( $tipo ); ?>"><?php echo esc_html( $label ); ?></button>
            <?php
            $first = false;
          endforeach;
          ?>
        </div>
        <div class="finder-grid">
          <div>
            <label for="finder-rubro">Rubro</label>
            <select id="finder-rubro" data-finder-rubro></select>
          </div>
          <div>
            <label for="finder-medida" data-finder-filtro-label>Medida</label>
            <select id="finder-medida" data-finder-medida></select>
          </div>
        </div>
        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary" data-finder-go>Ver disponibilidad</a>
        <script type="application/json" data-finder-json><?php echo wp_json_encode( $finder ); ?></script>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php $novaimport_tree = novaimport_cat_tree(); ?>
<?php if ( $novaimport_tree ) : ?>
  <section class="cat-cards">
    <div class="wrap">
      <div class="section-title">
        <h2>Comprá por categoría</h2>
        <a href="<?php echo esc_url( $shop_url ); ?>">Ver todo el catálogo →</a>
      </div>
      <div class="rubro-grid">
        <?php foreach ( $novaimport_tree as $node ) : $parent = $node['term']; ?>
          <div class="rubro-card">
            <a class="rubro-card__head" href="<?php echo esc_url( get_term_link( $parent ) ); ?>">
              <span class="rubro-card__name"><?php echo esc_html( $parent->name ); ?></span>
              <span class="rubro-card__count"><?php echo esc_html( sprintf( _n( '%d producto', '%d productos', $parent->count, 'nova-import' ), $parent->count ) ); ?> →</span>
            </a>
            <?php if ( $node['children'] ) : ?>
              <div class="cat-grid">
                <?php foreach ( $node['children'] as $term ) : ?>
                  <a class="cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                    <span class="cat-card__name"><?php echo esc_html( $term->name ); ?></span>
                    <span class="cat-card__count"><?php echo esc_html( sprintf( _n( '%d producto', '%d productos', $term->count, 'nova-import' ), $term->count ) ); ?></span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php $novaimport_featured = novaimport_featured_ids(); ?>
<?php if ( $novaimport_featured ) : ?>
  <section class="featured">
    <div class="wrap">
      <div class="section-title">
        <h2>Productos destacados</h2>
        <a href="<?php echo esc_url( $shop_url ); ?>">Ver más →</a>
      </div>
      <?php echo do_shortcode( '[products ids="' . esc_attr( implode( ',', $novaimport_featured ) ) . '" columns="4" orderby="post__in"]' ); ?>
    </div>
  </section>
<?php endif; ?>

<div class="trust-strip" id="envios">
  <div class="wrap">
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      <div><b>Atención por WhatsApp</b><span>Lunes a viernes de 8:30 a 16 hs</span></div>
    </div>
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
      <div><b>Precio mayorista</b><span>Para comercios y revendedores</span></div>
    </div>
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      <div><b>Envíos a todo el país</b><span>Costo y plazo visibles antes de pagar</span></div>
    </div>
  </div>
</div>

<section id="mayoristas" class="wholesale-cta">
  <div class="wrap">
    <div>
      <span class="badge-pill">VENTAS MAYORISTAS</span>
      <h2>¿Tenés una bicicletería, gomería o casa de repuestos?</h2>
      <p>Precios especiales para revendedores de todo el país. Pedí tu cuenta: la aprobamos y cargás tu pedido de bici, moto y repuestos en una sola grilla.</p>
    </div>
    <div class="wholesale-cta__actions">
      <?php if ( function_exists( 'nova_may_is_wholesale' ) && nova_may_is_wholesale() ) : ?>
        <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/pedido-mayorista/' ) ); ?>">Cargar pedido mayorista</a>
      <?php else : ?>
        <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/mayoristas/' ) ); ?>">Solicitar cuenta mayorista</a>
        <?php if ( function_exists( 'wc_get_page_permalink' ) && ! is_user_logged_in() ) : ?>
          <a class="btn btn-ghost" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Ya tengo cuenta</a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php $c = novaimport_contact(); ?>
<section id="contacto" class="contact-section">
  <div class="wrap">
    <div class="contact-info">
      <div class="section-title"><h2>Contacto</h2></div>
      <p class="contact-lede">Escribinos por WhatsApp o visitá nuestro local en Santa Fe. Vendemos a todo el país.</p>
      <ul class="contact-list">
        <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><div><b>Dirección</b><a href="<?php echo esc_url( $c['maps'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['direccion'] ); ?> (<?php echo esc_html( $c['cp'] ); ?>)</a></div></li>
        <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><div><b>Horario</b><span><?php echo esc_html( $c['horario'] ); ?></span></div></li>
        <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg><div><b>WhatsApp</b><a href="<?php echo esc_url( $c['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['telefono'] ); ?></a></div></li>
        <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg><div><b>Email</b><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></div></li>
        <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg><div><b>Instagram</b><a href="<?php echo esc_url( $c['instagram'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['ig_user'] ); ?></a></div></li>
      </ul>
      <a class="btn btn-whatsapp" href="<?php echo esc_url( $c['whatsapp'] ); ?>" target="_blank" rel="noopener">Escribinos por WhatsApp</a>
    </div>
    <div class="contact-map">
      <iframe title="Ubicación de Nova Import en Santa Fe" src="<?php echo esc_url( $c['maps_embed'] ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
</section>

<?php get_footer(); ?>

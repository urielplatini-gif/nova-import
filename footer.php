<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$novaimport_shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo">NOVA IMPORT<small>bici · moto · repuestos</small></div>
        <p>Neumáticos, cámaras y repuestos para bici y moto. Importamos directo de China. Venta minorista y mayorista.</p>
        <?php $c = novaimport_contact(); ?>
        <ul class="footer-contact">
          <li><a href="<?php echo esc_url( $c['maps'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['direccion'] ); ?></a></li>
          <li><a href="<?php echo esc_url( $c['whatsapp'] ); ?>" target="_blank" rel="noopener">WhatsApp <?php echo esc_html( $c['telefono'] ); ?></a></li>
          <li><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></li>
          <li><?php echo esc_html( $c['horario'] ); ?></li>
        </ul>
        <div class="socials">
          <a href="<?php echo esc_url( $c['instagram'] ); ?>" target="_blank" rel="noopener" aria-label="Instagram de Nova Import"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
          <a href="<?php echo esc_url( $c['whatsapp'] ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp de Nova Import"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></a>
        </div>
      </div>
      <div>
        <h4>Tienda</h4>
        <ul>
          <li><a href="<?php echo esc_url( $novaimport_shop ); ?>">Catálogo completo</a></li>
          <?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
            <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mi cuenta</a></li>
            <li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Carrito</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div>
        <h4>Categorías</h4>
        <ul>
          <?php foreach ( novaimport_cat_tree() as $node ) : ?>
            <li class="footer-cat-parent"><a href="<?php echo esc_url( get_term_link( $node['term'] ) ); ?>"><?php echo esc_html( $node['term']->name ); ?></a></li>
            <?php foreach ( $node['children'] as $term ) : ?>
              <li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4>Ayuda</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/envios/' ) ); ?>">Envíos</a></li>
          <li><a href="<?php echo esc_url( home_url( '/mayoristas/' ) ); ?>">Ventas mayoristas</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#contacto' ) ); ?>">Contacto</a></li>
          <?php
          // Páginas legales: solo se muestran las que están publicadas.
          $novaimport_legal = array(
            'terminos-y-condiciones' => 'Términos y condiciones',
            'reembolso_devoluciones' => 'Cambios y devoluciones',
            'privacy-policy'         => 'Política de privacidad',
          );
          foreach ( $novaimport_legal as $slug => $label ) :
            $page = get_page_by_path( $slug );
            if ( $page && 'publish' === $page->post_status ) :
              ?>
              <li><a href="<?php echo esc_url( get_permalink( $page ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
              <?php
            endif;
          endforeach;
          ?>
        </ul>
      </div>
    </div>
    <?php $novaimport_arrep = get_page_by_path( 'arrepentimiento' ); ?>
    <?php if ( $novaimport_arrep && 'publish' === $novaimport_arrep->post_status ) : ?>
      <div class="footer-arrepentimiento">
        <a class="btn btn-ghost" href="<?php echo esc_url( get_permalink( $novaimport_arrep ) ); ?>">Botón de arrepentimiento</a>
      </div>
    <?php endif; ?>
    <div class="footer-bottom">
      &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Nova Import. Neumáticos, cámaras y repuestos para bici y moto.
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

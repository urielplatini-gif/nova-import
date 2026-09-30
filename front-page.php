<?php
/**
 * Nova Import — front-page.php
 * Página de inicio. El catálogo completo vive en la página de Tienda de
 * WooCommerce (archive-product.php de WooCommerce, ya estilada por CSS).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '#catalogo';
?>

<section class="hero">
  <div class="wrap">
    <div>
      <span class="badge-pill">IMPORTACIÓN DIRECTA · SIN INTERMEDIARIOS</span>
      <h1>Neumáticos y repuestos <span class="hl">para bici y moto</span></h1>
      <p class="lede">Cámaras, neumáticos, kits de corona y piñón, cadenas y bujías. Compra minorista o mayorista desde el mismo sitio.</p>
      <div class="hero-ctas">
        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary">Ver catálogo</a>
        <a href="#mayoristas" class="btn btn-ghost">Soy vendedor mayorista</a>
      </div>
    </div>
    <div class="finder-card">
      <h3>Buscá tu producto</h3>
      <div class="sub">Elegí el rubro y la medida</div>
      <div class="finder-tabs">
        <button type="button" class="active">Bicicleta</button>
        <button type="button">Moto</button>
      </div>
      <div class="finder-grid">
        <div>
          <label>Rubro</label>
          <select><option>Neumático</option><option>Cámara</option></select>
        </div>
        <div>
          <label>Rodado</label>
          <select><option>16"</option><option>20"</option><option>24"</option><option selected>26"</option><option>29"</option></select>
        </div>
        <div>
          <label>Medida</label>
          <select><option>1.75</option><option selected>1.95</option><option>2.10</option><option>2.125</option></select>
        </div>
      </div>
      <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary">Ver disponibilidad</a>
    </div>
  </div>
</section>

<section class="featured">
  <div class="wrap">
    <div class="featured-label">PRODUCTOS<br>DESTACADOS</div>
    <div class="featured-row">
      <?php
      if ( shortcode_exists( 'featured_products' ) ) {
        // WooCommerce activo: trae hasta 4 productos marcados como destacados.
        echo do_shortcode( '[featured_products limit="4" columns="4"]' );
      } else {
        // Sin WooCommerce todavía: se ve una vista previa de referencia.
        $demo = array(
          array( 'cat' => 'Neumáticos de moto', 'title' => 'Neumático moto 300-18 SY-02', 'price' => '$ 31.490' ),
          array( 'cat' => 'Corona y piñón', 'title' => 'Kit YBR 125 (43-14) c/cadena 118', 'price' => '$ 14.090' ),
          array( 'cat' => 'Neumáticos de bici', 'title' => 'Neumático 26x1.95 ZT-0038', 'price' => '$ 17.900' ),
          array( 'cat' => 'Cadenas', 'title' => 'Cadena 428H-118', 'price' => '$ 7.290' ),
        );
        foreach ( $demo as $p ) : ?>
          <div class="card">
            <span class="cat"><?php echo esc_html( $p['cat'] ); ?></span>
            <div class="icon-wrap">
              <svg viewBox="0 0 48 48" fill="none" stroke="#123563" stroke-width="2.2" width="100%" height="100%"><circle cx="24" cy="24" r="19"/><circle cx="24" cy="24" r="10" stroke-dasharray="3 3"/><circle cx="24" cy="24" r="3" fill="#123563" stroke="none"/></svg>
            </div>
            <h4><?php echo esc_html( $p['title'] ); ?></h4>
            <div class="price price-emph"><?php echo esc_html( $p['price'] ); ?></div>
            <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary">Ver más</a>
          </div>
        <?php endforeach;
      }
      ?>
    </div>
  </div>
</section>

<div class="trust-strip">
  <div class="wrap">
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      <div><b>Atención directa</b><span>Por WhatsApp, sin vueltas</span></div>
    </div>
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
      <div><b>Precio mayorista</b><span>Para vendedores registrados</span></div>
    </div>
    <div class="trust-item">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      <div><b>Envíos a todo el país</b><span>Costo y plazo visibles antes de pagar</span></div>
    </div>
  </div>
</div>

<section id="mayoristas" class="bulk-section wholesale-only">
  <div class="wrap">
    <div class="section-head">
      <h2>Pedido mayorista — carga rápida</h2>
      <p>Cargá cantidades de bici, moto y repuestos en la misma grilla y mandalo como un solo pedido. Queda registrado con tu cuenta de vendedor.</p>
    </div>
    <table class="bulk-table">
      <thead>
        <tr><th>Producto</th><th>Categoría</th><th>Precio mayorista</th><th>Cantidad</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        <tr><td>Neumático moto 300-18 SY-02</td><td>Neumáticos de moto</td><td>$24.900</td><td><input class="qty-input" type="number" value="6" min="0"></td><td>$149.400</td></tr>
        <tr><td>Kit YBR 125 (43-14) c/cadena</td><td>Corona y piñón</td><td>$11.200</td><td><input class="qty-input" type="number" value="8" min="0"></td><td>$89.600</td></tr>
        <tr><td>Cámara 26x1.95/2.125 V/A 35mm</td><td>Cámaras de bici</td><td>$6.700</td><td><input class="qty-input" type="number" value="20" min="0"></td><td>$134.000</td></tr>
        <tr class="bulk-total-row"><td colspan="4">Total del pedido</td><td>$373.000</td></tr>
      </tbody>
    </table>
    <p class="bulk-note">Vista de referencia: la carga real por vendedor la resuelve el plugin de mayoristas (B2BKing) una vez cargado el catálogo en WooCommerce.</p>
  </div>
</section>

<?php get_footer(); ?>

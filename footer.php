<?php
/**
 * Nova Import — footer.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <h4>Información</h4>
        <ul>
          <li><a href="#">Quiénes somos</a></li>
          <li><a href="#">Cómo comprar</a></li>
          <li><a href="#">Encontrá lo que buscás</a></li>
        </ul>
      </div>
      <div>
        <h4>Categorías</h4>
        <ul>
          <li><a href="#">Bicicleta</a></li>
          <li><a href="#">Moto</a></li>
          <li><a href="#">Corona, piñón y cadenas</a></li>
        </ul>
      </div>
      <div>
        <h4>Ayuda</h4>
        <ul>
          <li><a href="#">Términos y condiciones</a></li>
          <li><a href="#">Contacto</a></li>
          <li><a href="#">Promociones</a></li>
        </ul>
      </div>
      <div>
        <h4>Redes sociales</h4>
        <div class="socials">
          <a href="#" aria-label="Facebook">f</a>
          <a href="#" aria-label="Instagram">in</a>
          <a href="#" aria-label="WhatsApp">wa</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      Nova Import — neumáticos, cámaras y repuestos para bici y moto · &copy; <?php echo esc_html( date( 'Y' ) ); ?>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

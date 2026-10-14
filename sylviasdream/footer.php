<?php
/**
 * Parent footer shell. The child theme ships its own footer.php.
 *
 * @package SylviasDream
 */
defined( 'ABSPATH' ) || exit;
?>
<footer class="site-footer">
	<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>

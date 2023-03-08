<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Satus
 */

?>

	<footer id="colophon" class="site-footer">
        <div class="site-footer-copyright">&copy; <?php echo date( 'Y' ); ?> <?php echo get_bloginfo( 'name' ); ?>. All Rights Reserved.</div>
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package boxes
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php wp_nav_menu( array( 'theme_location' => 'footer', 'fallback_cb' => FALSE, 'items_wrap' => '%3$s' ) ); ?>
			<?php do_action( 'boxes_credits' ); ?>
			<?php echo get_the_author() ?><br />
			© <?php echo date('Y') == get_the_date('Y') ? date('Y') : get_the_date('Y') .'-'. date('Y'); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
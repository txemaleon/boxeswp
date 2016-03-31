<?php
/**
 * @package boxes
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-title">
		<a href="<?php the_permalink(); ?>"><?php pinboard_first_blockquote(); ?></a>
	</div><!-- .entry-title -->

</article><!-- #post-## -->

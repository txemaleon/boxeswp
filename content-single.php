<?php
/**
 * @package boxes
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header" style="background:url('<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : get_first_image_url() ; ?>')">

		<h1 class="entry-title"><?php the_title(); ?></h1>


	</header><!-- .entry-header -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( ' / ' );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ' / ' );

			if ( ! boxes_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( !'' != $tag_list ) {
					$meta_text = '%2$s';
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' == $tag_list ) {
					$meta_text = '%1$s';
				} else {
					$meta_text = '%1$s / %2$s';
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list
			);
		?>

	</footer><!-- .entry-meta -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'boxes' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	
	<aside id="viewcomments"><a href="#comments" title="Ver Comentarios">Ver Comentarios</a></aside>

</article><!-- #post-## -->

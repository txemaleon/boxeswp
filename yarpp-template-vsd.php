<?php
/*
YARPP Template: Boxes Related
Description: Boxes related posts for YARPP
Author: Txema León (http://leon-labs.net/ )
*/ ?>
<?php if ( have_posts() ):?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php if( has_post_thumbnail() ) : ?>
	<?php get_template_part( 'content', 'related' ); ?>
	<?php endif; ?>
<?php endwhile; ?>

<?php endif; ?>
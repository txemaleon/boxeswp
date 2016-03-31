<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package boxes
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function boxes_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'boxes_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function boxes_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'boxes_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function boxes_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'boxes' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'boxes_wp_title', 10, 2 );



if( !function_exists( "get_the_post_thumbnail_url" ) ) {
/**
 * Returns the URL of the thumbnail in full size
 *
 * @param string $id gets the post ID you want to take the URL from
 */
	function get_the_post_thumbnail_url( $id = NULL ) {
	
		$thumbnail = NULL;

		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full' );
		return $thumbnail[0];

	}
}

if( !function_exists( "get_first_image_url" ) ) {
/**
 * Returns the URL of the thumbnail in full size
 *
 * @param string $id gets the post ID you want to take the URL from
 */
	function get_first_image_url( $id = NULL ) {

		$src = NULL;

		$document = new DOMDocument();
		$content = apply_filters( 'the_content', get_the_content( '', true ) );
		if( '' != $content ) {
			libxml_use_internal_errors( true );
			$document->loadHTML( $content );
			$document->loadHTML( '<?xml encoding="UTF-8">' . $content );
			libxml_clear_errors();
			$images = $document->getElementsByTagName( 'img' );
			$document = new DOMDocument();
			if( $images->length ) {
				$image= $images->item( $images->length - 1 );
				$src = $image->getAttribute( 'src' );
			}
		}
		return $src;
	}
}




if( !function_exists( "boxes_get_the_slug" ) ) {
/**
 * Returns the slug of the page being viewed
 *
 * @param string $id gets the post ID you want to take the URL from
 */
	function boxes_get_the_slug() {
	    return $_SERVER['REDIRECT_URL'];
    }
}



if ( ! function_exists( 'pinboard_first_blockquote' ) ) :
function pinboard_first_blockquote() {
	$document = new DOMDocument();
	$content = apply_filters( 'the_content', get_the_content( '', true ) );
	if( '' != $content ) {
		libxml_use_internal_errors( true );
		$document->loadHTML( '<?xml encoding="UTF-8">' . $content );
		libxml_clear_errors();
		$blockquotes = $document->getElementsByTagName( 'blockquote' );
		for( $i = 0; $i < $blockquotes->length; $i++ ) {
			$blockquote = $blockquotes->item($i);
			$document = new DOMDocument();
			$document->appendChild( $document->importNode( $blockquote, true ) );
			echo $document->saveHTML();
		}
	}
}
endif;

// Quitar <p> de imágenes y blockquotes
if ( ! function_exists( 'filter_ptags' ) ) :
	function filter_ptags($content){
	   $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)*\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	   $content = preg_replace('/<p>\s*(<span.*>)?(<iframe .*>)*(<\/span>)\s*<\/p>/iU', '\2', $content);
	   $content = preg_replace('/<p>\s*(<iframe .*>)*\s*<\/p>/iU', '\1', $content);
	   $content = preg_replace('/<blockquote><p>\s*(.*)?\s*<\/p><\/blockquote>/iU', '<blockquote>\1</blockquote>', $content);
	   
	   return $content;
	}
endif;

add_filter('the_content', 'filter_ptags');

// Quitar hashtags de los títulos
if ( ! function_exists( 'filter_hashtags' ) ) :
	function filter_hashtags($content){
	   $content = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '', $content);
	   
	   return $content;
	}
endif;

add_filter('the_title', 'filter_hashtags');

// Quitar el ancho predeterminado de las imágenes
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s?/', "", $html );
   return $html;
}

// Excluir categoría Live y meter en la home sólo posts con thumbnail
function exclude_category($query) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$cat = get_cat_ID('Live');
		$query->set('cat', '-'.$cat);
		$query->set('meta_key', '_thumbnail_id' );
	}
	return $query;
}
add_filter('pre_get_posts', 'exclude_category');

// Auto add thumbnails to posts:
if ( function_exists( 'add_theme_support' ) && !function_exists('easy_add_thumbnail') ) {

	add_theme_support( 'post-thumbnails' ); // This should be in your theme. But we add this here because this way we can have featured images before swicth to a theme that supports them.
  
	function easy_add_thumbnail($post) {
      
		$already_has_thumb = has_post_thumbnail();
     
		if (!$already_has_thumb)  {

			$attached_image = get_children( "order=ASC&post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );

			if ($attached_image) {
			
			    $attachment_values = array_values($attached_image);
			    $first_child_image = $attachment_values[0];
			                                        
			    add_post_meta($post->ID, '_thumbnail_id', $first_child_image->ID, true);                                 
			
			}                 
		}
	}
	/*

	add_action('the_post', 'easy_add_thumbnail');

	// hooks added to set the thumbnail when publishing too
	add_action('new_to_publish', 'easy_add_thumbnail');
	add_action('draft_to_publish', 'easy_add_thumbnail');
	add_action('pending_to_publish', 'easy_add_thumbnail');
	add_action('future_to_publish', 'easy_add_thumbnail');
*/

}

if ( ! function_exists( 'boxes_fonts_header' ) ) :

	function boxes_fonts_header() {
	    wp_register_style('Raleway', 'http://fonts.googleapis.com/css?family=Raleway:600,400,200');
		wp_register_style('Montserrat', 'http://fonts.googleapis.com/css?family=Montserrat:700,400');
		wp_enqueue_style( 'Raleway');
	    wp_enqueue_style( 'Montserrat');
	}
	
	add_action('wp_print_styles', 'boxes_fonts_header');

endif;

?>
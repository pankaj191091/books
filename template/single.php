<?php
/**
 * The template for displaying all books single posts
 * @package WordPress
 */

get_header();
?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

<?php
/* Start the Loop */
while (have_posts()):
    the_post();
    the_title('<h1 class="entry-title">', '</h1>');
    the_post_thumbnail('thumbnail');
    the_excerpt('<p>', '</p>');
    the_content('<p>', '</p>');
    $postid      = get_the_ID();
    $isbn        = get_post_meta($postid, 'isbn', true);
    $amazon_link = get_post_meta($postid, 'amazon_link', true);
    if ($isbn):
        echo '<h5>ISBN NUMBER:</h5>' . $isbn;
    endif;
    the_terms($postid, 'authors', "<h5>Book Authors:</h5>");
    if ($amazon_link):
        echo '<h5><u><a href="' . $amazon_link . '" target="_blank">Book Link</a></u></h5>';
    endif;
endwhile; // End of the loop.
?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_sidebar();
?>
</div><!-- .wrap -->

<?php
get_footer();
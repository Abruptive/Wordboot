<?php

/**
 * Template: Single (Item)
 * 
 * The single post template used when a visitor 
 * requests a single post from the custom post type.
 *
 * @link       https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
 * @package    Plugin
 * @subpackage Plugin/public
 * @author     Plugin_Author <email@example.com>
 */

?>

<?php get_header(); ?>

<div class="wrapper">

	<div class="container">

		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>

			<article id="<?php the_ID(); ?>" <?php post_class(); ?>>

				<header>
					<?php the_title( '<h1>', '</h1>' ); ?>
				</header>

				<div>
					<?php the_content(); ?>
				</div>

			</article>

		<?php endwhile; else: ?>

			<p>
				<?php _e( 'Sorry, your item could not be found.', 'plugin' ); ?>
			</p>

		<?php endif; ?>

	</div>	

</div>

<?php get_footer(); ?>

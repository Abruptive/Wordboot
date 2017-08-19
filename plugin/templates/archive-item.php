<?php

/**
 * Template: Archive (Item)
 * 
 * The archive post type template is used when visitors 
 * request the custom post type archive.
 *
 * @link          https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
 * @package       Plugin
 * @subpackage    Plugin/public
 * @author        Plugin_Author <email@example.com>
 */

?>

<?php get_header(); ?>

<div class="wrapper">

	<div class="container">

		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>

			<article id="<?php the_ID(); ?>" <?php post_class(); ?>>

				<header>
					<a href="<?php the_permalink(); ?>" rel="nofollow">
						<?php the_title( '<h1>', '</h1>' ); ?>
					</a>
				</header>

				<div>
					<?php the_excerpt(); ?>
				</div>

			</article>

		<?php endwhile; ?>

			<div class="nav-previous alignleft">
				<?php next_posts_link( 'Older items' ); ?>
			</div>

			<div class="nav-next alignright">
				<?php previous_posts_link( 'Newer items' ); ?>
			</div>
		
		<?php else: ?>

			<p>
				<?php _e( 'Sorry, no items could be found.', 'plugin' ); ?>
			</p>

		<?php endif; ?>

	</div>	

</div>

<?php get_footer(); ?>

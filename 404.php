<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 */

get_header(); ?>

	<div id="primary" class="sq-content-area">
		<main id="main" class="sq-site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'squire' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p class="lead"><?php esc_html_e( 'It looks like nothing was found at this location. All glory to the hypnotoad!', 'squire' ); ?></p>

					<img class="aligncenter" src="<?php echo get_template_directory_uri(); ?>/assets/img/404.gif" alt="Error 404">

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();

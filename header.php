<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="sq off-canvas-wrapper">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'squire' ); ?></a>

	<div class="off-canvas position-left" id="offCanvas" data-off-canvas>
		<!-- Close button -->
		<button class="close-button" aria-label="Close menu" type="button" data-close>
			<span aria-hidden="true">&times;</span>
		</button>

		<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
			<nav id="mobile-navigation" class="mobile-navigation" role="navigation">
				<?php wp_nav_menu(
					array(
						'container'      => false,
						'theme_location' => 'primary-menu',
						'menu_id'        => 'mobile-primary-menu',
						'menu_class'     => 'vertical menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
						'walker'         => new SQUIRE_Sidebar_Walker(),
					)
				); ?>
			</nav><!-- #mobile-navigation -->
			<?php
		endif;
		?>
	</div>

	<div class="off-canvas-content" data-off-canvas-content>
		<div class="row expanded collapse large-unstack large-align-stretch">
			<aside class="sq-sidebar large-3 xlarge-2 columns">
				<header id="masthead" class="site-header" role="banner">
					<div class="sq-branding">
						<a class="button menu-toggle" data-toggle="offCanvas"><i class="fa fa-bars"></i></a>
						<?php
						the_custom_logo();

						if ( is_front_page() && is_home() ) : ?>
							<h1 class="sq-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="sq-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="sq-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
							<?php
						endif; ?>

					</div><!-- .site-branding -->
				</header><!-- #masthead -->

				<div class="widget-area">
					<?php

					if ( has_nav_menu( 'primary-menu' ) ) : ?>
						<nav id="site-navigation" class="main-navigation"
						     role="navigation">
							<?php wp_nav_menu(
								array(
									'container'      => false,
									'theme_location' => 'primary-menu',
									'menu_id'        => 'primary-menu',
									'menu_class'     => 'menu',
									'items_wrap'     => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
									'walker'         => new SQUIRE_Sidebar_Walker(),
								)
							); ?>
						</nav><!-- #site-navigation -->
					<?php
					endif;

					if ( is_active_sidebar( 'sidebar-top' ) ) {
						dynamic_sidebar( 'sidebar-top' );
					}

					squire_theme_mobile_sub_nav();

					if ( is_active_sidebar( 'sidebar-bottom' ) ) {
						dynamic_sidebar( 'sidebar-bottom' );
					}
					?>
				</div>

				<footer id="colophon" class="sq-footer show-for-large" role="contentinfo">
					<?php
					if ( has_nav_menu( 'social-links' ) ) : ?>
						<nav class="social-navigation align-self-bottom column" role="navigation" aria-label="<?php _e( 'Social Links Menu', 'squire' ); ?>">
							<?php
							wp_nav_menu( array(
								'container'      => false,
								'theme_location' => 'social-links',
								'menu_class'     => 'social-links-menu',
								'depth'          => 1,
								'link_before'    => '<span class="screen-reader-text">',
								'link_after'     => '</span>'
							) );
							?>
						</nav><!-- .social-navigation -->
					<?php endif; ?>

					<div class="sq-info align-self-bottom column">
						<?php squire_footer_text(); ?>
					</div><!-- .site-info -->
				</footer><!-- #colophon -->
			</aside><!-- .sq-sidebar -->

			<div id="content" class="sq-content large-9 xlarge-10 columns">

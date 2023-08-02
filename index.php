<?php
/**
 * Plugin Name: Fixed Position Button Bar
 * Version: 0.9
 * Plugin URI: https://offices.vassar.edu/faculty-housing/
 * Description: This sets up a fixed-position widget area, intended for button widgets. Do not use any other widgets here.
 * Author: Chris Silverman
 * Author URI: https://www.csilverman.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * @package WordPress
 * @author Chris Silverman
 * @since 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fixed_widget_area_init() {

	register_sidebar( array(
		'name'          => 'Button bar',
		'id'            => 'button_bar',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

}
add_action( 'widgets_init', 'fixed_widget_area_init' );

//  Sets up the widget area after the content
add_action('vassarparent__after_entryContent', function() {
    echo '<style>
.wp-block-buttons {
    gap: 0.6rem !important;
}
    .fixed-button-bar {
    position: fixed;
    bottom: 1.4em;
    right: 1em;
    z-index: 1000;
    }

    .fixed-button-bar a.wp-block-button__link:link,
    .fixed-button-bar a.wp-block-button__link:visited {
    background: #651a2c;
    display: inline-block;
    padding: 0.4em 0.6em;
    border-radius: 0.4em;
    border: 3px solid #fff;
    box-shadow: 0 0.1em 0.1em rgba(0,0,0,0.4);
    color: #fff;
    text-decoration: none;
    font-weight: normal;
    letter-spacing: normal;
    transition-duration: 0.2s;
    }
.wp-block-button__link {
	margin: 0 0 1rem 0;
}
.fixed-button-bar a.wp-block-button__link:hover,
.fixed-button-bar a.wp-block-button__link:focus,
.fixed-button-bar a.wp-block-button__link:visited:hover,
.fixed-button-bar a.wp-block-button__link:visited:focus {
    background: #e00 !important;
    color: #fff;
}
</style>';

if ( is_active_sidebar( 'button_bar' )) {
    //  I need to add a tabindex attribute to each button, so they can be tabbed to immediately. While
    //  this is less of a consideration for informational links, a "leave quickly" button, like we have on
    //  SAVP, would need to be immediately focusable.

    //  The simplest way to do this would be by getting the markup of each button and adding tabindex
    //  via a search and replace. Since there's no way to get the contents of a sidebar, I have to use
    //  an output buffer for this.
    ob_start();
    dynamic_sidebar('button_bar');
    $button_bar_markup = ob_get_contents();
    ob_end_clean();

    //  At this point, $button_bar_markup contains all the markup in the fixed-position widget area.
    //  So add the tabindex by search/replace:
    $button_bar_markup = str_replace('class="wp-block-button__link"', 'class="wp-block-button__link" tabindex="1"', $button_bar_markup);

    //  And here's the final result.
    echo '<div class="fixed-button-bar">' . $button_bar_markup . '</div>';
}

});

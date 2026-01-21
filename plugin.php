<?php

/**
 * Plugin Name:     wp-plugin-parking
 * Description:     WordPress plugin to display information about parking.
 * Version:         1.0.1
 * Author:          CEA Informatics
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-plugin-parking
 *
 * @package         wp-plugin-parking
 */

if (!defined('ABSPATH')) exit;

function wpw_display_parking() {
    ob_start(); ?>
    <div id="wp-parking">
    <button id="wp-parking">Parking Info</button>
    <?php
    return ob_get_clean();
}

add_shortcode('wp-parking', 'wpw_display_parking');


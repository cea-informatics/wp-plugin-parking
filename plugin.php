<?php

/**
 * Plugin Name:     Custom Parking
 * Description:     The plugin displays information about parking availability.
 * Version:         1.1.1
 * Author:          CEA Informatics
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-plugin-parking
 *
 * @package         wp-plugin-parking
 */

if (!defined('ABSPATH')) exit;

/**
 * Retourne le nombre de places disponibles depuis le transient.
 * Si le transient a expiré, en génère un nouveau avec une valeur et durée aléatoires.
 */
function wpw_get_parking_spots() {
    $spots = get_transient('wpw_parking_spots');
    if ($spots === false) {
        $spots    = rand(3, 50);
        $duration = rand(60, 300); // durée aléatoire entre 1 et 5 minutes
        set_transient('wpw_parking_spots', $spots, $duration);
    }
    return (int) $spots;
}

/**
 * Injecte le badge parking dans le <header> du site via JS.
 */
function wpw_render_parking_header() {
    $spots = wpw_get_parking_spots();
    $color = $spots <= 5 ? '#e74c3c' : ($spots <= 15 ? '#f39c12' : '#27ae60');
    ?>
    <div id="wpw-parking-badge" style="
        display: none;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 2px solid <?php echo esc_attr($color); ?>;
        border-radius: 8px;
        padding: 4px 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,.18);
        font-family: sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #222;
        line-height: 1;
        cursor: default;
        user-select: none;
        margin-left: auto;
    " title="Places de parking disponibles">
        <span style="
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: <?php echo esc_attr($color); ?>;
            border-radius: 50%;
            color: #fff;
            font-size: 16px;
            font-weight: 900;
            line-height: 1;
        ">P</span>
        <span style="color: <?php echo esc_attr($color); ?>; font-size: 18px;">
            <?php echo esc_html($spots); ?>
        </span>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var badge = document.getElementById('wpw-parking-badge');
        var search = document.querySelector('[data-device="desktop"] .ct-header-search');
        if (search && badge) {
            search.insertAdjacentElement('beforebegin', badge);
            badge.style.display = 'flex';
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'wpw_render_parking_header');

function wpw_display_parking() {
    ob_start(); ?>
    <div id="wp-parking">
    <button id="wp-parking">Parking Info</button>
    <?php
    return ob_get_clean();
}

add_shortcode('wp-parking', 'wpw_display_parking');


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
    $color = $spots <= 5 ? '#c0392b' : ($spots <= 15 ? '#d4820a' : '#3a7a2e');
    ?>
    <div id="wpw-parking-badge" style="
        display: none;
        align-items: center;
        gap: 10px;
        font-family: sans-serif;
        cursor: default;
        user-select: none;
        margin-left: auto;
    " title="Places de parking disponibles">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 68" width="88" height="60" aria-hidden="true">
            <rect x="2" y="32" width="72" height="24" rx="6" fill="<?php echo esc_attr($color); ?>"/>
            <path d="M13 32 L21 16 Q23 11 28 11 L52 11 Q57 11 59 16 L67 32 Z" fill="<?php echo esc_attr($color); ?>"/>
            <circle cx="17" cy="56" r="10" fill="<?php echo esc_attr($color); ?>"/>
            <circle cx="17" cy="56" r="5" fill="#fff"/>
            <circle cx="59" cy="56" r="10" fill="<?php echo esc_attr($color); ?>"/>
            <circle cx="59" cy="56" r="5" fill="#fff"/>
            <rect x="63" y="1" width="26" height="26" rx="5" fill="<?php echo esc_attr($color); ?>"/>
            <text x="76" y="21" font-size="17" font-weight="900" fill="#fff" text-anchor="middle" font-family="Arial,sans-serif">P</text>
        </svg>
        <span style="color: <?php echo esc_attr($color); ?>; font-size: 34px; font-weight: 700; line-height: 1;">
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


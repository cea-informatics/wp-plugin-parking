<?php

/**
 * Plugin Name:     Custom Parking
 * Description:     The plugin displays information about parking availability.
 * Version:         1.2.7
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
        gap: 6px;
        font-family: sans-serif;
        cursor: default;
        user-select: none;
        margin-left: auto;
        color: <?php echo esc_attr($color); ?>;
    " title="Places de parking disponibles">
        <?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/parking-badge.svg'); ?>
        <span style="color: <?php echo esc_attr($color); ?>; font-size: 18px; font-weight: 700; line-height: 1;">
            <?php echo esc_html($spots); ?>
        </span>
    </div>
    <div id="wpw-parking-user" style="display: none; align-items: center;">
        <?php if (is_user_logged_in()) : ?>
            <a href="/account" style="display: flex; align-items: center;"><?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/parking-icon.svg'); ?></a>
        <?php else : ?>
            <a href="/login" style="font-family: sans-serif; font-size: 15px; font-weight: 600; text-decoration: none;">Connexion</a>
        <?php endif; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var badge = document.getElementById('wpw-parking-badge');
        var user  = document.getElementById('wpw-parking-user');
        var search = document.querySelector('[data-device="desktop"] .ct-header-search');
        if (search && badge) {
            search.insertAdjacentElement('beforebegin', badge);
            badge.style.display = 'flex';
        }
        if (search && user) {
            search.insertAdjacentElement('beforebegin', user);
            user.style.display = 'flex';
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'wpw_render_parking_header');


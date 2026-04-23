<?php

/**
 * Plugin Name:     Custom Parking
 * Description:     The plugin displays information about parking availability.
 * Version:         1.2.1
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
    " title="Places de parking disponibles">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <g clip-path="url(#wpw-clip)">
                <path d="M18.4533 3.56853C18.1889 3.56853 18.012 3.59405 17.9194 3.61957V5.30647C18.0288 5.33145 18.1647 5.34078 18.3512 5.34078C19.037 5.34078 19.4615 4.99291 19.4615 4.4084C19.4615 3.88209 19.0965 3.56853 18.4533 3.56853Z" fill="<?php echo esc_attr($color); ?>"/>
                <path d="M20.2152 5.72997C19.7746 6.14487 19.1219 6.33176 18.3589 6.33176C18.1893 6.33176 18.0372 6.32386 17.9198 6.30736V8.34964H16.6392V2.71237C17.0371 2.6448 17.5967 2.59434 18.3846 2.59434C19.1813 2.59434 19.7497 2.74707 20.1315 3.05199C20.4965 3.33983 20.7417 3.81475 20.7417 4.37444C20.741 4.93325 20.5537 5.40797 20.2152 5.72997ZM22.8198 0.27243H14.3056C13.6535 0.27243 13.1255 0.771207 13.1255 1.38674V9.42553C13.1255 10.0411 13.6535 10.54 14.3056 10.54H22.8198C23.4704 10.54 23.9998 10.0411 23.9998 9.42553V1.38674C23.9998 0.771207 23.4704 0.27243 22.8198 0.27243Z" fill="<?php echo esc_attr($color); ?>"/>
                <path d="M17.4794 17.9134C16.3948 17.9134 15.5151 17.0335 15.5151 15.9489C15.5151 14.864 16.3948 13.9847 17.4794 13.9847C18.564 13.9847 19.4429 14.864 19.4429 15.9489C19.4429 17.0335 18.564 17.9134 17.4794 17.9134ZM4.2755 17.9134C3.19074 17.9134 2.31103 17.0335 2.31103 15.9489C2.31103 14.864 3.19035 13.9847 4.2755 13.9847C5.36046 13.9847 6.2394 14.864 6.2394 15.9489C6.2394 17.0335 5.36007 17.9134 4.2755 17.9134ZM20.5591 11.682H2.2749L6.06885 5.12838C6.06885 5.12838 6.20598 4.85909 6.84591 4.85909H10.7713C10.7713 4.85909 11.7024 4.92225 11.6683 4.27678C11.6338 3.93422 11.4454 3.69962 11.0397 3.69962C10.6396 3.69962 5.81737 3.69962 5.81737 3.69962C5.81737 3.69962 5.57139 3.71101 5.30264 4.17416C5.0627 4.58538 1.70843 10.3967 0.960146 11.6995C0.417261 11.7968 0 12.3281 0 12.9735V18.9216C0 19.6419 0.509078 20.2132 1.13727 20.2132H2.27416V23.0645C2.27416 23.4192 2.5031 23.7048 2.78889 23.7048H4.62279C4.90258 23.7048 5.13678 23.4192 5.13678 23.0645V20.2132H16.5872V23.088C16.5872 23.4419 16.8155 23.7276 17.0961 23.7276H18.9069C19.1866 23.7276 19.4157 23.4419 19.4157 23.088V20.2132H20.5584C21.1869 20.2132 21.6958 19.6419 21.6958 18.9216V12.9735C21.6965 12.259 21.1876 11.682 20.5591 11.682Z" fill="<?php echo esc_attr($color); ?>"/>
            </g>
            <defs>
                <clipPath id="wpw-clip"><rect width="24" height="24" fill="white"/></clipPath>
            </defs>
        </svg>
        <span style="color: <?php echo esc_attr($color); ?>; font-size: 18px; font-weight: 700; line-height: 1;">
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


<?php
/**
 * Plugin Name: AIR: Group Connect Block for Telegram
 * Plugin URI: https://wordpress.org/plugins/air-group-connect-block-telegram/
 * Description: Creates a Gutenberg block that provides a beautiful and customizable link to a Telegram group or channel.
 * Version: 1.0.0
 * Author: Dan Zakirov
 * Author URI: https://profiles.wordpress.org/alexodiy/
 * Text Domain: air-telegram-group
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WP tested up to: 6.4
 *
 *     Copyright Dan Zakirov
 *
 *     This file is part of AIR: Group Connect Block for Telegram,
 *     a plugin for WordPress.
 *
 *     AIR: Group Connect Block for Telegram is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     AIR: Group Connect Block for Telegram is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package AIR_Group_Connect_Block_for_Telegram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load plugin textdomain.
 */
function air_gut_tg_load_plugin_textdomain() {
    // Load the translation files for the plugin.
    load_plugin_textdomain( 'air-telegram-group', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'air_gut_tg_load_plugin_textdomain' );

/**
 * Enqueue block editor assets.
 */
function air_gut_tg_enqueue_block_assets() {

    // Enqueue the block editor script.
    wp_enqueue_script(
        'air-gut-tg-block-sc',
        plugin_dir_url( __FILE__ ) . 'assets/js/block.js',
        array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n', 'wp-components' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/block.js' )
    );

    // Set up translations for the block editor script.
    wp_set_script_translations( 'air-gut-tg-block-sc', 'air-telegram-group', plugin_dir_path( __FILE__ ) . 'languages' );

    // Pass the URL to the block script.
    wp_localize_script(
        'air-gut-tg-block-sc',
        'airGutTgPluginData',
        array(
            'pluginUrl' => esc_url_raw(  plugin_dir_url( __FILE__ ) ),
        )
    );

    // Enqueue additional styles for the block editor.
    wp_enqueue_style(
        'air-gut-tg-additional-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css',
        array( 'air-gut-tg-block-style-frontend' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/admin-style.css' )
    );
}
add_action( 'enqueue_block_editor_assets', 'air_gut_tg_enqueue_block_assets' );

/**
 * Enqueue block assets for the frontend.
 */
function air_gut_tg_enqueue_block_assets_frontend() {
    // Register and enqueue styles for the frontend block.
    wp_register_style(
        'air-gut-tg-block-style-frontend',
        plugin_dir_url( __FILE__ ) . 'assets/css/block.css',
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/block.css' )
    );
    wp_enqueue_style( 'air-gut-tg-block-style-frontend' );

    // Enqueue frontend script.
    wp_enqueue_script(
        'air-gut-tg-frontend-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/frontend.js',
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/frontend.js' ),
        true
    );
}
add_action( 'enqueue_block_assets', 'air_gut_tg_enqueue_block_assets_frontend' );

/**
 * Register custom block category if it doesn't exist.
 *
 * @param array  $categories Default block categories.
 * @param object $post       The post being edited.
 *
 * @return array Modified block categories.
 */
function air_gut_tg_register_block_category( $categories, $post ) {
    // Define the target category details.
    $target_category = array(
        'slug'  => 'air-gut-tg',
        'title' => __( 'AIR Gutenberg Blocks', 'air-telegram-group' ),
    );

    // Check if the target category already exists.
    foreach ( $categories as $index => $category ) {
        if ( $category['slug'] === $target_category['slug'] ) {
            // Remove the existing target category from its position.
            unset( $categories[ $index ] );
            break;
        }
    }

    // Add the target category to the beginning of the categories array.
    array_unshift( $categories, $target_category );

    return $categories;
}
add_filter( 'block_categories_all', 'air_gut_tg_register_block_category', 3, 2 );
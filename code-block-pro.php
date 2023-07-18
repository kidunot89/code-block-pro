<?php
/**
 * Plugin Name:       Code Block Pro
 * Description:       Code highlighting powered by the VS Code engine
 * Plugin URI:        https://code-block-pro.com/?utm_campaign=plugin&utm_source=plugin-uri
 * Author:            Kevin Batdorf
 * Author URI:        https://code-block-pro.com/?utm_campaign=plugin&utm_source=author-uri
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Version:           1.21.0
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       code-block-pro
 *
 * @package           kevinbatdorf
 */

/**
 * Get Plugin URL for the Current Site
 *
 * @param string $file  The file path of the plugin.
 *
 * @return string
 */
function get_site_plugin_dir_url($file) {
    // get the original plugin dir URL
    $original_url = plugin_dir_url($file);

    // get the main site URL and the network home URL
    $main_site_url = get_home_url(1);  // 1 is the blog_id of the main site
    $network_home_url = home_url();

    // replace the main site URL with the network home URL
    $network_plugin_dir_url = str_replace($main_site_url, $network_home_url, $original_url);

    return $network_plugin_dir_url;
}

add_action('init', function () {
    register_block_type(__DIR__ . '/build');
    wp_set_script_translations('kevinbatdorf/code-block-pro', 'code-block-pro');
    wp_add_inline_script('kevinbatdorf-code-block-pro-view-script', 'window.codeBlockPro = ' . wp_json_encode([
        'pluginUrl' => esc_url_raw(get_site_plugin_dir_url(__FILE__)),
    ]) . ';');
});

add_action('admin_init', function () {
    wp_add_inline_script('kevinbatdorf-code-block-pro-editor-script', 'window.codeBlockPro = ' . wp_json_encode([
        'pluginUrl' => esc_url_raw(get_site_plugin_dir_url(__FILE__)),
        'canSaveHtml' => current_user_can('unfiltered_html'),
    ]) . ';');
});

include_once(__DIR__ . '/php/compatibility.php');
include_once(__DIR__ . '/php/router.php');
include_once(__DIR__ . '/php/routes.php');

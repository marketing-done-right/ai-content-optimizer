<?php
/*
* Plugin Name: AI Content Optimizer
* Description: Uses AI to analyze content and provide recommendations for improving SEO, readability, and engagement.
* Version: 1.0.0
* Author: Hans Steffens & Marketing Done Right LLC
* Author URI:  https://marketingdr.co
* Text Domain: ai-content-optimizer
* License: GPL v3 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.html 
*
* AI Content Optimizer is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* AI Content Optimizer is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with AI Content Optimizer.  If not, see <https://www.gnu.org/licenses/>.
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Defines the path to the plugin directory.
 *
 * This constant is used throughout the plugin to reference the plugin's root directory.
 */
define( 'AICO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Defines the URL to the plugin directory.
 *
 * This constant is used to reference the URL to the plugin's assets.
 */
define( 'AICO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the main class responsible for initializing the plugin.
require_once AICO_PLUGIN_PATH . 'includes/class-aico-main.php';

/**
 * Initializes the AI Content Optimizer plugin.
 *
 * This function is hooked to the 'plugins_loaded' action and initializes the main plugin class,
 * ensuring that the plugin's functionality is ready after all plugins have been loaded.
 *
 * @return void
 */
function aico_init() {
    \MarketingDoneRight\AIContentOptimizer\AICO_Main::get_instance();
}
add_action( 'plugins_loaded', 'aico_init' );
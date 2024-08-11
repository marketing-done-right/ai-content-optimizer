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

// Add settings page to WordPress admin.
add_action( 'admin_menu', 'aico_add_admin_menu' );
add_action( 'admin_init', 'aico_settings_init' );

// Function to add settings page to WordPress admin.
function aico_add_admin_menu() {
    add_options_page(
        'AI Content Optimizer',
        'AI Content Optimizer',
        'manage_options',
        'ai-content-optimizer',
        'aico_options_page'
    );
}

// Function to initialize settings.
function aico_settings_init() {
    register_setting( 'aico_options', 'aico_api_key' );
    register_setting( 'aico_options', 'aico_ai_model' );
    register_setting( 'aico_options', 'aico_max_tokens' );
    register_setting( 'aico_options', 'aico_rate_limit' );

    add_settings_section(
        'aico_section_developers',
        __( 'OpenAI API Settings', 'wordpress' ),
        null,
        'aico_options'
    );

    add_settings_field(
        'aico_api_key',
        __( 'OpenAI API Key', 'wordpress' ),
        'aico_api_key_render',
        'aico_options',
        'aico_section_developers'
    );

    add_settings_field(
        'aico_ai_model',
        __( 'AI Model', 'wordpress' ),
        'aico_ai_model_render',
        'aico_options',
        'aico_section_developers'
    );

    add_settings_field(
        'aico_max_tokens',
        __( 'Max Tokens', 'wordpress' ),
        'aico_max_tokens_render',
        'aico_options',
        'aico_section_developers'
    );

    add_settings_field(
        'aico_rate_limit',
        __( 'Daily Token Limit', 'wordpress' ),
        'aico_rate_limit_render',
        'aico_options',
        'aico_section_developers'
    );
}

// Function to render API key field.
function aico_api_key_render() {
    $api_key = get_option( 'aico_api_key' );
    echo '<input style="width:50%;" type="text" name="aico_api_key" value="' . esc_attr( $api_key ) . '" />';
}

// Function to render AI model selection.
function aico_ai_model_render() {
    $ai_model = get_option( 'aico_ai_model', 'gpt-3.5-turbo' );
    ?>
    <select name="aico_ai_model">
        <option value="gpt-3.5-turbo" <?php selected( $ai_model, 'gpt-3.5-turbo' ); ?>>GPT-3.5 Turbo</option>
        <option value="gpt-4-turbo" <?php selected( $ai_model, 'gpt-4-turbo' ); ?>>GPT-4 Turbo</option>
        <option value="gpt-4o-mini" <?php selected( $ai_model, 'gpt-4o-mini' ); ?>>GPT-4o Mini</option>
    </select>
    <?php
}

// Function to render max tokens input field.
function aico_max_tokens_render() {
    $max_tokens = get_option( 'aico_max_tokens', 500 );
    echo '<input type="number" name="aico_max_tokens" value="' . esc_attr( $max_tokens ) . '" min="1" max="4096" />';
}

// Function to render rate limit input field.
function aico_rate_limit_render() {
    $rate_limit = get_option( 'aico_rate_limit', 10000 );
    echo '<input type="number" name="aico_rate_limit" value="' . esc_attr( $rate_limit ) . '" min="1" />';
}

// Function to render settings page.
function aico_options_page() {
    ?>
    <form action="options.php" method="post">
        <h1>AI Content Optimizer</h1>
        <?php
        settings_fields( 'aico_options' );
        do_settings_sections( 'aico_options' );
        submit_button();
        ?>
    </form>
    <?php
}

// Function to get suggestions from OpenAI API.
function aico_get_openai_suggestions( $content ) { 
    $api_key = get_option( 'aico_api_key' );
    $ai_model = get_option( 'aico_ai_model', 'gpt-3.5-turbo' );
    $max_tokens = get_option( 'aico_max_tokens', 500 );
    $rate_limit = get_option( 'aico_rate_limit', 10000 );

    if ( ! $api_key ) {
        return 'API key is missing. Please add it in the plugin settings.';
    }

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $body = json_encode([
        'model' => $ai_model,
        'messages' => [
            ['role' => 'system', 'content' => 'You are an SEO and content optimization expert.'],
            ['role' => 'user', 'content' => 'Analyze the following content and provide recommendations for SEO, readability, and engagement: ' . $content],
        ],
        'max_tokens' => $max_tokens,
        'temperature' => 0.7,
    ]);

    // Example of how you might track and enforce rate limits.
    $used_tokens = get_option( 'aico_used_tokens', 0 );

    if ( $used_tokens + $max_tokens > $rate_limit ) {
        return 'Daily token limit exceeded. Please try again tomorrow.';
    }

    $response = wp_remote_post( $endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ],
        'body' => $body,
        'timeout' => 15,
    ]);

    if ( is_wp_error( $response ) ) {
        error_log( 'OpenAI API Request Failed: ' . $response->get_error_message() );
        return 'Failed to connect to OpenAI API.';
    }

    $body = wp_remote_retrieve_body( $response );
    error_log( 'OpenAI API Response: ' . $body );
    $result = json_decode( $body, true );

    // Track the used tokens for rate limiting.
    update_option( 'aico_used_tokens', $used_tokens + $max_tokens );

    return $result['choices'][0]['message']['content'] ?? 'No suggestions available.';
}

// Add a meta box to the post editor.
add_action( 'add_meta_boxes', 'aico_add_meta_box' );
function aico_add_meta_box() { 
    add_meta_box(
        'aico-content-analysis',
        'AI-Powered Content Analysis',
        'aico_display_meta_box',
        'post',
        'normal',
        'high'
    );
}

function aico_display_meta_box( $post ) {
    $content = $post->post_content;
    $suggestions = aico_get_openai_suggestions( $content );
    echo '<p><strong>AI Suggestions:</strong></p>';
    echo '<div style="padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">';
    echo wp_kses_post( nl2br( $suggestions ) );
    echo '</div>';
}

// Add a confirmation message after saving the API key.
function aico_admin_notices() {
    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
        // Check the API key immediately after saving settings.
        $api_key = get_option( 'aico_api_key' );
        $response = wp_remote_get( 'https://api.openai.com/v1/models', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
            ],
        ]);

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            echo '<div class="notice notice-error is-dismissible"><p>There was an issue connecting to the OpenAI API. Please check your API key.</p></div>';
        } else {
            echo '<div class="notice notice-success is-dismissible"><p>API key verified and connected successfully.</p></div>';
        }
    }
}
add_action( 'admin_notices', 'aico_admin_notices' );

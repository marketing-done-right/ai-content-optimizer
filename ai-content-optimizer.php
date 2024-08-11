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

// Function to get suggestions from OpenAI API.
function aico_get_openai_suggestions( $content ) { 
    $api_key = '';

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $body = json_encode([
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'system', 'content' => 'You are an SEO and content optimization expert.'],
            ['role' => 'user', 'content' => 'Analyze the following content and provide recommendations for SEO, readability, and engagement: ' . $content],
        ],
        'max_tokens' => 500, 
        'temperature' => 0.7,
    ]);

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
    echo '<p>' . esc_html( $suggestions ) . '</p>';
}

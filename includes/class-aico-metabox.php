<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AICO_Metabox
 *
 * Manages the creation and functionality of the AI-powered content analysis meta box within the WordPress post editor.
 *
 * This class follows the Singleton pattern to ensure that only one instance of the class exists.
 *
 * @package MarketingDoneRight\AIContentOptimizer
 */
class AICO_Metabox {

    /**
     * The single instance of the AICO_Metabox class.
     *
     * @var AICO_Metabox|null
     */
    private static $instance;

    /**
     * Retrieves the single instance of the AICO_Metabox class.
     *
     * This method implements the Singleton pattern, ensuring that only one instance of this class exists.
     *
     * @return AICO_Metabox The single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AICO_Metabox constructor.
     *
     * The constructor is private to enforce the Singleton pattern. It sets up actions to add the meta box
     * and handle AJAX requests for content analysis.
     */
    private function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'wp_ajax_aico_analyze_content', [ $this, 'analyze_content' ] );
    }

    /**
     * Adds the AI-powered content analysis meta box to the WordPress post editor.
     *
     * This method hooks into WordPress to add the meta box to the post editor screen.
     */
    public function add_meta_box() {
        $post_types = ['post', 'page'];
        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'aico-content-analysis',
                'AI-Powered Content Analysis',
                [ $this, 'display_meta_box' ],
                $post_type, // Register meta box for each post type
                'normal',
                'high'
            );
        }
    }

    /**
     * Displays the content of the AI-powered content analysis meta box.
     *
     * This method generates the HTML for the meta box, including a button to trigger content analysis
     * and a container to display the AI suggestions.
     *
     * @param \WP_Post $post The current post object.
     */
    public function display_meta_box( $post ) {
        // Retrieve the latest saved suggestions.
        $saved_suggestions = get_post_meta( $post->ID, '_aico_latest_suggestions', true );
        ?>
        <p><strong>AI Suggestions:</strong></p>
        <div id="aico-suggestions" style="padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">
            <?php
            if ( ! empty( $saved_suggestions ) ) {
                echo wp_kses_post( $saved_suggestions );
            } else {
                echo '<p>No suggestions available yet. Click the button below to analyze the content.</p>';
            }
            ?>
        </div>
        <button style="margin-top:20px; display:flex; justify-content:center; align-items:center;" type="button" id="aico-analyze-button" class="button button-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" style="margin-right: 5px;">
                <path fill="currentColor" d="M19.92.897a.447.447 0 0 0-.89-.001c-.12 1.051-.433 1.773-.922 2.262-.49.49-1.21.801-2.262.923a.447.447 0 0 0 0 .888c1.035.117 1.772.43 2.274.922.499.49.817 1.21.91 2.251a.447.447 0 0 0 .89 0c.09-1.024.407-1.76.91-2.263.502-.502 1.238-.82 2.261-.908a.447.447 0 0 0 .001-.891c-1.04-.093-1.76-.411-2.25-.91-.493-.502-.806-1.24-.923-2.273ZM11.993 3.82a1.15 1.15 0 0 0-2.285-.002c-.312 2.704-1.115 4.559-2.373 5.817-1.258 1.258-3.113 2.06-5.817 2.373a1.15 1.15 0 0 0 .003 2.285c2.658.3 4.555 1.104 5.845 2.37 1.283 1.26 2.1 3.112 2.338 5.789a1.15 1.15 0 0 0 2.292-.003c.227-2.631 1.045-4.525 2.336-5.817 1.292-1.291 3.186-2.109 5.817-2.336a1.15 1.15 0 0 0 .003-2.291c-2.677-.238-4.529-1.056-5.789-2.34-1.266-1.29-2.07-3.186-2.37-5.844Z"></path>
            </svg>
            Analyze Content with AI
        </button>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#aico-analyze-button').on('click', function() {
                    var postContent = '<?php echo esc_js( $post->post_content ); ?>';
                    var postId = '<?php echo esc_attr( $post->ID ); ?>';
                    var nonce = '<?php echo esc_attr(wp_create_nonce( 'aico_analyze_content_nonce' )); ?>';

                    // Disable the button
                    $(this).attr('disabled', true);

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aico_analyze_content',
                            content: postContent,
                            post_id: postId,
                            _ajax_nonce: nonce,
                        },
                        beforeSend: function() {
                            $('#aico-suggestions').html('<p>Analyzing...</p>');
                        },
                        success: function(response) {
                            $('#aico-suggestions').html(response);
                        },
                        error: function() {
                            $('#aico-suggestions').html('<p>An error occurred while processing your request.</p>');
                        },
                        complete: function() {
                            // Re-enable the button
                            $('#aico-analyze-button').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * Handles the AJAX request to analyze content using the OpenAI API.
     *
     * This method processes the AJAX request sent by the meta box, analyzes the content using the OpenAI API,
     * converts the AI suggestions from markdown to HTML, and saves the suggestions to the post meta.
     */
    public function analyze_content() {
        // Verify nonce
        if ( ! isset( $_POST['_ajax_nonce'] ) || ! wp_verify_nonce( $_POST['_ajax_nonce'], 'aico_analyze_content_nonce' ) ) {
            wp_send_json_error( 'Nonce verification failed', 400 );
            wp_die(); // Stop execution if nonce fails
        }

        if ( isset($_POST['content']) && !empty($_POST['content']) ) {
            $content = sanitize_text_field( wp_unslash( $_POST['content'] ) );
            $post_id = intval( $_POST['post_id'] );
            // Get suggestions from the API
            $suggestions = AICO_API::get_instance()->get_suggestions( $content );
            // Convert markdown to HTML
            $suggestions_html = AICO_Utils::get_instance()->convert_markdown_to_html( $suggestions );
            // Save the suggestions to the post meta
            update_post_meta( $post_id, '_aico_latest_suggestions', $suggestions_html );

            echo wp_kses_post( nl2br( $suggestions_html ) );
        } else {
            echo '<p>No content available to analyze.</p>';
        }

        wp_die();
    }
}

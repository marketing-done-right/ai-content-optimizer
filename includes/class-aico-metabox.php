<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AICO_Metabox {

    private static $instance;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'wp_ajax_aico_analyze_content', [ $this, 'analyze_content' ] );
    }

    public function add_meta_box() {
        add_meta_box(
            'aico-content-analysis',
            'AI-Powered Content Analysis',
            [ $this, 'display_meta_box' ],
            'post',
            'normal',
            'high'
        );
    }

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
        <button style="margin-top:20px;" type="button" id="aico-analyze-button" class="button button-primary">Analyze Content with AI</button>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#aico-analyze-button').on('click', function() {
                    var postContent = '<?php echo esc_js( $post->post_content ); ?>';
                    var postId = '<?php echo $post->ID; ?>';

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aico_analyze_content',
                            content: postContent,
                            post_id: postId,
                        },
                        beforeSend: function() {
                            $('#aico-suggestions').html('<p>Analyzing...</p>');
                        },
                        success: function(response) {
                            $('#aico-suggestions').html(response);
                        },
                        error: function() {
                            $('#aico-suggestions').html('<p>An error occurred while processing your request.</p>');
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function analyze_content() {
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

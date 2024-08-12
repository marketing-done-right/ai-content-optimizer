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
        add_meta_box(
            'aico-content-analysis',
            'AI-Powered Content Analysis',
            [ $this, 'display_meta_box' ],
            'post',
            'normal',
            'high'
        );
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

    /**
     * Handles the AJAX request to analyze content using the OpenAI API.
     *
     * This method processes the AJAX request sent by the meta box, analyzes the content using the OpenAI API,
     * converts the AI suggestions from markdown to HTML, and saves the suggestions to the post meta.
     */
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

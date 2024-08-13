<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AICO_Settings
 *
 * Manages the settings page and options for the AI Content Optimizer plugin.
 *
 * This class follows the Singleton pattern to ensure that only one instance of the class exists.
 *
 * @package MarketingDoneRight\AIContentOptimizer
 */
class AICO_Settings {
    
    /**
     * The single instance of the AICO_Settings class.
     *
     * @var AICO_Settings|null
     */
    private static $instance;

    /**
     * Retrieves the single instance of the AICO_Settings class.
     *
     * This method implements the Singleton pattern, ensuring that only one instance of this class exists.
     *
     * @return AICO_Settings The single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AICO_Settings constructor.
     *
     * The constructor is private to enforce the Singleton pattern. It sets up actions to add the settings page
     * and initialize the settings fields.
     */
    private function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'settings_init' ] );
    }

    /**
     * Adds the AI Content Optimizer settings page to the WordPress admin menu.
     *
     * This method hooks into WordPress to add the settings page under the "Settings" menu.
     */
    public function add_admin_menu() {
        add_options_page(
            'AI Content Optimizer',
            'AI Content Optimizer',
            'manage_options',
            'ai-content-optimizer',
            [ $this, 'options_page' ]
        );
    }

    /**
     * Initializes the settings for the plugin.
     *
     * Registers settings and adds the necessary sections and fields to the settings page.
     */
    public function settings_init() {
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
            [ $this, 'api_key_render' ],
            'aico_options',
            'aico_section_developers'
        );

        add_settings_field(
            'aico_ai_model',
            __( 'AI Model', 'wordpress' ),
            [ $this, 'ai_model_render' ],
            'aico_options',
            'aico_section_developers'
        );

        add_settings_field(
            'aico_max_tokens',
            __( 'Max Tokens', 'wordpress' ),
            [ $this, 'max_tokens_render' ],
            'aico_options',
            'aico_section_developers'
        );

        add_settings_field(
            'aico_rate_limit',
            __( 'Rate Limit', 'wordpress' ),
            [ $this, 'rate_limit_render' ],
            'aico_options',
            'aico_section_developers'
        );
    }

    /**
     * Renders the OpenAI API key field.
     *
     * Displays a password input field for the API key, with a placeholder indicating that the key is stored securely.
     */
    public function api_key_render() {
        $api_key = get_option( 'aico_api_key' );
        echo '<input style="width:300px;" type="password" name="aico_api_key" value="' . esc_attr( $api_key ) . '" />';
        echo '<p><small>Your OpenAI API key is stored securely. Enter a new key to update it.</small></p>';
    }

    /**
     * Renders the AI model selection field.
     *
     * Displays radio buttons allowing the user to select the AI model to be used by the plugin.
     */
    public function ai_model_render() {
        $ai_model = get_option( 'aico_ai_model', 'gpt-3.5-turbo' );
        ?>
        <label>
            <input type="radio" name="aico_ai_model" value="gpt-3.5-turbo" <?php checked( $ai_model, 'gpt-3.5-turbo' ); ?>>
            GPT-3.5 Turbo<br>
            <small>Fastest model. Best for most use cases.</small>
        </label><br><br>
        <label>
            <input type="radio" name="aico_ai_model" value="gpt-4-turbo" <?php checked( $ai_model, 'gpt-4-turbo' ); ?>>
            GPT-4 Turbo<br>
            <small>More powerful than GPT-3.5 but slower.</small>
        </label><br><br>
        <label>
            <input type="radio" name="aico_ai_model" value="gpt-4o" <?php checked( $ai_model, 'gpt-4o' ); ?>>
            GPT-4o<br>
            <small>Newest and most advanced model.</small>
        </label>
        <?php
    }

    /**
     * Renders the max tokens field.
     *
     * Displays a number input field allowing the user to set the maximum number of tokens the AI model can generate in a single response.
     */
    public function max_tokens_render() {
        $max_tokens = get_option( 'aico_max_tokens', 700 );
        echo '<input type="number" name="aico_max_tokens" value="' . esc_attr( $max_tokens ) . '" min="1" max="4096" />';
        echo '<button type="button" class="button-secondary" onclick="document.getElementsByName(\'aico_max_tokens\')[0].value=700;">Reset Usage</button>';
        echo '<p><small>Set the maximum number of tokens the AI model can generate in a single response.<br>Higher values allow for longer and more detailed suggestions, but will consume more of your daily token limit.</small></p>';
    }

    /**
     * Renders the rate limit field.
     *
     * Displays a number input field allowing the user to set a daily request limit for the AI Assistant.
     * If the limit is exceeded, subsequent requests will be rejected.
     */
    public function rate_limit_render() {
        $rate_limit = get_option( 'aico_rate_limit', 10000 );
        $used_requests = get_option( 'aico_used_requests', 0 );
        echo '<input type="number" name="aico_rate_limit" value="' . esc_attr( $rate_limit ) . '" min="1" />';
        echo '<button type="button" class="button-secondary" onclick="document.getElementsByName(\'aico_rate_limit\')[0].value=10000;">Reset Usage</button>';
        echo '<p><small>Set a daily request limit for the AI Assistant. If you exceed this limit, subsequent requests will be rejected.</small></p>';
        echo '<p><small><strong>Current usage:</strong> <span style="color: #666;">' . esc_html( $used_requests . ' / ' . $rate_limit ) . ' requests.</span></small></p>';
    }

    /**
     * Renders the settings page.
     *
     * This method generates the HTML for the settings page, displaying the various settings fields and the save button.
     */
    public function options_page() {
        ?>
        <div class="wrap">
        <form action="options.php" method="post">
            <h1>AI Content Optimizer</h1>
            <?php
            settings_fields( 'aico_options' );
            do_settings_sections( 'aico_options' );
            submit_button();
            ?>
        </form>
        </div>
        <?php
    }
}

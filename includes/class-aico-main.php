<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AICO_Main
 *
 * The main class for initializing and managing the AI Content Optimizer plugin.
 *
 * This class follows the Singleton pattern to ensure that only one instance of the class exists.
 * It handles the loading of dependencies and the initialization of core components.
 *
 * @package MarketingDoneRight\AIContentOptimizer
 */
class AICO_Main {
    
    /**
     * The single instance of the AICO_Main class.
     *
     * @var AICO_Main|null
     */
    private static $instance;

    /**
     * Retrieves the single instance of the AICO_Main class.
     *
     * This method implements the Singleton pattern, ensuring that only one instance of this class exists.
     *
     * @return AICO_Main The single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AICO_Main constructor.
     *
     * The constructor is private to enforce the Singleton pattern. It initializes the plugin by
     * loading dependencies and initializing core classes.
     */
    private function __construct() {
        $this->load_dependencies();
        $this->initialize_classes();
    }

    /**
     * Loads the required dependencies for the plugin.
     *
     * This method includes the necessary class files for the plugin's core functionality.
     */
    private function load_dependencies() {
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-settings.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-api.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-metabox.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-utils.php';
    }

    /**
     * Initializes the core classes of the plugin.
     *
     * This method ensures that each core component of the plugin is properly instantiated and ready for use.
     */
    private function initialize_classes() {
        AICO_Settings::get_instance();
        AICO_API::get_instance();
        AICO_Metabox::get_instance();
        AICO_Utils::get_instance();
    }
}

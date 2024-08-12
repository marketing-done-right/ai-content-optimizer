<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AICO_Main {
    
    private static $instance;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->initialize_classes();
    }

    private function load_dependencies() {
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-settings.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-api.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-metabox.php';
        require_once AICO_PLUGIN_PATH . 'includes/class-aico-utils.php';
    }

    private function initialize_classes() {
        AICO_Settings::get_instance();
        AICO_API::get_instance();
        AICO_Metabox::get_instance();
        AICO_Utils::get_instance();
    }
}

<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AICO_Utils
 *
 * Provides utility functions for the AI Content Optimizer plugin.
 *
 * This class follows the Singleton pattern to ensure that only one instance of the class exists.
 *
 * @package MarketingDoneRight\AIContentOptimizer
 */
class AICO_Utils {

    /**
     * The single instance of the AICO_Utils class.
     *
     * @var AICO_Utils|null
     */
    private static $instance;

    /**
     * Retrieves the single instance of the AICO_Utils class.
     *
     * This method implements the Singleton pattern, ensuring that only one instance of this class exists.
     *
     * @return AICO_Utils The single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AICO_Utils constructor.
     *
     * The constructor is private to enforce the Singleton pattern. It serves as a placeholder for any future
     * initialization code that might be necessary.
     */
    private function __construct() {
        // Initialization code if needed
    }

    /**
     * Converts markdown-style text to HTML.
     *
     * This method takes a string containing markdown-style formatting and converts it to equivalent HTML.
     *
     * @param string $text The text containing markdown-style formatting.
     * @return string The HTML-formatted string.
     */
    public function convert_markdown_to_html( $text ) {
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/###### (.*?)\n/', '<h6>$1</h6>', $text);
        $text = preg_replace('/##### (.*?)\n/', '<h5>$1</h5>', $text);
        $text = preg_replace('/#### (.*?)\n/', '<h4>$1</h4>', $text);
        $text = preg_replace('/### (.*?)\n/', '<h3>$1</h3>', $text);
        $text = preg_replace('/## (.*?)\n/', '<h2>$1</h2>', $text);
        $text = preg_replace('/# (.*?)\n/', '<h1>$1</h1>', $text);
        $text = preg_replace('/\n/', '<br>', $text);

        return $text;
    }
}

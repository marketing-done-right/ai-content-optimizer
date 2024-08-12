<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AICO_Utils {

    private static $instance;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Initialization code if needed
    }

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

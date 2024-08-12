<?php

namespace MarketingDoneRight\AIContentOptimizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AICO_API
 *
 * Handles communication with the OpenAI API for generating content suggestions.
 *
 * This class follows the Singleton pattern to ensure that only one instance of the class exists.
 *
 * @package MarketingDoneRight\AIContentOptimizer
 */
class AICO_API {

    /**
     * The single instance of the AICO_API class.
     *
     * @var AICO_API|null
     */
    private static $instance;

    /**
     * Retrieves the single instance of the AICO_API class.
     *
     * This method implements the Singleton pattern, ensuring that only one instance of this class exists.
     *
     * @return AICO_API The single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AICO_API constructor.
     *
     * The constructor is private to enforce the Singleton pattern. It serves as a placeholder for any future
     * initialization code that might be necessary.
     */
    private function __construct() {
        // Initialization code here if needed, this is set as placeholder for any future initialization code that might be necessary.
    }

    /**
     * Sends content to the OpenAI API for analysis and retrieves optimization suggestions.
     *
     * This method handles communication with the OpenAI API, including rate limiting and request throttling.
     * It sends content to the API and retrieves suggestions related to SEO, keyword density, readability, and engagement strategies.
     *
     * @param string $content The content to be analyzed by the AI.
     * @return string The suggestions provided by the AI, or an error message if the request fails.
     */
    public function get_suggestions( $content ) {
        static $last_request_time = null;

        $api_key = get_option( 'aico_api_key' );
        $ai_model = get_option( 'aico_ai_model', 'gpt-3.5-turbo' );
        $max_tokens = (int) get_option( 'aico_max_tokens', 700 );
        $rate_limit = get_option( 'aico_rate_limit', 10000 );
        $used_requests = get_option( 'aico_used_requests', 0 );

        if ( ! $api_key ) {
            return 'API key is missing. Please add it in the plugin settings.';
        }

        if ( $used_requests >= $rate_limit ) {
            return 'Daily request limit exceeded. Please try again tomorrow.';
        }

        $time_between_requests = 60 / $rate_limit;
        if ( $last_request_time && ( microtime(true) - $last_request_time < $time_between_requests ) ) {
            usleep( ( $time_between_requests - ( microtime(true) - $last_request_time ) ) * 1000000 );
        }
        $last_request_time = microtime(true);

        $endpoint = 'https://api.openai.com/v1/chat/completions';

        $body = wp_json_encode([
            'model' => $ai_model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert in SEO, readability analysis, and content engagement strategies. Your task is to provide detailed and actionable recommendations for optimizing web content. You are skilled in analyzing keyword density, readability scores, and content engagement techniques.'],
                ['role' => 'user', 'content' => 'Please analyze the following content and provide comprehensive suggestions in the following areas:

                0. **SEO-friendly URL**: Evaluate the URL structure and suggest an SEO-friendly URL based on the content. Consider incorporating relevant keywords and maintaining a concise and descriptive format.

                1. **Keyword Optimization**: Identify relevant keywords and phrases. Analyze their density within the content and suggest adjustments if necessary. Provide a suggested meta title and meta description.
                
                2. **Keyword Density Analysis**: Calculate the keyword density for the identified keywords and provide recommendations on whether the density should be increased or decreased. Indicate the current density percentage and provide an ideal target range.

                3. **Readability Score**: Evaluate the content’s readability using established readability scores (e.g., Flesch-Kincaid). Provide the current readability score and suggest specific improvements tailored to the content’s complexity. Recommendations should be aligned with the target audience’s reading level.

                4. **Readability Enhancements**: Based on the readability score and content analysis, suggest specific improvements for sentence structure, paragraph length, word choice, and overall clarity. Provide actionable steps to simplify complex sections or enhance the flow of the content.

                5. **Engagement Strategies**: Suggest strategies to enhance reader engagement, such as incorporating calls to action, internal linking, multimedia elements (e.g., images, videos), and interactive content. Provide specific examples of where these elements could be effectively integrated into the content.

                Use the following format for your response:
                
                *SEO Recommendations:*
                **SEO-friendly URL**: "Suggested URL"
                **Keywords**: ["keyword1", "keyword2", "keyword3"]
                **Keyword Density Analysis**: ["keyword1": "current_density%", "keyword2": "current_density%", ...] **Ideal Density Range**: "X% - Y%"
                **Meta Title**: "Suggested Meta Title"
                **Meta Description**: "Suggested Meta Description"
                **Readability Score**: "Score (e.g., Flesch-Kincaid 65)"
                **Readability Enhancements**: [Detailed suggestions for improving readability]
                **Engagement Strategies**: [Specific strategies for increasing reader engagement]

                Content to analyze: ' . $content],
            ],
            'max_tokens' => $max_tokens,
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

        if ( isset( $result['error'] ) ) {
            $error_message = $result['error']['message'];
            $error_code = $result['error']['code'];

            if ( $error_code === 'invalid_request_error' && strpos($error_message, 'The model') !== false ) {
                return 'The model ' . $ai_model . ' does not exist, or you do not have access to it.';
            }

            if ( $error_code === 'insufficient_quota' ) {
                return $error_message;
            }

            return 'OpenAI API Error: ' . $error_message;
        }

        update_option( 'aico_used_requests', $used_requests + 1 );

        return $result['choices'][0]['message']['content'] ?? 'No suggestions available.';
    }
}

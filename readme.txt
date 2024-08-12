=== AI Content Optimizer ===
Contributors: hanscode, marketingdoneright
Tags: Content analysis, Readability, SEO, AI, GPT
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Uses AI to analyze content and provide recommendations for improving SEO, readability, and engagement.

== Description ==

The AI Content Optimizer plugin is a powerful tool that leverages OpenAI's GPT models to analyze your WordPress content and provide detailed recommendations for improving SEO, readability, and user engagement. By integrating AI directly into the WordPress editor, this plugin offers actionable insights to help you optimize your content for better performance and visibility.

## Features

* **AI-Powered SEO Suggestions:** Get specific keyword recommendations, meta title, and meta description suggestions based on your content.
* **Readability Analysis:** Receive readability scores and tailored suggestions to enhance the clarity and flow of your content.
* **Engagement Strategies:** Discover strategies to increase user engagement, including calls to action, multimedia integration, and more.
* **Keyword Density Analysis:** Evaluate the density of your keywords and receive actionable advice on optimizing their usage.
* **Customizable AI Model:** Choose between GPT-3.5 Turbo, GPT-4 Turbo, and GPT-4o models for content analysis.
* **Rate Limit Management:** Set daily limits on API usage to control costs and prevent overuse.

## Why This Plugin?

In a digital landscape where content quality directly impacts SEO rankings and user engagement, the AI Content Optimizer plugin offers a significant advantage. By integrating advanced AI models directly into your WordPress workflow, this plugin empowers you to make data-driven improvements to your content, ensuring it resonates with your audience and performs well in search engines.

== Installation ==

* Download the AI Content Optimizer plugin.
* Upload the plugin files to the `/wp-content/plugins/ai-content-optimizer` directory, or install the plugin through the WordPress plugins screen directly.
* Activate the plugin through the 'Plugins' screen in WordPress.
* Navigate to Settings > AI Content Optimizer to configure the plugin.

= Getting an OpenAI API Key =

* Log into or create an [OpenAI account](https://platform.openai.com/).
* Go to the [API Keys page](https://platform.openai.com/api-keys).
* Click the *"Create new secret key"* button.
* Copy the secret key after it has been created.
* Go to Settings > *AI Content Optimizer* in the WordPress Admin Area.
* Paste the secret key in the *"OpenAI API Key"* field.

Congratulations! You should now be able to use the AI Content Optimizer Plugin when working in the WordPress editor for posts or pages.

= Settings =
* **OpenAI API Key:** Enter your OpenAI API key to enable AI-powered content analysis.
* **AI Model:** Choose between GPT-3.5 Turbo, GPT-4 Turbo, or GPT-4o based on your needs.
* **Max Tokens:** Set the maximum number of tokens the AI model can generate in a single response.
* **Rate Limit:** Configure daily limits on API requests to manage costs and avoid exceeding your quota.

= Usage =
* Open a post or page in the WordPress editor.
* Find the *"AI-Powered Content Analysis"* meta box on the editor screen.
* Click the *"Analyze Content with AI"* button.
* Review the AI-generated suggestions for keyword optimization, readability enhancements, and engagement strategies.
* Implement the recommended changes to optimize your content.

== Frequently Asked Questions ==

= What is the AI Content Optimizer plugin? =
The AI Content Optimizer plugin uses AI (powered by OpenAI) to analyze your content and provide recommendations for improving SEO, readability, and engagement. It offers suggestions on keyword optimization, meta tags, readability enhancements, and engagement strategies tailored to your content.

= How do I get an OpenAI API key? =
To use the AI Content Optimizer, you need an OpenAI API key. You can obtain this key by creating an account on the OpenAI platform and navigating to the API section to generate a new key. After generating your key, enter it in the plugin settings under "OpenAI API Key".

= What AI models are supported by the plugin? =
The plugin supports multiple AI models, including GPT-3.5 Turbo, GPT-4 Turbo, and GPT-4o. You can choose the model that best fits your needs from the plugin settings. GPT-3.5 Turbo is faster and suitable for most use cases, while GPT-4o models are more powerful and it's the newest and most advanced model.

= What are "tokens," and how do I set the maximum tokens in the plugin? =
Tokens are the basic units that the AI model uses to process and generate text. Setting a maximum token limit determines how much text the AI can generate in response to your content analysis. You can set this limit in the plugin settings, balancing between detailed suggestions and resource consumption.

= How does the rate limit work, and how can I manage it? =
The rate limit controls how many requests the plugin can make to the OpenAI API each day. If you exceed this limit, further requests will be rejected until the next day. You can adjust the rate limit in the plugin settings to fit your usage needs, and the current usage is displayed to help you monitor it.

= How can I avoid sending unnecessary API requests? =
The AI Content Optimizer plugin includes a button in the WordPress editor labeled `Analyze Content with AI.` The plugin only sends an API request when this button is clicked, ensuring that you only use your tokens and rate limits when needed.

= Can I customize the prompt sent to the AI for content analysis? =
Currently, the AI Content Optimizer plugin does not allow customization of the prompt. The plugin uses a predefined prompt designed to provide comprehensive SEO, readability, and engagement suggestions based on the content you provide. Future versions of the plugin may include this feature based on user feedback.

== Changelog ==

= 1.0.0 =
* Initial release with AI-powered content analysis, SEO recommendations, readability enhancements, and engagement strategies.



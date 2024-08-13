# AI-Powered Content Optimizer

### Authors: 
- [Hans Steffens](https://hanscode.io/)
- The folks behind [Marketing Done Right, LLC](https://marketingdr.co/)

## Description
The AI Content Optimizer plugin is a powerful tool that leverages OpenAI's GPT models to analyze your WordPress content and provide detailed recommendations for improving SEO, readability, and user engagement. By integrating AI directly into the WordPress editor, this plugin offers actionable insights to help you optimize your content for better performance and visibility.

## Features
- **AI-Powered SEO Suggestions:** Get specific keyword recommendations, meta title, and meta description suggestions based on your content.
- **Readability Analysis:** Receive readability scores and tailored suggestions to enhance the clarity and flow of your content.
- **Engagement Strategies:** Discover strategies to increase user engagement, including calls to action, multimedia integration, and more.
- **Keyword Density Analysis:** Evaluate the density of your keywords and receive actionable advice on optimizing their usage.
- **Customizable AI Model:** Choose between `GPT-3.5 Turbo`, `GPT-4 Turbo`, and `GPT-4o` models for content analysis.
- **Rate Limit Management:** Set daily limits on API usage to control costs and prevent overuse.

## Why This Plugin?
In a digital landscape where content quality directly impacts SEO rankings and user engagement, the AI Content Optimizer plugin offers a significant advantage. By integrating advanced AI models directly into your WordPress workflow, this plugin empowers you to make data-driven improvements to your content, ensuring it resonates with your audience and performs well in search engines.

## Installation
- Download the AI Content Optimizer plugin.
- Upload the plugin files to the `/wp-content/plugins/ai-content-optimizer` directory, or install the plugin through the WordPress plugins screen directly.
- Activate the plugin through the 'Plugins' screen in WordPress.
- Navigate to Settings > AI Content Optimizer to configure the plugin.

## Getting an OpenAI API Key
- Log into or create an [OpenAI account](https://platform.openai.com/).
- Go to the [API Keys page](https://platform.openai.com/api-keys).
- Click the __"Create new secret key"__ button.
- Copy the secret key after it has been created.
- Go to Settings > __AI Content Optimizer__ in the WordPress Admin Area.
- Paste the secret key in the __"OpenAI API Key"__ field.
  
Congratulations! You should now be able to use the AI Content Optimizer Plugin when working in the WordPress editor for posts or pages.

## Settings
- **OpenAI API Key:** Enter your OpenAI API key to enable AI-powered content analysis.
- **AI Model:** Choose between GPT-3.5 Turbo, GPT-4 Turbo, or GPT-4o based on your needs.
- **Max Tokens:** Set the maximum number of tokens the AI model can generate in a single response.
- **Rate Limit:** Configure daily limits on API requests to manage costs and avoid exceeding your quota.

![alt text](https://github.com/marketing-done-right/ai-content-optimizer/blob/main/public/images/screenshot-1.png "Settings")

## Usage
- Open a post or page in the WordPress editor.
- Find the __"AI-Powered Content Analysis"__ meta box on the editor screen.
- Click the __"Analyze Content with AI"__ button.
- Review the AI-generated suggestions for keyword optimization, readability enhancements, and engagement strategies.
- Implement the recommended changes to optimize your content.

![alt text](https://github.com/marketing-done-right/ai-content-optimizer/blob/main/public/images/screenshot-2.png "Meta Box")

![alt text](https://github.com/marketing-done-right/ai-content-optimizer/blob/main/public/images/screenshot-3.png "Analyzing")

![alt text](https://github.com/marketing-done-right/ai-content-optimizer/blob/main/public/images/screenshot-4.png "Results")

## Common Errors
> [!CAUTION]
> _The model gpt-4-turbo-preview does not exist, and you do not have access to it._

This error indicates that you currently don’t have access to the latest GPT-4 models. To access these models, please refer to this [OpenAI help article](https://help.openai.com/en/articles/7102672-how-can-i-access-gpt-4-gpt-4-turbo-and-gpt-4o). Additionally, these models require that you’ve spent $5 or more. Otherwise, you will be in the free tier, where only GPT-3.5 is available.

> [!CAUTION]
> _You exceeded your current quota, please check your plan and billing details. For more information on this error, read the docs: https://platform.openai.com/docs/guides/error-codes/api-errors_

This error indicates you have either run out of credits or have hit your monthly quota. For more information, read the [OpenAI API error documentation](https://platform.openai.com/docs/guides/error-codes/api-errors).

## Frequently Asked Questions

**Q: What is the AI Content Optimizer plugin?**

**A:** The AI Content Optimizer plugin uses AI (powered by OpenAI) to analyze your content and provide recommendations for improving SEO, readability, and engagement. It offers suggestions on keyword optimization, meta tags, readability enhancements, and engagement strategies tailored to your content.

**Q: How do I get an OpenAI API key?**

**A:** To use the AI Content Optimizer, you need an OpenAI API key. You can obtain this key by creating an account on the OpenAI platform and navigating to the API section to generate a new key. After generating your key, enter it in the plugin settings under "OpenAI API Key".

**Q: What AI models are supported by the plugin?**

**A:** The plugin supports multiple AI models, including `GPT-3.5 Turbo`, `GPT-4 Turbo`, and `GPT-4o`. You can choose the model that best fits your needs from the plugin settings. `GPT-3.5 Turbo` is faster and suitable for most use cases, while `GPT-4o` models are more powerful and it's the newest and most advanced model.

**Q: What are "tokens," and how do I set the maximum tokens in the plugin?**

**A:** Tokens are the basic units that the AI model uses to process and generate text. Setting a maximum token limit determines how much text the AI can generate in response to your content analysis. You can set this limit in the plugin settings, balancing between detailed suggestions and resource consumption.

**Q: How does the rate limit work, and how can I manage it**

**A:** The rate limit controls how many requests the plugin can make to the OpenAI API each day. If you exceed this limit, further requests will be rejected until the next day. You can adjust the rate limit in the plugin settings to fit your usage needs, and the current usage is displayed to help you monitor it.

**Q: How can I avoid sending unnecessary API requests?**

**A:** The AI Content Optimizer plugin includes a button in the WordPress editor labeled `Analyze Content with AI.` The plugin only sends an API request when this button is clicked, ensuring that you only use your tokens and rate limits when needed.

**Q: Can I customize the prompt sent to the AI for content analysis?**

**A:** Currently, the AI Content Optimizer plugin does not allow customization of the prompt. The plugin uses a predefined prompt designed to provide comprehensive SEO, readability, and engagement suggestions based on the content you provide. Future versions of the plugin may include this feature based on user feedback.

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are greatly appreciated.

1. Fork the Project
2. Create your Feature Branch (git checkout -b feature/AmazingFeature)
3. Commit your Changes (git commit -m 'Add some AmazingFeature')
4. Push to the Branch (git push origin feature/AmazingFeature)
5. Open a Pull Request

## Changelog
### Version 1.0.0:
- Initial release with AI-powered content analysis, SEO recommendations, readability enhancements, and engagement strategies.

## License
Distributed under the GPL v3 License. See [LICENSE](LICENSE) for more information.


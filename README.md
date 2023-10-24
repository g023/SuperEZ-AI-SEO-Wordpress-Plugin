# SuperEZ AI SEO Wordpress Plugin

A Wordpress plugin that utilizes the power of OpenAI GPT-3/GPT-4 API to generate SEO content for your blog or page posts. This Wordpress plugin serves as a personal AI assistant to help you with content ideas and creating content. It also allows you to add Gutenberg blocks to the editor after the assistant generates the content.

## Features

- Early alpha release, use at your own risk.
- Personal AI assistant to assist with content ideas and creation.
- Ability to add Gutenberg blocks to the editor.
- AI-powered revision and update of existing titles.
- Mainly running in JavaScript with PHP handling on page load to eliminate page reloads while using the plugin.
- Utilizes jQuery to access the API and provide functionality.
- Various fields for SEO generation, including meta title, meta description, meta keywords, meta categories, and meta tags.
- Supports the use of short tags in editable prompts, such as {PAGE_TITLE}.
- Easy to add more fields using a simple array, although their interactivity will need to be connected via jQuery.
- Temperature can be adjusted, with 0.0 being very conservative and 1.2 being very creative.
- Max tokens control the limit of tokens for each request.
- Click on the title of the AI assistant to minimize or maximize its window.

## Installation

1. Download the plugin and place it in the `wp-content/plugins/` folder.
   - Create a folder called `superez-ai-seo` inside the `plugins` folder.
   - Place the plugin files inside the `superez-ai-seo` folder.
   - Example path: `wp-content/plugins/superez-ai-seo/superez-ai-seo.php`
   - Write permissions are not required for any of the folders at the moment.
2. Activate the plugin.
   - Go to the WordPress plugins section and activate the plugin.
3. Access the plugin in a blog post or page.
   - You should see a section called "AI SEO and AI Content Generation".
   - If the section is collapsed, click on it to show the AI assistants.
4. Set your API key.
   - Get an API key from [https://openai.com/blog/openai-api](https://openai.com/blog/openai-api).
   - Add your API key to the API key field and click "Set API Key".
5. Start using the plugin.
   - Choose an assistant and start exploring its features.
   - You can start by entering a simple title on your page and then use the AI to generate a different title. Click "AI Revise Main Title" and then "Update Main Title" to update the main title with the revised title.
6. Enjoy!

## Requires
- OpenAI API Key
- Tested on clean install of Wordpress 6.3.2

[![screenshot](https://raw.githubusercontent.com/g023/SuperEZ-AI-SEO-Wordpress-Plugin/main/wp-superez-ai-seo-page.png)](#screenshot)


## Notes
v1.0.1a
- Code cleanup. Release .zip should install as a wordpress zipped plugin.


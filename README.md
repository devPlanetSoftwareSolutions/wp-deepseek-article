# DeepSeek Article Generator

A WordPress plugin that generates and publishes articles with subheadings and images using the DeepSeek API. Users can input prompts, and the plugin will automatically create and publish posts based on the generated content.

---

## Features

- **Article Generation**: Generates articles using the DeepSeek API.
- **Subheadings and Images**: Creates articles with structured subheadings (`<h2>`) and embedded images.
- **Prompt Input**: Allows users to input custom prompts for article generation.
- **Image Handling**: Downloads and attaches images from the API response to the WordPress media library.
- **Easy Setup**: Simple settings page for entering the DeepSeek API key.

---

## Installation

### 1. Download the Plugin
- Download the plugin files and place them in the `wp-content/plugins/deepseek-article-generator/` directory.

### 2. Activate the Plugin
- Go to **Plugins > Installed Plugins** in your WordPress admin dashboard.
- Find **DeepSeek Article Generator** and click **Activate**.

### 3. Set Up API Key
- Go to **DeepSeek Article Generator > Settings**.
- Enter your DeepSeek API key and save the settings.

---

## Usage

### 1. Generate Articles
- Go to **DeepSeek Article Generator > Generate Article**.
- Enter a prompt (e.g., "Write an article about renewable energy").
- Click **Generate Article**.

### 2. View Published Articles
- Once generated, the article will be published as a WordPress post.
- You can view the article on your website or edit it in the **Posts** section.

---

## Requirements

- WordPress 5.0 or higher.
- PHP 7.0 or higher.
- A valid DeepSeek API key.

---

## Frequently Asked Questions (FAQ)

### 1. Where do I get a DeepSeek API key?
You can obtain an API key by signing up on the [DeepSeek website](https://www.deepseek.com) (replace with the actual URL).

### 2. Can I customize the generated articles?
Yes, you can edit the generated articles in the **Posts** section of your WordPress admin dashboard.

### 3. What happens if the API request fails?
The plugin will display an error message. Ensure your API key is valid and the DeepSeek API is accessible.

### 4. Can I change the post category or author?
Yes, you can modify the `wp_insert_post` function in the plugin code to set your preferred category, author, or other post parameters.

---

## Changelog

### 1.0
- Initial release of the DeepSeek Article Generator plugin.

---

## License

This plugin is licensed under the GPLv2 or later. See the [LICENSE](LICENSE) file for details.

---

## Credits

- Developed by [Syed Naseer](https://github.com/devPlanetSoftwareSolutions) (devPlanet Software Solutions).
- Powered by the [DeepSeek API](https://www.deepseek.com).

<?php
/*
Plugin Name: DeepSeek Article Generator
Description: A plugin to generate and publish articles with subheadings and images using DeepSeek API.
Version: 1.0
Author: Syed Naseer Abbas
Author URI: https://github.com/devPlanetSoftwareSolutions
*/

function dseeg_add_settings_page() {
    add_menu_page(
        'DeepSeek Article Generator',
        'DeepSeek Article Generator',
        'manage_options',
        'dseeg-settings',
        'dseeg_render_settings_page',
        'dashicons-admin-post',
        100
    );
}
add_action('admin_menu', 'dseeg_add_settings_page');

function dseeg_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>DeepSeek Article Generator Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('dseeg_options_group');
            do_settings_sections('dseeg-settings');
            submit_button('Save Settings');
            ?>
        </form>

        <h2>Generate Article</h2>
        <form method="post" action="">
            <label for="dseeg_prompt">Enter your prompt:</label><br>
            <textarea name="dseeg_prompt" id="dseeg_prompt" rows="5" cols="50" placeholder="e.g., Write an article about AI in healthcare" required></textarea><br><br>
            <?php submit_button('Generate Article'); ?>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dseeg_prompt'])) {
            $prompt = sanitize_textarea_field($_POST['dseeg_prompt']);
            $post_id = dseeg_publish_article($prompt);

            if ($post_id) {
                echo '<p>Article published successfully! <a href="' . get_permalink($post_id) . '">View Article</a></p>';
            } else {
                echo '<p>Failed to generate article. Please check your API key and prompt.</p>';
            }
        }
        ?>
    </div>
    <?php
}

function dseeg_register_settings() {
    register_setting('dseeg_options_group', 'dseeg_api_key', 'dseeg_sanitize_api_key');
    add_settings_section('dseeg_main_section', 'Main Settings', null, 'dseeg-settings');
    add_settings_field('dseeg_api_key', 'DeepSeek API Key', 'dseeg_render_api_key_field', 'dseeg-settings', 'dseeg_main_section');
}
add_action('admin_init', 'dseeg_register_settings');

function dseeg_render_api_key_field() {
    $api_key = get_option('dseeg_api_key', '');
    echo '<input type="text" name="dseeg_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
}

function dseeg_sanitize_api_key($input) {
    return sanitize_text_field($input);
}

function dseeg_generate_article($prompt) {
    $api_key = get_option('dseeg_api_key');
    $api_url = 'https://api.deepseek.com/v1/generate'; // Replace with actual DeepSeek API endpoint

    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'prompt' => $prompt,
            'include_subheadings' => true,
            'include_images' => true,
        )),
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data;
}

function dseeg_download_image($image_url) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $tmp_file = download_url($image_url);

    if (is_wp_error($tmp_file)) {
        return false;
    }

    $file_array = array(
        'name' => basename($image_url),
        'tmp_name' => $tmp_file,
    );

    $attachment_id = media_handle_sideload($file_array, 0);

    if (is_wp_error($attachment_id)) {
        @unlink($tmp_file);
        return false;
    }

    return $attachment_id;
}

function dseeg_publish_article($prompt) {
    $article_data = dseeg_generate_article($prompt);

    if (!$article_data) {
        return false;
    }

    $content = '';

    foreach ($article_data['sections'] as $section) {
        $content .= '<h2>' . $section['heading'] . '</h2>';
        $content .= '<p>' . $section['content'] . '</p>';

        if (!empty($section['image_url'])) {
            $image_id = dseeg_download_image($section['image_url']);
            if ($image_id) {
                $content .= wp_get_attachment_image($image_id, 'full');
            }
        }
    }

    $post_id = wp_insert_post(array(
        'post_title'    => wp_trim_words($prompt, 5, '...'), // Use the first few words of the prompt as the title
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_author'  => 1,
        'post_category' => array(1),
    ));

    return $post_id;
}

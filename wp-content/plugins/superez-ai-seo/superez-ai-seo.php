<?php
/*
Plugin Name: SuperEZ AI SEO Wordpress Plugin
Description: A Wordpress OpenAI API GPT-3/GPT-4 SEO and Content Generator for Pages and Posts
Version: 1.0.1a
Author: <a href="https://github.com/g023" target="_blank" >https://github.com/g023</a>
License: 3-clause BSD license (https://opensource.org/licenses/BSD-3-Clause)
*/


/*
Features:
    Use the power of OpenAI GPT-3/GPT-4 API to generate content for your blog or page post in your wordpress site.
    - early alpha release, so use at your own risk.
    - personal ai assistant to help you with content ideas/creating content
    - ability to add gutenberg blocks to the editor after the assistant generates.
    - existing title revise/update using AI
    - mainly running in Javascript with PHP just handled on the page load.
    - jQuery used to access api and provide functionality.
    - various fields for SEO generation.
        - meta title, meta description, meta keywords, meta categories, meta tags...
    - can use short tags in editable prompts such as {PAGE_TITLE}
    - easy to add more fields using a simple array.
        - their interactivity will still need to be connected via jquery somewhere in here.
    - temperature can be adjusted. (0.0 = very conservative, 1.2 = very creative)
    - max tokens controls how many tokens you want to limit the request to
    - click on title of ai assistant to minimize/maximize its window


INSTALL:
    1) install plugin to your wp-content\plugins\ folder
        - inside the plugins folder, you would make a folder called superez-ai-seo and then place the files in there.
          eg) wp-content\plugins\superez-ai-seo\superez-ai-seo.php
        - write permissions not required on any of the folders at the moment
    2) activate plugin
        - activate plugin in wordpress plugins section
    3) go into a blog post or a page and you should see it as a section called 'AI SEO and AI Content Generation'
        - if the section is collapsed, you may need to click it to show the AI assistants.
    4) add your api key to the api key field and click 'set api key'
        - you can get an api key from https://openai.com/blog/openai-api
    5) once key is loaded you simply pick an assistant and start playing around.
        - you might want to throw a simple title in on the page title, and then get ai to generate a different title first.
          (AI Revise Main Title) and then click (Update Main Title) to update the main title with the revised title.
    6) enjoy :)

TODO:
    - maybe add a key storage at some point. Right now you have to enter it whenever you enter the page
      and the key is just stored in memory, which is released when you leave the page.
*/

// This is a wordpress plugin for the admin section that helps build content using AI

// add a input and button to top bar
function my_seo_plugin_admin_bar() {
    global $wp_admin_bar;

    $wp_admin_bar->add_menu(array(
        'id' => 'my-seo-plugin',
        'title' => 'SuperEZ AI SEO',
        'href' => '#',
        'meta' => array(
            'title' => __('SuperEZ AI SEO'),
        ),
    ));

    $wp_admin_bar->add_menu(array(
        'id' => 'my-seo-plugin-settings',
        'parent' => 'my-seo-plugin',
        'title' => 'Settings',
        'href' => admin_url('options-general.php?page=my-seo-plugin-settings'),
        'meta' => array(
            'title' => __('Settings'),
        ),
    ));
/*
    $wp_admin_bar->add_menu(array(
        'id' => 'my-seo-plugin-help',
        'parent' => 'my-seo-plugin',
        'title' => 'Help',
        'href' => admin_url('options-general.php?page=my-seo-plugin-help'),
        'meta' => array(
            'title' => __('Help'),
        ),
    ));
*/
}

add_action('admin_bar_menu', 'my_seo_plugin_admin_bar', 100);

// add a settings page
function my_seo_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2>SuperEZ AI SEO Settings</h2>
        <p>Here are the settings for the SuperEZ AI SEO plugin.</p>

        <!-- add a text input and a button to the settings page that a user can store an api key -->

        <?php
        /*
        <input type="text" id="my-seo-api-key" name="my-seo-api-key" value="<?php echo esc_attr(get_option('my_seo_api_key')); ?>" size="50">
        <button id="my-seo-api-key-button" class="button">Save API Key</button>
        */
        ?>


    </div>
    <?php
}

// add a help page
/*
function my_seo_plugin_help_page() {
    ?>
    <div class="wrap">
        <h2>SuperEZ AI SEO Help</h2>
        <p>Here is some help for the SuperEZ AI SEO plugin.</p>
    </div>
    <?php
}
*/

// add the settings and help pages to the admin menu
function my_seo_plugin_menu() {
    add_options_page(
        'SuperEZ AI SEO Settings',
        'SuperEZ AI SEO',
        'manage_options',
        'my-seo-plugin-settings',
        'my_seo_plugin_settings_page'
    );
/*
    add_options_page(
        'SuperEZ AI SEO Help',
        'SuperEZ AI SEO Help',
        'manage_options',
        'my-seo-plugin-help',
        'my_seo_plugin_help_page'
    );
*/
}

add_action('admin_menu', 'my_seo_plugin_menu');

// Add meta boxes to the post and page editors
function my_seo_plugin_meta_box() {
    add_meta_box(
        'my-seo-meta-box',
        'AI SEO and AI Content Generation',
        'my_seo_meta_box_callback',
        array('post', 'page'), // Add support for both posts and pages
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'my_seo_plugin_meta_box');

// start with meta title
// add a meta title field to the meta box

// move g_fields to a separate file in ./inc.php/sections.php folder in plugin directory
// include it here
include_once plugin_dir_path(__FILE__) . '_inc.php/sections.php';


// $g_fields[] = array(
//     'id' => 'ez-base-title',
//     'label' => 'Revise Base Title',
//     'description' => 'The base title for the page.',
//     'prompt' => 'Just show the title. You will take the title [{POST_TITLE}] and generate a better title.',
//     'template-admin' => '<input type="text" name="ez-base-title" id="ez-base-title" class="output" value="{CONTENT}" size="70">',
//     'sanitize-type' => 'sanitize_text_field',
//     'escape-type' => 'esc_attr',
//     'button' => 'AI Revise Main Title',
//     'button-id' => 'my-seo-fetch-revise-title',
//     'ai-max-tokens' => 60,
//     'ai-temperature' => 0.7,
//     'ai-model' =>  'gpt-3.5-turbo-16k', // 'gpt-3.5-turbo-16k', 'gpt-3.5-turbo-instruct', 'gpt-4', 'gpt-4-32k'
//     'more-buttons' => [[ 'id'=>'update-main-title', 'label'=>'Update Main Title' ]],
// );


function gpt_dropdown_html($select='')
{
    $html = "<select class='the-model'>";
    $html .= "<option value='gpt-3.5-turbo-16k' " . ($select == 'gpt-3.5-turbo-16k' ? 'selected' : '') . ">gpt-3.5-turbo-16k</option>";
    $html .= "<option value='gpt-3.5-turbo-instruct' " . ($select == 'gpt-3.5-turbo-instruct' ? 'selected' : '') . ">gpt-3.5-turbo-instruct</option>";
    $html .= "<option value='gpt-4' " . ($select == 'gpt-4' ? 'selected' : '') . ">gpt-4</option>";
    $html .= "<option value='gpt-4-32k' " . ($select == 'gpt-4-32k' ? 'selected' : '') . ">gpt-4-32k</option>";
    $html .= "</select>";
    return $html;
}

// TODO: MOVE TO TEMPLATE WITH TEMPLATE TAGS:
//  {{{PLUGIN_DIR}}}
// Callback function to render the meta box
function my_seo_meta_box_callback($post) {
    global $g_fields;
    // Get the current meta values
    $meta_description   = get_post_meta($post->ID, '_meta_description', true);
    $site_id            = get_current_blog_id();

    $plugin_dir         = plugin_dir_url(__FILE__);

    $tags["{{{PLUGIN_DIR}}}"] = $plugin_dir; // 

    // Output the HTML for the meta box
    $template = file_get_contents($plugin_dir.'_inc.htm/tpl_edit.htm');
    // replace tags
    foreach ($tags as $tag => $value) {
        $template = str_replace($tag, $value, $template);
    }
    echo $template;

    // -----> continue
    
        // now add the fields
        foreach ($g_fields as $field) {
            echo "<div class='gpt-field' fld='".$field['id']."'>";
            echo '<label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label><br>';

            // show prompt as hidden field (probably redundant)
            // echo '<input type="hidden" class="prompt" value="' . esc_attr($field['prompt']) . '" size="70">'; 
            /*
    'ai-max-tokens' => 60,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
            */
            echo '<input type="hidden" class="ai-max-tokens" value="' . esc_attr($field['ai-max-tokens']) . '" size="70">';
            echo '<input type="hidden" class="ai-temperature" value="' . esc_attr($field['ai-temperature']) . '" size="70">';
            echo '<input type="hidden" class="ai-model" value="' . esc_attr($field['ai-model']) . '" size="70">';


            $escape_type    = $field['escape-type'];
            $sanitize_type  = $field['sanitize-type'];
            $template       = $field['template-admin'];
            $gpt_selected   = $field['ai-model'];

            $content = $escape_type($sanitize_type(get_post_meta($post->ID, $field['id'], true)));
            $template = str_replace('{CONTENT}', $content, $template);


            $template .= "<div class='config-container row'>";
            // BEGIN :: slider for temperature with number input as well
            $template .= "<div class='col'>";
            // preprompt
            $template .= "<textarea class='the-preprompt' rows=5 >" . esc_attr($field['prompt']) . "</textarea><br>";

            $template .= '<span>temp:&nbsp;</span>';
            $template .= '<input class="the-temp" type="range" min="0.0" max="1.2" step="0.1" value="' . esc_attr($field['ai-temperature']) . '">';
            $template .= '<input type="number" min="0.0" max="1.2" step="0.1" value="' . esc_attr($field['ai-temperature']) . '">';
            $template .= "&nbsp;&nbsp;";
            $template .= '</div><!-- end .col -->';
            // END :: slider

            // max tokens
            $template .= "<div class='col'>";
            $template .= '<span>max tokens:&nbsp;</span>';
            $template .= '<input class="the-maxtokens" type="number" min="1" max="16000" step="1" value="' . esc_attr($field['ai-max-tokens']) . '">';
            $template .= '</div><!-- end .col -->';

            // dropdown for model (select default)
            $template .= "<br><br><div class='row'>";
            $template .= gpt_dropdown_html($gpt_selected);
            $template .= "</div><!-- end .row -->";

            $template .= "</div><!-- end .config-container -->";



            // show hide configurations
            $template .= "<br><button class='button show-hide-config'>show/hide config</button>";

            $button_id = $field['button-id'];
            $button = $field['button'];
            $template .= '<button class="button ' . $button_id . '" fld="'. $field['id'] .'" >' . $button . '</button>';



            // check for more buttons and add
            if (isset($field['more-buttons'])) 
                foreach ($field['more-buttons'] as $more_button) 
                    $template .= '<button class="button ' . $more_button['id'] . '" fld="'. $field['id'] .'" >' . $more_button['label'] . '</button><br>';

            // $template .= "<br><br>";

            echo $template;
            echo "</div><hr>";

            //echo '<br>';
        }
        ?>


    </div> <!-- end .ezseo -->


    <?php
}


// Save the meta data when the post or page is saved
function my_seo_plugin_save_meta($post_id) {
    global $g_fields;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    foreach ($g_fields as $field) 
        if (isset($_POST[$field['id']])) 
            update_post_meta($post_id, $field['id'], call_user_func($field['sanitize-type'], $_POST[$field['id']]));
}

add_action('save_post', 'my_seo_plugin_save_meta');

// Enqueue the JavaScript file
function my_seo_plugin_enqueue_scripts() {
    wp_enqueue_script('my-seo-script', 
        plugin_dir_url(__FILE__) . '_inc.js/superez-gpt-builder.gptapi.js', 
        plugin_dir_url(__FILE__) . '_inc.js/script.js', 
        array('jquery'), 
        '1.0', 
        true);
}

// add_action('admin_enqueue_scripts', 'my_seo_plugin_enqueue_scripts');

// Add the meta title and description to the head section of the page
function my_seo_plugin_head() {
    global $post;

    if (is_singular()) { // if is a single post or page
/*
        $meta_title         = get_post_meta($post->ID, '_meta_title', true);
        if (!empty($meta_title)) {
            echo '<title>' . esc_html($meta_title) . '</title>';
        }
*/

        $meta_description   = get_post_meta($post->ID, 'ez-meta-desc', true);
        if (!empty($meta_description)) 
            echo '<meta name="description" content="' . esc_attr($meta_description) . '">';
        

        $meta_keywords      = get_post_meta($post->ID, 'ez-meta-keywords', true);
        if (!empty($meta_keywords)) 
            echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '">';
        

        $meta_categories    = get_post_meta($post->ID, 'ez-meta-categories', true);
        if (!empty($meta_categories)) 
            echo '<meta name="categories" content="' . esc_attr($meta_categories) . '">';
        

        $meta_tags          = get_post_meta($post->ID, 'ez-meta-tags', true);
        if (!empty($meta_tags)) 
            echo '<meta name="tags" content="' . esc_attr($meta_tags) . '">';
        
        // handle social twitter/opengraph/facebook/whatever
        $social_title = get_post_meta($post->ID, 'ez-meta-title', true);
        $social_desc = get_post_meta($post->ID, 'ez-meta-desc', true);

        if (!empty($social_title)) 
            echo '<meta property="og:title" content="' . esc_attr($social_title) . '">';
        else
            echo '<meta property="og:title" content="' . esc_attr($post->post_title) . '">';

        if (!empty($social_desc))
            echo '<meta property="og:description" content="' . esc_attr($social_desc) . '">';
        else
            echo '<meta property="og:description" content="' . esc_attr($post->post_excerpt) . '">';
        
        // handle twitter
        if (!empty($social_title)) 
            echo '<meta name="twitter:title" content="' . esc_attr($social_title) . '">';
        else
            echo '<meta name="twitter:title" content="' . esc_attr($post->post_title) . '">';

        if (!empty($social_desc))
            echo '<meta name="twitter:description" content="' . esc_attr($social_desc) . '">';
        else
            echo '<meta name="twitter:description" content="' . esc_attr($post->post_excerpt) . '">';

/*
        // handle image (use wp. to get image)
        
        if (!empty($social_image)) {
            echo '<meta property="og:image" content="' . esc_attr($social_image) . '">';
            echo '<meta name="twitter:image" content="' . esc_attr($social_image) . '">';
        }

        // handle url
        $social_url = wp.get_permalink($post->ID);
        if (!empty($social_url)) {
            echo '<meta property="og:url" content="' . esc_attr($social_url) . '">';
            echo '<meta name="twitter:url" content="' . esc_attr($social_url) . '">';
        }

        // handle site name
        
        // $social_site_name = get_post_meta($post->ID, 'ez-meta-site-name', true);
        $social_site_name = get_bloginfo('name');
        if (!empty($social_site_name)) {
            echo '<meta property="og:site_name" content="' . esc_attr($social_site_name) . '">';
        }

        // handle type
        $social_type = wp.get_post_type($post->ID);
        if (!empty($social_type)) {
            echo '<meta property="og:type" content="' . esc_attr($social_type) . '">';
        }

    */
        

    }
}

add_action('wp_head', 'my_seo_plugin_head');




// add stylesheet from plugin 
// function my_seo_plugin_styles() {
//     wp_enqueue_style('my-seo-styles', plugin_dir_url(__FILE__) . '_inc.css/styles.css');
// }




// 


<?php
/*
Plugin Name: SuperEZ AI SEO Wordpress Plugin
Description: A Wordpress OpenAI API GPT-3/GPT-4 SEO and Content Generator for Pages and Posts
Version: 1.0a
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


// Callback function to render the meta box
function my_seo_meta_box_callback($post) {
    global $g_fields;
    // Get the current meta values
    $meta_description   = get_post_meta($post->ID, '_meta_description', true);
    $site_id            = get_current_blog_id();

    // Output the HTML for the meta box
    ?>
        <!-- include from plugin dir -->
        <script src="<?php echo plugin_dir_url(__FILE__); ?>_inc.js/superez-gpt-builder.gptapi.js"></script>
        <!-- include style from plugin dir -->
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>_inc.css/styles.css" type="text/css" media="all" />

    <div class='ezseo'>
        <div class='d-footer' site-id='<?php echo $site_id; ?>'>
            <div class='d-footer-container'>
                <div class='row'>
                    <label for="the-aikey">OpenAI API Key:</label>
                </div>
                <div class='row'>
                    <div class='col row-fld'><input id="the-aikey" type="password" placeholder="Enter your OpenAI API key here."></div>
                    <br>
                    <div class='col row-btn'><button id="the-aikey-btn" class='btn button'>set api key</button></div>
                </div>
            </div>
        </div>

        <br>

        <script>
            // BEGIN :: UPDATE API KEY //
                // when we click the the-aikey-btn we set the api key for current window. (at moment just storing in a global variable)
                // begin.
                g_apiKey = '';
                g_GPT = new ChatGPTClient(g_apiKey);


                // when we click the the-aikey-btn we set the api key for current window. (at moment just storing in a global variable)
                jQuery(document).ready(function($) {
                    $('#the-aikey-btn').click(function() {
                        var key = $('#the-aikey').val();
                        g_apiKey = key;
                        g_GPT = new ChatGPTClient(g_apiKey);
                        alert('API key set.');
                        // blank field
                        $('#the-aikey').val('');
                    });
                });
            // END :: UPDATE API KEY //

            // BEGIN :: HANDLE UPDATE TITLE FROM REVISED //
                // when we click the update-main-title button we update the main title with the revised title
                jQuery(document).ready(function($) {
                    $('.update-main-title').click(function() {
                        var revised_title = $('#ez-base-title').val();
                        /*
                        // Get the current post title.
const currentTitle = wp.data.select('core/editor').getCurrentPost().title;

// Define the new title.
const newTitle = 'New Page Title';

// Update the post title using the `wp.data.dispatch` method.
wp.data.dispatch('core/editor').editPost({ title: newTitle });

// You can also console log the old and new titles for verification.
console.log('Old Title:', currentTitle);
console.log('New Title:', newTitle);
                        */
                        // update the title
                        wp.data.dispatch('core/editor').editPost({ title: revised_title });


                    });
                });
            // END :: HANDLE UPDATE TITLE FROM REVISED //


</script>


<!-- BEGIN: -->

        <div class='d-ai-assistant'>
            <div class='d-assistant-container'>
                <div class='row title'>
                    <label>: : Your AI Assistant : :</label>
                </div>
                <div class='row ai-assistant'>
                    <br>
                    <div class='col row-fld'><input id="the-prompt" type="text" placeholder="Make your request here (use {PAGE_TITLE} if you want):"></div>
                    <br><br>
                    <div class='col row-btn'><button id="the-ai-assistant-btn" class='btn button'>Get Answer</button></div>
                    <br><br>
                    <!-- temperature slider between 0.0 and 1.2 -->
                    <div class='col row-fld'><input id="the-ai-assistant-temperature" type="range" min="0.0" max="1.2" step="0.1" value="0.7"></div>
                    <!-- make slider display value -->
                    <div class='col row-fld'><input id="the-ai-assistant-temperature-2" type="number" min="0.0" max="1.2" step="0.1" value="0.7"></div>

                    <script>
                        // when slider changes, update the number
                        jQuery(document).ready(function($) {
                            $('#the-ai-assistant-temperature').on('input', function() {
                                $('#the-ai-assistant-temperature-2').val($(this).val());
                            });
                        });
                        // when number changes, update the slider
                        jQuery(document).ready(function($) {
                            $('#the-ai-assistant-temperature-2').on('input', function() {
                                $('#the-ai-assistant-temperature').val($(this).val());
                            });
                        });
                    </script>

                    <br><br>
                    <!-- max token input -->
                    <span>max tokens:</span>
                    <div class='col row-fld'><input id="the-ai-assistant-max-tokens" type="number" min="1" max="16000" step="1" value="500"></div>
                    <br><br>
                    <div>output:</div>
                    <textarea id='assistant-output' rows=10 ></textarea>
                    <br><br>
                    <div class='col row-btn'><button id="the-ai-assistant-add-block" class='btn button'>Add Block</button></div>
                </div>
            </div>
        </div>
        <br><br>

        <script>
            // find first {ai} tag in document and return the result after processing with the prompt
            // return error if prompt empty
            // if {PAGE_TITLE} in prompt, use the page title in place of that tag 
            jQuery(document).ready(function($) {

                // when + block clicked, take assistant output and insert it as a block in wp editor
// BEGIN :: HOW TO INSERT A BLOCK INTO THE EDITOR
/*
// create a block
var block = wp.blocks.createBlock('core/paragraph', {
    content: 'Hello World!',
});
// add the block to the editor
wp.data.dispatch('core/editor').insertBlocks(block);
*/
// END :: HOW TO INSERT A BLOCK INTO THE EDITOR
                jQuery('#the-ai-assistant-add-block').on('click', function() {
                    // get the output
                    var output = $('#assistant-output').val();
                    // if it is empty, error
                    if (output == '') {
                        alert('No content to add.');
                        return;
                    }
                    
                    // create a block
                    var block = wp.blocks.createBlock('core/paragraph', {
                        content: output,
                    });

                    // add the block to the editor
                    // wp.data.dispatch('core/editor').insertBlocks(block); // deprecated
                    // new: wp.data.dispatch( 'core/block-editor' ).insertBlocks`
                    wp.data.dispatch( 'core/block-editor' ).insertBlocks( block );
                });

                // when title clicked, slidetoggle
                jQuery('.d-ai-assistant .title').on('click', function() {
                    jQuery('.d-ai-assistant .ai-assistant').slideToggle(10);
                });

                // when assistant button clicked
                jQuery('#the-ai-assistant-btn').click(function() {
                    // createBlock from wordpress

                    var prompt = $('#the-prompt').val();
                    if (prompt == '') {
                        alert('Please enter a prompt.');
                        return;
                    }
                    // get the page title
                    var title = $('h1.editor-post-title').html();
                    // replace {PAGE_TITLE} with title
                    prompt = prompt.replace('{PAGE_TITLE}', title);
                    // get the content
                    var content = $('.editor-post-text-editor').val();
                    // replace {PAGE_CONTENT} with content
                    prompt = prompt.replace('{PAGE_CONTENT}', content);
                    // get the model
                    var model = 'gpt-3.5-turbo-16k';
                    // get the temperature
                    // var temperature = 0.7;
                    var temperature = $('#the-ai-assistant-temperature').val();
                    console.log('temperature',temperature);
                    // get the max tokens
                    // var max_tokens = 60;
                    var max_tokens = $('#the-ai-assistant-max-tokens').val();
                    console.log('max_tokens',max_tokens);
                    // make temperate a float
                    temperature = parseFloat(temperature);
                    // make max tokens an int
                    max_tokens = parseInt(max_tokens);
                    // set the prompt up
                    messages    = [{ role: 'user', content: prompt }];
                    // send the request, and await a response
                    get_response(model,messages,temperature,max_tokens, '.d-ai-assistant #assistant-output');

                });
            });


        </script>
<!-- END: -->


<script>


            // BEGIN :: 
            // create a function based off the code in .my-seo-fetch-revise-title
            async function update_output(parent_class)
            {
                    // theButton is $(this)
                    // title = jQuery('h1.editor-post-title').html();
                    // use wp. javascript functions to get title
                    const title = wp.data.select('core/editor').getCurrentPost().title;
                    content = jQuery('.editor-post-text-editor').val(); // need a better option
                    // get the parent div
                    var parent_div = jQuery(parent_class);
                    // get the target to send the output to
                    var update_class = parent_class + ' .output';

                    console.log('parent_class',parent_class);
                    console.log('update_class',update_class);

                    console.log('current_output',jQuery(update_class).val());


                    console.log('t',title);
                    // get the prompt // parent gpt-field // child prompt
                    // var prompt = parent_div.find('.prompt').val();
                    var prompt = parent_div.find('.the-preprompt').val();

                    console.log('prompt',prompt);

                    // replace {POST_TITLE} with title
                    prompt = prompt.replace('{POST_TITLE}', title);
                    // replace {POST_CONTENT} with content
                    prompt = prompt.replace('{POST_CONTENT}', content);

                    console.log('prompt',prompt);
                    // get the model
                    // var model = parent_div.find('.ai-model').val();
                    var model = parent_div.find('.the-model').val();
                    console.log('model',model);
                    // get the temperature
                    // var temperature = $('.gpt-field .ai-temperature').val();
                    // var temperature = parent_div.find('.ai-temperature').val();
                    var temperature = parent_div.find('.the-temp').val();
                    console.log('temperature',temperature);
                    // get the max tokens
                    // var max_tokens = $('.gpt-field .ai-max-tokens').val();
                    // var max_tokens = parent_div.find('.ai-max-tokens').val();
                    var max_tokens = parent_div.find('.the-maxtokens').val();
                    console.log('max_tokens',max_tokens);
                    // make temperate a float
                    temperature = parseFloat(temperature);
                    // make max tokens an int
                    max_tokens = parseInt(max_tokens);
                    // set the prompt up
                    messages    = [{ role: 'user', content: prompt }];


                    // send the request, and await a response
                    await get_response(model,messages,temperature,max_tokens, update_class);
            }

            // when we click my-seo-fetch-revise-title
            jQuery(document).ready(function($) {


                // send fld attribute on button forward
                $('.my-seo-fetch-revise-title').on('click', async function( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });
                
                $('.my-seo-fetch-title').on('click', async  function( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });

                $('.my-seo-fetch-ai-description').on('click', async  function ( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });

                $('.my-seo-fetch-ai-keywords').on('click', async  function ( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });

                $('.my-seo-fetch-ai-categories').on('click', async  function ( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });

                $('.my-seo-fetch-ai-tags').on('click', async  function ( theButton ) {
                    var parent_class = '.gpt-field[fld='+$(this).attr('fld')+']';
                    await update_output(parent_class);
                });

            });

            // END ::


        </script>
<!-- 
        <label for="meta-title">Meta Title:</label><br>
        <input type="text" name="meta-title" id="meta-title" value="<?php echo esc_attr($meta_title); ?>" size="70"><br>

        <label for="meta-description">Meta Description:</label><br>
        <textarea name="meta-description" id="meta-description" rows="3" cols="70"><?php echo esc_textarea($meta_description); ?></textarea> 
-->
        <?php
        echo "<script>
            // handle sliders for gpt-fields
            jQuery(document).ready(function($) {
                $('.gpt-field input[type=range]').on('input', function() {
                    $(this).next().val($(this).val());
                });
                $('.gpt-field input[type=number]').on('input', function() {
                    $(this).prev().val($(this).val());
                });
            });

            // default hide config
            jQuery(document).ready(function($) {
                $('.gpt-field .config-container').hide();
            });
            // when click show config
            jQuery(document).ready(function($) {
                $('.gpt-field .show-hide-config').on('click', function() {
                    $(this).parent().find('.config-container').slideToggle(10);
                });
            });
        </script>
        ";
        foreach ($g_fields as $field) {
            echo "<div class='gpt-field' fld='".$field['id']."'>";
            echo '<label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label><br>';

            // show prompt as hidden field
            echo '<input type="hidden" class="prompt" value="' . esc_attr($field['prompt']) . '" size="70">';
            /*
    'ai-max-tokens' => 60,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
            */
            echo '<input type="hidden" class="ai-max-tokens" value="' . esc_attr($field['ai-max-tokens']) . '" size="70">';
            echo '<input type="hidden" class="ai-temperature" value="' . esc_attr($field['ai-temperature']) . '" size="70">';
            echo '<input type="hidden" class="ai-model" value="' . esc_attr($field['ai-model']) . '" size="70">';


            $escape_type = $field['escape-type'];
            $sanitize_type = $field['sanitize-type'];
            $template = $field['template-admin'];
            $content = $escape_type($sanitize_type(get_post_meta($post->ID, $field['id'], true)));
            $template = str_replace('{CONTENT}', $content, $template);


            $template .= "<style>
            .config-container {
            }
            .config-container textarea {
                width: 100%;
            }
            </style>
            ";
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
            $selected = $field['ai-model'];
            $template .= "<br><br><div class='row'>";
            $template .= "<select class='the-model'>";
            $template .= "<option value='gpt-3.5-turbo-16k' " . ($selected == 'gpt-3.5-turbo-16k' ? 'selected' : '') . ">gpt-3.5-turbo-16k</option>";
            $template .= "<option value='gpt-3.5-turbo-instruct' " . ($selected == 'gpt-3.5-turbo-instruct' ? 'selected' : '') . ">gpt-3.5-turbo-instruct</option>";
            $template .= "<option value='gpt-4' " . ($selected == 'gpt-4' ? 'selected' : '') . ">gpt-4</option>";
            $template .= "<option value='gpt-4-32k' " . ($selected == 'gpt-4-32k' ? 'selected' : '') . ">gpt-4-32k</option>";
            $template .= "</select>";
            $template .= "</div><!-- end .row -->";

            $template .= "</div><!-- end .config-container -->";




            $button_id = $field['button-id'];
            $button = $field['button'];
            $template .= '<br><button class="button ' . $button_id . '" fld="'. $field['id'] .'" >' . $button . '</button>';

            // show hide configurations
            $template .= "<button class='button show-hide-config'>show/hide config</button>";


            // check for more buttons and add
            if (isset($field['more-buttons'])) {
                foreach ($field['more-buttons'] as $more_button) {
                    $template .= '<button class="button ' . $more_button['id'] . '" fld="'. $field['id'] .'" >' . $more_button['label'] . '</button><br>';
                }
            }

            // $template .= "<br><br>";

            echo $template;
            echo "</div><hr>";

            //echo '<br>';
        }
        ?>

        <!-- have a button to fetch the title and the content from those sections. Use jquery to read values and then console.log -->
        <br>
        <!-- <button id="my-seo-fetch-title" class="button">Fetch Title</button>
        <button id="my-seo-fetch-content" class="button">Fetch Content</button>
        <button id='my-seo-fetch-ai-description' class='button'>Fetch AI Description</button> -->

    </div> <!-- end .ezseo -->

    <script>
        jQuery(document).ready(function($) {

            $('#my-seo-fetch-title').click(function() {
                var title = $('h1.editor-post-title').html();
                console.log('t',title);
            });

            $('#my-seo-fetch-content').click(function() {
                var content = $('.editor-post-text-editor').val();
                console.log('c',content);
            });
            
        });
    </script>

    

    <?php
}


// Save the meta data when the post or page is saved
function my_seo_plugin_save_meta($post_id) {
    global $g_fields;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    foreach ($g_fields as $field) {
        if (isset($_POST[$field['id']])) {
            update_post_meta($post_id, $field['id'], call_user_func($field['sanitize-type'], $_POST[$field['id']]));
        }        
    }
}

add_action('save_post', 'my_seo_plugin_save_meta');

// Enqueue the JavaScript file
function my_seo_plugin_enqueue_scripts() {
    //
    wp_enqueue_script('my-seo-script', 
        plugin_dir_url(__FILE__) . '_inc.js/superez-gpt-builder.gptapi.js', 
        plugin_dir_url(__FILE__) . '_inc.js/script.js', array('jquery'), 
        '1.0', //
        true);
}

// add_action('admin_enqueue_scripts', 'my_seo_plugin_enqueue_scripts');

// Add the meta title and description to the head section of the page
function my_seo_plugin_head() {
    global $post;

    if (is_singular()) {
/*
        $meta_title         = get_post_meta($post->ID, '_meta_title', true);
        if (!empty($meta_title)) {
            echo '<title>' . esc_html($meta_title) . '</title>';
        }
*/

        $meta_description   = get_post_meta($post->ID, 'ez-meta-desc', true);
        if (!empty($meta_description)) {
            echo '<meta name="description" content="' . esc_attr($meta_description) . '">';
        }

        $meta_keywords      = get_post_meta($post->ID, 'ez-meta-keywords', true);
        if (!empty($meta_keywords)) {
            echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '">';
        }

        $meta_categories    = get_post_meta($post->ID, 'ez-meta-categories', true);
        if (!empty($meta_categories)) {
            echo '<meta name="categories" content="' . esc_attr($meta_categories) . '">';
        }

        $meta_tags          = get_post_meta($post->ID, 'ez-meta-tags', true);
        if (!empty($meta_tags)) {
            echo '<meta name="tags" content="' . esc_attr($meta_tags) . '">';
        }
        

    }
}

add_action('wp_head', 'my_seo_plugin_head');




// add stylesheet from plugin 
// function my_seo_plugin_styles() {
//     wp_enqueue_style('my-seo-styles', plugin_dir_url(__FILE__) . '_inc.css/styles.css');
// }




// 


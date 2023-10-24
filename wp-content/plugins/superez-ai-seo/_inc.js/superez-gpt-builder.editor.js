            // BEGIN :: UPDATE API KEY //
                // when we click the the-aikey-btn we set the api key for current window. (at moment just storing in a global variable)
                // begin.
                g_apiKey = '';
                g_GPT = new ChatGPTClient(g_apiKey);


                // when we click the the-aikey-btn we set the api key for current window. (at moment just storing in a global variable)
                jQuery(document).ready(function($) {
                    jQuery('#the-aikey-btn').click(function() {
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
                // when we click the update-main-title button we update the main title with the revised title
                jQuery(document).ready(function($) {
                    jQuery('.update-main-title').click(function() {
                        var revised_title = $('#ez-base-title').val();

                        // update the title
                        wp.data.dispatch('core/editor').editPost({ title: revised_title });
                    });
                });
            // END :: HANDLE UPDATE TITLE FROM REVISED //


// -- end: 1


// begin: 2

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
// -- end: 2




// begin: 3

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

            // make an array of updateables
            var updateables = [
                '.my-seo-fetch-revise-title',
                '.my-seo-fetch-title',
                '.my-seo-fetch-ai-description',
                '.my-seo-fetch-ai-keywords',
                '.my-seo-fetch-ai-categories',
                '.my-seo-fetch-ai-tags',
            ];

            // attach click handlers to each updateable
            jQuery(document).ready(function($) {
                updateables.forEach(function(updateable) {
                    jQuery(updateable).on('click', async function( theButton ) {
                        var parent_class = '.gpt-field[fld='+jQuery(this).attr('fld')+']';
                        await update_output(parent_class);
                    });
                });
            });

            // when we click my-seo-fetch-revise-title

            // END ::


// -- end: 3


// begin: 4

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

// -- end: 4
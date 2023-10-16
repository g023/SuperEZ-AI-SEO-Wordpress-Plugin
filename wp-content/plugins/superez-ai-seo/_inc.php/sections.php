<?php

$g_fields[] = array(
    'id' => 'ez-base-title',
    'label' => 'Revise Base Title',
    'description' => 'The base title for the page.',
    'prompt' => 'Just show the title. You will take the title [{POST_TITLE}] and generate a better title. If the title looks like it contains things that do not seem in place, remove those elements.',
    'template-admin' => '<input type="text" name="ez-base-title" id="ez-base-title" class="output" value="{CONTENT}" size="70">',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'AI Revise Main Title',
    'button-id' => 'my-seo-fetch-revise-title',
    'ai-max-tokens' => 60,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k', // 'gpt-3.5-turbo-16k', 'gpt-3.5-turbo-instruct', 'gpt-4', 'gpt-4-32k'
    'more-buttons' => [[ 'id'=>'update-main-title', 'label'=>'Update Main Title' ]],
);

$g_fields[] = array(
    'id' => 'ez-meta-title',
    'label' => 'Meta Title',
    'description' => 'The meta title for the page.',
    'prompt' => 'Show me a catchy social media title for an og:title tag based on the page title for a blog called {POST_TITLE}.',
    'template-admin' => '<input type="text" name="ez-meta-title" id="ez-meta-title" class="output" value="{CONTENT}" size="70">',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'Fetch AI Title',
    'button-id' => 'my-seo-fetch-title',
    'ai-max-tokens' => 60,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k', // 'gpt-3.5-turbo-16k', 'gpt-3.5-turbo-instruct', 'gpt-4', 'gpt-4-32k'
);


$g_fields[] = array(
    'id' => 'ez-meta-desc',
    'label' => 'Meta Description',
    'description' => 'The meta description for the page.',
    'prompt' => 'Just show the description. Show me a catchy social media description for an og:description tag based on the page content for a blog called {POST_TITLE}.',
    'template-admin' => '<textarea name="ez-meta-desc" id="ez-meta-desc" class="output" rows="3" cols="70">{CONTENT}</textarea>',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'Fetch AI Description',
    'button-id' => 'my-seo-fetch-ai-description',
    'ai-max-tokens' => 250,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
);

$g_fields[] = array(
    'id' => 'ez-meta-keywords',
    'label' => 'Meta Keywords',
    'description' => 'The meta keywords for the page.',
    'prompt' => 'Just show the keywords with each keyword separated by a comma. No numbers. Show me a list of keywords for a blog called {POST_TITLE}. Order by most relevant to least relevant.',
    'template-admin' => '<textarea name="ez-meta-keywords" id="ez-meta-keywords" class="output" rows="3" cols="70">{CONTENT}</textarea>',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'Fetch AI Keywords',
    'button-id' => 'my-seo-fetch-ai-keywords',
    'ai-max-tokens' => 200,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
);

$g_fields[] = array(
    'id' => 'ez-meta-categories',
    'label' => 'Meta Categories',
    'description' => 'The meta categories for the page.',
    'prompt' => 'Just show the categories, with each category separated by a comma. No numbers. Show me a list of categories for a blog called {POST_TITLE}. Order by most relevant to least relevant.',
    'template-admin' => '<textarea name="ez-meta-categories" id="ez-meta-categories" class="output" rows="3" cols="70">{CONTENT}</textarea>',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'Fetch AI Categories',
    'button-id' => 'my-seo-fetch-ai-categories',
    'ai-max-tokens' => 200,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
);

$g_fields[] = array(
    'id' => 'ez-meta-tags',
    'label' => 'Meta Tags',
    'description' => 'The meta tags for the page.',
    'prompt' => 'Just show me the tags, with each tag separated by a comma. No numbers. Show me a list of tags for a blog called {POST_TITLE}. Order by most relevant to least relevant.',
    'template-admin' => '<textarea name="ez-meta-tags" id="ez-meta-tags" class="output" rows="3" cols="70">{CONTENT}</textarea>',
    'sanitize-type' => 'sanitize_text_field',
    'escape-type' => 'esc_attr',
    'button' => 'Fetch AI Tags',
    'button-id' => 'my-seo-fetch-ai-tags',
    'ai-max-tokens' => 200,
    'ai-temperature' => 0.7,
    'ai-model' =>  'gpt-3.5-turbo-16k',
);


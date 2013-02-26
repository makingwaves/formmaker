{* Template renders summary page (if form has it enabled), params:
- $all_pages, array
- $body_text, string *}

{def $thumb = ''}

<input type="hidden" name="summary_page" value="1" />
<p>{$body_text|wash()|i18n( 'formmaker/front' )}</p>
<br/>
{foreach $all_pages as $page}
    {if is_set( $page.page_label )}
        <strong>{$page.page_label|i18n( 'formmaker/front' )}</strong>
    {/if}
    <br/>

    {set $thumb = ''}
    {foreach $page.attributes as $attribute}

        {* if it's a file *}
        {if $attribute.is_file}

            {* it's an image so display a thumbnail *}
            {if $attribute.is_image}

                {set $thumb = $attribute.value|explode('.') }
                {set $thumb = concat($thumb.0, '_thumb.', $thumb.1)}            

                <span>{$attribute.label|i18n( 'formmaker/front' )}:<br/>
                <a target="_blank" href="/{$attribute.value}"><img src="/{$thumb}" /></a>
                <br/>

            {* ...otherwise display link to file *}
            {else}
                {def $extension = $attribute.value|explode('.')}
                <span>{$attribute.label|i18n( 'formmaker/front' )}:
                <a target="_blank" href="/{$attribute.value}">{$extension.1}</a>
                <br/>
            {/if}
        {else}
            <span>{$attribute.label|i18n( 'formmaker/front' )}: <i>{$attribute.value}</i></span><br/>
        {/if}
    {/foreach}
    <br/>    
{/foreach}

{include uri="design:formmaker/form_buttons.tpl" send_name='form-send' send_value='Send' back_display=true() back_value='Edit'}
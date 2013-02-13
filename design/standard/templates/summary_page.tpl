{* Template renders summary page (if form has it enabled), params:
- $all_pages, array
- $body_text, string *}

<input type="hidden" name="summary_page" value="1" />
<p>{$body_text|wash()|i18n( 'formmaker/front' )}</p>
<br/>
{foreach $all_pages as $page}
    {if is_set( $page.page_label )}
        <strong>{$page.page_label|i18n( 'formmaker/front' )}</strong>
    {/if}
    <br/>

    {def $thumb = ''}
    {foreach $page.attributes as $attribute}

        {* if it's a file *}
        {if $attribute.value|contains('formmaker')}

            {* it's an image so display a thumbnail *}
            {if is_image($attribute.value)}

                {set $thumb = $attribute.value|explode('.') }
                {set $thumb = concat($thumb.0, '_thumb.', $thumb.1)}            

                <span>{$attribute.label|i18n( 'formmaker/front' )}:<br/>
                <a target="_blank" href="/{$attribute.value}"><img src="/{$thumb}" /></a>
                <br/>

            {* ...otherwise, display link to file *}
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

<div class="form-footer">
    <div class="form-footer-back">
        <input type="submit" value="{'Edit'|i18n( 'formmaker/front' )}" name="form-back"/>
    </div>
    <div class="form-footer-next">
        <input type="submit" name="form-send" value="{'Send'|i18n( 'formmaker/front' )}"/>
        <input type="hidden" name="validation" value="false"/>                
    </div>
</div>
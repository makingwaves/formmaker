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

    {foreach $page.attributes as $attribute}    
        {if $attribute.value|contains('formmaker')} {* if it's a file type *}

            {def $thumb = $attribute.value|explode('.jpg') }
            {set $thumb = concat($thumb.0, '_thumb.jpg')}            

            <span>{$attribute.label|i18n( 'formmaker/front' )}:<br/>
            {*$attribute|attribute(show)*}
            <a target="_blank" href="/{$attribute.value}"><img src="/{$thumb}" /></a>
            <br/>
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
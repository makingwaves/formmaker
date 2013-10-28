{* Template for rendering label element. Params:
- $input_id
- $css *}

{if ezini( 'AdditionalElements', 'Css', 'formmaker.ini' )|eq( 'enabled' )}
    <div class="form-field-attribute">
        {'CSS class:'|i18n( 'formmaker/admin' )}
        <input type="text" value="{$css|wash()}" name="formelement_{$input_id}[css]" />
    </div>
{/if}
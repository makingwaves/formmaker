{* Template for rendering label element. Params:
- $input_id
- $css *}

{if ezini( 'AdditionalElements', 'css', 'formmaker.ini' )|eq( 'enabled' )}
    <div class="form-field-attribute">
        {'CSS class: '|i18n( 'extension/formmaker/admin' )}
        <input type="text" value="{$css}" name="formelement_{$input_id}[css]" />
    </div>
{/if}
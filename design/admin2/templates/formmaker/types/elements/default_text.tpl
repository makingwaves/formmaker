{* Template for rendering default element (as an text input) . Params:
- $input_id
- $default_value - string *}

{if ezini( 'AdditionalElements', 'DefaultValue', 'formmaker.ini' )|eq( 'enabled' )}
    <div class="form-field-attribute">
        {'Default value:'|i18n( 'formmaker/admin' )}
        <input type="text" value="{$default_value|wash()}" name="formelement_{$input_id}[default]" />
    </div>
{/if}
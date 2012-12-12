{* Template for rendering default element (as an text input) . Params:
- $input_id
- $default_value - string *}

<span>
    {'Default value: '|i18n( 'extension/formmaker/admin' )} 
    <input type="text" value="{$default_value}" name="formelement_{$input_id}[default]" />
</span>
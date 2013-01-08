{* Template for rendering description element. Params:
- $input_id
- $description - string, displayed value *}

<span>
    {'Description: '|i18n( 'extension/formmaker/admin' )} 
    <input class="input-description-field" type="text" value="{$description}" name="formelement_{$input_id}[description]" />
</span>
{* Template for rendering description element. Params:
- $input_id
- $description - string, displayed value *}

<div class="form-field-attribute">
    {'Description: '|i18n( 'extension/formmaker/admin' )} 
    <input class="input-description-field" type="text" value="{$description}" name="formelement_{$input_id}[description]" />
</div>
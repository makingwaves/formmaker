{* Template renders custom regex attribute part, params:
- $regex - string,
- $input_id - int, attribute id *}

<div class="form-field-attribute dynamic-validator-holder">
    <span class="custom-regex-inputs">
        {'Validation Regex:'|i18n( 'formmaker/admin' )}
        <input type="text" value="{$regex|wash()}" name="formelement_{$input_id}[custom_regex]" />
    </span>
</div>
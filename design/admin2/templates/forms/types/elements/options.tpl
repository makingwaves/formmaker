{* Template for rendering options attribute. Params:
- $input_id *}

<input type="hidden" class="attribute-unique-id" value="{$input_id}" />
<fieldset class="form-attribute-options">
    <legend>{'Options'|i18n( 'extension/formmaker/admin' )}</legend>
    <ul>
        {include uri="design:forms/types/elements/option_line.tpl" input_id=$input_id}
    </ul>
    <a class="add-option">{'Add option'|i18n( 'extension/formmaker/admin' )}</a>
</fieldset>
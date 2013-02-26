{* Template for rendering options attribute. Params:
- $input_id
- $default_value
- $options - array of attribute options *}

<input type="hidden" class="attribute-unique-id" value="{$input_id}" />
<div class="form-field-radio-container">
    <fieldset class="form-attribute-options">
        <legend>{'Options'|i18n( 'formmaker/admin' )}</legend>
        <ul>
            {foreach $options as $option}
                {include uri="design:formmaker/types/elements/option_line.tpl" option_id=$option.id input_id=$input_id label=$option.label default_value=$default_value}
            {/foreach}
        </ul>
        <a class="add-option">{'Add option'|i18n( 'formmaker/admin' )}</a>
    </fieldset>
</div>
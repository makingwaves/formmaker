{* Template renders view the line for text line, parameters:
- $input - textline object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute
- $regex - string, needed for custom_regex.tpl
- $validator_email_id - integer, is defined in class constant formValidators::EMAIL_ID
- $validator_custom_regex_id - integer, is defined in class constant formValidators::CUSTOM_REGEX *}

<div class="formField form-{$input.id}">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    {include uri="design:forms/types/elements/field_header.tpl" input_name=$input.name input_id=$input_id enabled=$data.enabled}
    <div class="form-field-attributes-container">
        {include uri="design:forms/types/elements/label.tpl" label=$data.label input_id=$input_id}
        {include uri="design:forms/types/elements/identifier.tpl" identifier=$data.identifier input_id=$input_id}
        {include uri="design:forms/types/elements/mandatory.tpl" is_mandatory=$data.is_mandatory input_id=$input_id}
        {include uri="design:forms/types/elements/description.tpl" description=$data.description input_id=$input_id}
        {include uri="design:forms/types/elements/default_text.tpl" default_value=$data.default_value input_id=$input_id}
        {include uri="design:forms/types/elements/css.tpl" css=$data.css input_id=$input_id}
        {include uri="design:forms/types/elements/validation.tpl" email_receiver=$data.email_receiver validators=$input.validators validator_ids=$data.validator_ids 
                 input_id=$input_id validator_email_id=$validator_email_id validator_custom_regex_id=$validator_custom_regex_id regex=$data.custom_regex}
    </div>
    
</div>
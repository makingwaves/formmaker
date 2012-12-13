{* Template renders view the line for text line, parameters:
- $input - checkbox object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField form-{$input.id}">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    {include uri="design:forms/types/elements/field_header.tpl" input_name=$input.name input_id=$input_id enabled=$data.enabled}
    <p>
        {include uri="design:forms/types/elements/label.tpl" label=$data.label input_id=$input_id}
        <span class="spc">|</span>
        {include uri="design:forms/types/elements/mandatory.tpl" is_mandatory=$data.is_mandatory input_id=$input_id}
        <span class="spc">|</span>
        {include uri="design:forms/types/elements/default_checkbox.tpl" default_value=$data.default_value input_id=$input_id}
    </p>
</div>
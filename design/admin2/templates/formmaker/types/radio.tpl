{* Template renders view the line for the radio buttons, parameters:
- $input - radio object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField form-{$input.id}">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    <input type="hidden" name="formelement_{$input_id}[default]" value="{$data.default_value}" />
    {include uri="design:formmaker/types/elements/field_header.tpl" input_name=$input.name input_id=$input_id enabled=$data.enabled}
    <div class="form-field-attributes-container">
        {include uri="design:formmaker/types/elements/label.tpl" label=$data.label input_id=$input_id}
        {include uri="design:formmaker/types/elements/mandatory.tpl" is_mandatory=$data.is_mandatory input_id=$input_id}
        {include uri="design:formmaker/types/elements/description.tpl" description=$data.description input_id=$input_id}
        {include uri="design:formmaker/types/elements/css.tpl" css=$data.css input_id=$input_id}
        {include uri="design:formmaker/types/elements/identifier.tpl" identifier=$data.identifier input_id=$input_id}
        {include uri="design:formmaker/types/elements/options.tpl" input_id=$input_id options=$data.options default_value=$data.default_value}
    </div>
</div>
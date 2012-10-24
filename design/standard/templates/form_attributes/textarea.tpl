{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<label for="mwform_{$attribute.type}_{$attribute.id}">
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<textarea class="mwform_element" id="mwform_{$attribute.type}_{$attribute.id}" name="field_textarea_{$attribute.id}">{$attribute.default_value}</textarea>
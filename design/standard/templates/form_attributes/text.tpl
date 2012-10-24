{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<label for="mwform_{$attribute.type}_{$attribute.id}">
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<input class="mwform_element" id="mwform_{$attribute.type}_{$attribute.id}" name="field_text_{$attribute.id}" type="text" value="{$attribute.default_value}"/>
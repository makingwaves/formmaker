{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<label for="form_{$attribute.type_id}_{$attribute.id}">
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<input class="form_element" id="form_{$attribute.type_id}_{$attribute.id}" name="field_text_{$attribute.id}" type="text" value="{$attribute.default_value}"/>
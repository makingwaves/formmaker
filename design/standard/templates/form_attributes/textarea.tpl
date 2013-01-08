{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<p class="form-element-description">{$attribute.description}</p>
<label for="form_{$attribute.type_id}_{$attribute.id}">
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<textarea class="form_element" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}">{$attribute.default_value}</textarea>
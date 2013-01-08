{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<p class="form-element-description">{$attribute.description}</p>
{def $default_value = $attribute.default_value}
{if eq( $default_value, '0' )}
    {set $default_value = ''}
{/if}

<label class="form_label_checkbox">
    <input type="checkbox" connected="form_{$attribute.type_id}_{$attribute.id}" {if eq( $attribute.default_value, 'on' )}checked="checked"{/if}/>
    {$attribute.label} 
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label>
<input id="form_{$attribute.type_id}_{$attribute.id}" type="hidden" name="field_{$attribute.type_id}_{$attribute.id}" value="{$default_value}"/>
{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $default_value = $attribute.default_value}
{if eq( $default_value, '0' )}
    {set $default_value = ''}
{/if}

<label class="mwform_label_checkbox">
    <input type="checkbox" connected="mwform_{$attribute.type}_{$attribute.id}" {if eq( $attribute.default_value, 'on' )}checked="checked"{/if}/>
    {$attribute.label} 
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label>
<input id="mwform_{$attribute.type}_{$attribute.id}" type="hidden" name="field_{$attribute.type}_{$attribute.id}" value="{$default_value}"/>
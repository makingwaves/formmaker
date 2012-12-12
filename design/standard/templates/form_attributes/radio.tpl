{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

<label>
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
{foreach $attribute.options as $option}
    <label class="form_label_radio">
        <input type="radio" {if eq( $attribute.default_value, $option.id )}checked="checked"{/if} name="connected_field_{$attribute.type_id}_{$attribute.id}" value="{$option.id}" />
        {$option.label}
    </label>
{/foreach}
<input type="hidden" name="field_{$attribute.type_id}_{$attribute.id}" value="{$attribute.default_value}" />
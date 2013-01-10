{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $css_class = 'form_element' 
     $default_value = '' }
     
{if ezini( 'AdditionalElements', 'css', 'formmaker.ini' )|eq( 'enabled' )}
    {set $css_class = concat( $css_class, ' ', $attribute.css|wash() ) }
{/if}

{if ezini( 'AdditionalElements', 'description', 'formmaker.ini' )|eq( 'enabled' )}
    <p class="form-element-description">{$attribute.description}</p>
{/if}

{if ezini( 'AdditionalElements', 'default_value', 'formmaker.ini' )|eq( 'enabled' )}
    {set $default_value = $attribute.default_value }
{/if}

<label for="form_{$attribute.type_id}_{$attribute.id}">
    {$attribute.label}
{include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<input class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="text" value="{$default_value}"/>
<input type="hidden" class="validation-type" value="{$attribute.validator_ids|implode(',')}"/>
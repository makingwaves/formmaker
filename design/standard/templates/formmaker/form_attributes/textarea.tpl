{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $css_class = 'form_element'}
     
{if ezini( 'AdditionalElements', 'css', 'formmaker.ini' )|eq( 'enabled' )}
    {set $css_class = concat( $css_class, ' ', $attribute.css|wash() ) }
{/if}

{if ezini( 'AdditionalElements', 'description', 'formmaker.ini' )|eq( 'enabled' )}
    <p class="form-element-description">{$attribute.description}</p>
{/if}

<label for="form_{$attribute.type_id}_{$attribute.id}">
    {$attribute.label}
    {include uri="design:formmaker/form_attributes/parts/required.tpl" is_required=$is_required}
</label> 
<textarea class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}">{$attribute.default_value}</textarea>
{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $default_value = '' 
     $css_class = '' }

{if ezini( 'AdditionalElements', 'Description', 'formmaker.ini' )|eq( 'enabled' )}
    <p class="form-element-description">{$attribute.description}</p>
{/if}

{if ezini( 'AdditionalElements', 'DefaultValue', 'formmaker.ini' )|eq( 'enabled' )}
    {set $default_value = $attribute.default_value }
    {if eq( $default_value, '0' )}
        {set $default_value = ''}
    {/if}    
{/if}

{if ezini( 'AdditionalElements', 'Css', 'formmaker.ini' )|eq( 'enabled' )}
    {set $css_class = $attribute.css|wash() }
{/if}

<label class="form_label_checkbox">
    <input class="{$css_class}" type="checkbox" connected="form_{$attribute.type_id}_{$attribute.id}" {if eq( $default_value, 'on' )}checked="checked"{/if}/>
    {$attribute.label} 
    {include uri="design:formmaker/form_attributes/parts/required.tpl" is_required=$is_required}
</label>
<input id="form_{$attribute.type_id}_{$attribute.id}" type="hidden" name="field_{$attribute.type_id}_{$attribute.id}" value="{$default_value}"/>
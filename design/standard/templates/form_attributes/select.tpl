{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $css_class = ''}

{if ezini( 'AdditionalElements', 'css', 'formmaker.ini' )|eq( 'enabled' )}
    {set $css_class = $attribute.css|wash() }
{/if}

{if ezini( 'AdditionalElements', 'description', 'formmaker.ini' )|eq( 'enabled' )}
    <p class="form-element-description">{$attribute.description}</p>
{/if}

<label>
    {$attribute.label}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label>

<span class="{$css_class}">
    <select name="field_{$attribute.type_id}_{$attribute.id}">
        {foreach $attribute.options as $option}
        <option {if eq( $attribute.default_value, $option.id )}selected{/if} value="{$option.id}">{$option.label}</option>
        {/foreach}
    </select>
</span>
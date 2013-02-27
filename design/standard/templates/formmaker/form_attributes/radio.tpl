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
    {include uri="design:formmaker/form_attributes/parts/required.tpl" is_required=$is_required}
</label>
<span class="{$css_class}">
    {foreach $attribute.options as $option}
        <label class="form_label_radio">
            <input type="radio" {if eq( $attribute.default_value, $option.id )}checked="checked"{/if} name="connected_field_{$attribute.type_id}_{$attribute.id}" value="{$option.id}" />
            {$option.label}
        </label>
    {/foreach}
</span>
<input type="hidden" name="field_{$attribute.type_id}_{$attribute.id}" value="{$attribute.default_value}" />
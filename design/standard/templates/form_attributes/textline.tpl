{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
- $year_validator - integer, id of date year validator
*}

{def $css_class = 'form_element' 
     $default_value = ''
     $current_year = currentdate()|datetime( 'custom', '%Y' )
     $lowest_year = sum( $current_year, -150 )}
     
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
{if $attribute.validator_ids|contains( $year_validator )}
    <select class="date-year-validation {$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}">
        <option value=""></option>
        {for $lowest_year to $current_year as $year}
            <option value="{$year}">{$year}</option>
        {/for}
    </select>
{else}
    <input class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="text" value="{$default_value}"/>
{/if}
<input type="hidden" class="validation-type" value="{$attribute.validator_ids|implode(',')}"/>
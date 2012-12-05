{if $disabled}
<div class="formFieldTemplate" id="tpl_textarea" rel="new">

    <select disabled="disabled" name="types[]">
        
        {foreach $fields as $key => $field}
            {set $selected = ''}
            {if eq($field, 'textarea')}
                {set $selected = 'selected="selected"'}
            {/if}
            <option value="{$field}" {$selected}>{$field}</option>
        {/foreach}
        
    </select>
    
    <input type="hidden" name="ids[]" disabled="disabled" value=""> <span class="spc">|</span>
    {'label'|i18n( 'extension/formmaker/admin' )}: <input disabled="disabled" name="labels[]" type="text"></input> <span class="spc">|</span>
    {'mandatory'|i18n( 'extension/formmaker/admin' )}: <input type="checkbox" disabled="disabled" name="mandatories[]" type="text" value="on"></input> <span class="spc clear"></span>
    <input type="hidden" name="mandatoriesValue[]" value="0" disabled="disabled" />
    
    <select disabled="disabled" name="validators[]">
        <option value="- validation -">- {'validation'|i18n( 'extension/formmaker/admin' )} -</option>
        {foreach $validators as $key => $validator}
        <option value="{$validator.id}">{$validator.description}</option>
        {/foreach}
    </select>
    
    <span class="spc">|</span>
        
    <input type="hidden" disabled="disabled" name="placeholders[]" value="" />
    <input type="hidden" disabled="disabled" name="placeholdersValue[]" value="0" />
    
    <a class="removeField"></a>

</div>
    
{else}

<div class="formField" rel="0">

    <input type="hidden" name="ids[]" value="{$item.id}"></input>
    
    <select name="types[]">
        {foreach $fields as $key => $field}
            {set $selected = ''}
            {if eq($field, $item.type)}
                {set $selected = 'selected="selected"'}
            {/if}
            <option value="{$field}" {$selected}>{$field}</option>
        {/foreach}
    </select>
    
    <span class="spc">|</span>
    
    {'label'|i18n( 'extension/formmaker/admin' )}: <input name="labels[]" type="text" value="{$item.label}"></input> <span class="spc">|</span>
    {'mandatory'|i18n( 'extension/formmaker/admin' )}: <input type="checkbox" name="mandatories[]" {if $activeValidatorsIDs|contains(5)}checked="checked"{/if} value="on"></input><span class="spc clear"></span>
    <input type="hidden" name="mandatoriesValue[]" value="{if $activeValidatorsIDs|contains(5)}1{else}0{/if}" />
    
    <select name="validators[]">
        <option value="- validation -">- {'validation'|i18n( 'extension/formmaker/admin' )} -</option>
        {foreach $validators as $key => $validator}
        {set $selected = ''}   
        {if $activeValidatorsIDs|contains($validator.id) }
            {set $selected = 'selected="selected"'}
        {/if}
        <option value="{$validator.id}" {$selected}>{$validator.description}</option>
        {/foreach}
    </select>
    
    <span class="spc">|</span>
    
    <input type="hidden" name="placeholders[]" value="" />
    <input type="hidden" name="placeholdersValue[]" value="0" />
    
    <a class="removeField"></a>

</div>

{/if}
{* Template renders view the line for text line, parameters:
- $input - textline object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    <p><strong>{$input.name}</strong></p>
    <p>
        {'Label: '|i18n( 'extension/formmaker/admin' )} <input type="text" value="{$data.label}" name="formelement_{$input_id}[label]" />
        <span class="spc">|</span>
        <span>
            {'Mandatory: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $data.is_mandatory}checked="checked"{/if} />
            <input type="hidden" name="formelement_{$input_id}[mandatory]" value="{$data.is_mandatory}" />
        </span>
        <span class="spc">|</span>
        {'Default value: '|i18n( 'extension/formmaker/admin' )} <input type="text" value="{$data.default_value}" name="formelement_{$input_id}[default]" />
    </p>
    <p>
        {'Validation: '|i18n( 'extension/formmaker/admin' )}
        <select name="formelement_{$input_id}[validation]">
            <option value="0">{'- no validation -'|i18n( 'extension/formmaker/admin' )}</option>
            {foreach $input.validators as $validator}
                <option {if $data.validator_ids|contains( $validator.id )}selected="selected"{/if} value="{$validator.id}">{$validator.description}</option>
            {/foreach}            
        </select>
    </p>
    <a class="removeField">{'Remove'|i18n( 'extension/formmaker/admin' )}</a>
</div>
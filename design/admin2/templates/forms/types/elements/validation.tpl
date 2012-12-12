{* Template for rendering validators element. Params:
- $input_id
- $validators - array
- $validator_ids - array *}

<span>
    {'Validation: '|i18n( 'extension/formmaker/admin' )}
    <select name="formelement_{$input_id}[validation]">
        <option value="0">{'- no validation -'|i18n( 'extension/formmaker/admin' )}</option>
        {foreach $validators as $validator}
            <option {if $validator_ids|contains( $validator.id )}selected="selected"{/if} value="{$validator.id}">{$validator.description}</option>
        {/foreach}            
    </select>
</span>
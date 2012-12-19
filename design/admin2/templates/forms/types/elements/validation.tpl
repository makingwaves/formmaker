{* Template for rendering validators element. Params:
- $input_id
- $email_receiver - 0|1
- $validators - array
- $validator_ids - array, since in can contain "required" validator,
- $validator_email_id - integer, is defined in class constant formValidators::EMAIL_ID *}

{def $selected_validator = 0}

<input type="hidden" class="attribute-unique-id" value="{$input_id}" />
<span>
    {'Validation: '|i18n( 'extension/formmaker/admin' )}
    <select class="attribute-validation" name="formelement_{$input_id}[validation]">
        <option value="0">{'- no validation -'|i18n( 'extension/formmaker/admin' )}</option>
        {foreach $validators as $validator}
            <option {if $validator_ids|contains( $validator.id )}{set $selected_validator = $validator.id} selected="selected"{/if} value="{$validator.id}">
                {$validator.description}
            </option>
        {/foreach}            
    </select>
    {if $selected_validator|eq( $validator_email_id )}
        {include uri="design:forms/types/elements/email_receiver.tpl" enabled=$email_receiver input_id=$input_id}
    {/if}
</span>
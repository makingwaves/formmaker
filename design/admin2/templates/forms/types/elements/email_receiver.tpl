{* Template renders email receiver attribute part, params:
- $enabled - 0|1,
- $input_id - int, attribute id *}

<div class="form-field-attribute email-receiver-holder">
    <span class="email-receiver-inputs">
        <input type="hidden" value="{$enabled}" name="formelement_{$input_id}[email_receiver]"/>
        {'Email receiver:'|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $enabled}checked="checked"{/if} />      
    </span>
</div>
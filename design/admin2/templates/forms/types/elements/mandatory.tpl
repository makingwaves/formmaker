{* Template for rendering mandatory element. Params:
- $input_id
- $is_mandatory - variable contains "0" or "on" *}

<div class="form-field-attribute attribute-mandatory-holder">
    {'Mandatory: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $data.is_mandatory}checked="checked"{/if} />
    <input type="hidden" name="formelement_{$input_id}[mandatory]" value="{$is_mandatory}" />
</div>
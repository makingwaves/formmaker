{* Template for rendering mandatory element. Params:
- $input_id
- $is_mandatory - variable contains "0" or "on" *}

<span class="attribute-mandatory-holder">
    {'Mandatory: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $data.is_mandatory}checked="checked"{/if} />
    <input type="hidden" name="formelement_{$input_id}[mandatory]" value="{$is_mandatory}" />
</span>
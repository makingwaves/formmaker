{* Template for rendering label element. Params:
- $input_id
- $label *}

<span>
    {'Label: '|i18n( 'extension/formmaker/admin' )} 
    <input type="text" value="{$label}" name="formelement_{$input_id}[label]" />
</span>
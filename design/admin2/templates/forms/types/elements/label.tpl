{* Template for rendering label element. Params:
- $input_id
- $label *}

<span>
    {'Label: '|i18n( 'extension/formmaker/admin' )}<span class="form_attribute_required"> *</span>
    <input type="text" value="{$label}" name="formelement_{$input_id}[label]" required />
</span>
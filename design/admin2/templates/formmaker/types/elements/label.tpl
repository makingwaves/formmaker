{* Template for rendering label element. Params:
- $input_id
- $label *}

<div class="form-field-attribute">
    {'Label:'|i18n( 'formmaker/admin' )} <span class="form_attribute_required"> *</span>
    <input type="text" value="{$label|wash()}" name="formelement_{$input_id}[label]" required />
</div>
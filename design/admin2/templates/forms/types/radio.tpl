{* Template renders view the line for the radio buttons, parameters:
- $input - radio object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField form-{$input.id}">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    <input type="hidden" name="formelement_{$input_id}[default]" value="{$data.default_value}" />
    <p><strong>{$input.name}</strong></p>
    <p>
        {include uri="design:forms/types/elements/label.tpl" label=$data.label input_id=$input_id}
        <span class="spc">|</span>
        {include uri="design:forms/types/elements/mandatory.tpl" is_mandatory=$data.is_mandatory input_id=$input_id}
    </p>
    <p>
        {include uri="design:forms/types/elements/options.tpl" input_id=$input_id options=$data.options default_value=$data.default_value}
    </p>
    <a class="removeField">{'Remove'|i18n( 'extension/formmaker/admin' )}</a>
</div>
{* Template renders view the line for text line, parameters:
- $input - textarea object
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    <p><strong>{$input.name}</strong></p>
    <p>
        {'Label: '|i18n( 'extension/formmaker/admin' )} <input type="text" name="formelement_{$input_id}[label]" />
        <span class="spc">|</span>
        <span>
            {'Mandatory: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" />
            <input type="hidden" name="formelement_{$input_id}[mandatory]" value="0" />
        </span>
    </p>
    <a class="removeField">{'Remove'|i18n( 'extension/formmaker/admin' )}</a>
</div>
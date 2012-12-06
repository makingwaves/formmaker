{* Template renders view the line for text line, parameters:
- $input - checkbox object
- $data - attribute object conatining data (or empty object when adding new attribute)
- $input_id - id of attribute stored in database or unique id for new attribute *}

<div class="formField">
    <input type="hidden" name="formelement_{$input_id}[type]" value="{$input.id}"/>
    <p><strong>{$input.name}</strong></p>
    <p>
        {'Label: '|i18n( 'extension/formmaker/admin' )} <input type="text" value="{$data.label}" name="formelement_{$input_id}[label]" />
        <span class="spc">|</span>
        <span>
            {'Mandatory: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $data.is_mandatory}checked="checked"{/if} />
            <input type="hidden" name="formelement_{$input_id}[mandatory]" value="{$data.is_mandatory}" />
        </span>
        <span class="spc">|</span>
        <span>
            {'Default value: '|i18n( 'extension/formmaker/admin' )} <input type="checkbox" {if $data.default_value}checked="checked"{/if} />
            <input type="hidden" name="formelement_{$input_id}[default]" value="{$data.default_value}" />
        </span>
    </p>
    <a class="removeField">{'Remove'|i18n( 'extension/formmaker/admin' )}</a>
</div>
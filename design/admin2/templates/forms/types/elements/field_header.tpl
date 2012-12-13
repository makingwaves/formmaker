{* template renders header part of each field type, params:
- $input_name - string, for example "Textarea"
- $input_id - integer,
- $enabled - 0|1 *}

<div class="attribute-header">
    <div class="attribute-name">{$input_name|wash()}</div>
    <div class="attribute-actions">
        <a class="remove-field">{'Remove'|i18n( 'extension/formmaker/admin' )}</a>
        <span class="enable-attribute">
            <input type="hidden" value="{$enabled}" name="formelement_{$input_id}[enabled]"/>
            <input type="checkbox" {if $enabled}checked="checked"{/if} />{'Enabled'|i18n( 'extension/formmaker/admin' )}
        </span>
    </div>
</div>
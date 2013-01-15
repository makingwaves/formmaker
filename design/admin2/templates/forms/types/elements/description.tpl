{* Template for rendering description element. Params:
- $input_id
- $description - string, displayed value *}

{if ezini( 'AdditionalElements', 'description', 'formmaker.ini' )|eq( 'enabled' )}
    <div class="form-field-attribute">
        {'Description:'|i18n( 'formmaker/admin' )} 
        <input class="input-description-field" type="text" value="{$description}" name="formelement_{$input_id}[description]" />
    </div>
{/if}
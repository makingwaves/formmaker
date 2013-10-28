{* Template for rendering description element. Params:
- $input_id
- $allowed_file_types - string, displayed value *}

{def $allowed_file_types_ini = ezini( 'AdditionalElements', 'AllowedFileTypes', 'formmaker.ini' )}

{if and( $allowed_file_types_ini, $allowed_file_types|not() )}
	{set $allowed_file_types = $allowed_file_types_ini}
{/if}

<div class="form-field-attribute">
    {'Allowed file types'|i18n( 'extension/formmaker/admin' )}
    <input class="input-description-field" type="text" value="{$allowed_file_types|wash()}" name="formelement_{$input_id}[allowed_file_types]" />
</div>
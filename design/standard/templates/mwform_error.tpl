{* Template takes following parameters:
- $attribute_id - attribute id
- $errors - array of validation errors
*}

{if is_set($errors[$attribute_id])}
    <span class="mwform_error">
        {foreach $errors.$attribute_id as $messages}
            {foreach $messages as $message}{$message}<br/>{/foreach}
        {/foreach}
    </span>
{/if}
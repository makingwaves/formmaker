{* Displays information about processing the template, params:
- $result - boolean value
- $form_definition, object *}

{if $result}
    <p class="form-receipt-intro">{$form_definition.receipt_intro|wash()}</p>
    <p class="form-receipt-body">{$form_definition.receipt_body|wash()}</p>
{else}
    {'Sorry, there were some problems with sending the email message. Please try again later.'|i18n( 'extension/formmaker/front' )}
{/if}
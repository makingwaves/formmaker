{* Displays information about processing the template, params:
- $result - boolean value *}

{if $result}
    {'Thank you for sending us the information.'|i18n( 'extension/formmaker/front' )}
{else}
    {'Sorry, there were some problems with sending the email message. Please try again later.'|i18n( 'extension/formmaker/front' )}
{/if}
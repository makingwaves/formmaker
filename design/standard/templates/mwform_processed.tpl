{* Displays information about processing the template, params:
- $result - boolean value *}

{if $result}
    {'Thank you for sending us the information.'|i18n( 'extension/mwezforms/front' )}
{else}
    {'Sorry, there were some problems with sending the email message. Please try again later.'|i18n( 'extension/mwezforms/front' )}
{/if}
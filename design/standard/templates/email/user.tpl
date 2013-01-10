{* Template renders an email message for target users, params:
- $data - form data grouped into pages *}

<strong>{'Thank you for sending us the information.'|i18n( 'extension/formmaker/email' )}</strong>
<br/><br/>
{foreach $data as $page}
    <strong>{$page.page_label|i18n( 'extension/formmaker/email' )}</strong>
    <br/>
    {foreach $page.attributes as $attribute}
        <span>{$attribute.label}: {$attribute.value}</span><br/>
    {/foreach}
    <br/>
{/foreach}
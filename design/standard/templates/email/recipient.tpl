{* Template renders an email message for target recipient(s), params:
- $data - form data grouped into pages *}

<strong>{'We kindly inform about the new answer.'|i18n( 'extension/formmaker/email' )}</strong>
<br/><br/>
{foreach $data as => $page}
    <strong>{$page.page_label|i18n( 'extension/formmaker/email' )}</strong>
    <br/>
    {foreach $page.attributes as $label => $value}
        <span>{$label}: {$value}</span><br/>
    {/foreach}
    <br/>
{/foreach}
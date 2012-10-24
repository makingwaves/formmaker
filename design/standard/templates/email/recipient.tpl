<strong>We kindly inform about new answer on form {$form_name}:</strong>
<br/><br/>
{foreach $data as $label => $value}
    <span>{$label}: {$value}</span><br/>
{/foreach}
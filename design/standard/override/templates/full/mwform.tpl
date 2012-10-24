{* Template renders full view of mwform objects *}

{if $node.data_map.form.has_content}
    {set-block scope=root variable=cache_ttl}0{/set-block}
    {attribute_view_gui attribute=$node.data_map.form node=$node}
{/if}

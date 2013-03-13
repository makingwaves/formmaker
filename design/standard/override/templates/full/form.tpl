{* Template renders full view of form objects *}

{if $node.data_map.form.has_content}
    {set-block scope=root variable=cache_ttl}0{/set-block}
    {def $view_type = first_set( $node.data_map.view_type.contentclass_attribute.content.options[$node.data_map.view_type.content.0].name, 'default' )}
    {attribute_view_gui view=$view_type attribute=$node.data_map.form node=$node}
{/if}

{* Template renders the form steps, params:
- all_pages, array containing data for all pages defined in form 
- form_definition, object
- form_data, array
- current_page, integer *}

{def $current_step = $current_page}
{if is_set( $form_data.summary_page )}
    {set $current_step = -1}
{elseif is_set( $form_data.success )}
    {set $current_step = -2}
{/if}

<div class="form-steps">
    {foreach $all_pages as $i => $page}
        <span class="form-step {if $current_step|eq( $i )}form-current-step{/if}">
            {cond( is_set( $page.page_info.label ), $page.page_info.label, $form_definition.first_page )|wash()|i18n( 'formmaker/front' )}
        </span> > 
    {/foreach}
    {if $form_definition.summary_page}
        <span class="form-step {if $current_step|eq( -1 )}form-current-step{/if}">
            {$form_definition.summary_label|wash()|i18n( 'formmaker/front' )}
        </span> > 
    {/if}
    <span class="form-step {if $current_step|eq( -2 )}form-current-step{/if}">
        {$form_definition.receipt_label|wash()|i18n( 'formmaker/front' )}
    </span>           
</div>
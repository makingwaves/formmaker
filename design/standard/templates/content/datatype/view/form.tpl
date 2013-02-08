{* Template renders view of form attribute *}

{def $form_data         = fetch( 'formmaker', 'data', hash( 'form_id', $node.data_map.form.content.form_id ) )
     $errors            = $form_data.validation
     $form_definition   = $form_data.definition
     $form_attributes   = $form_data.attributes 
     $counted_validators= $form_data.counted_validators
     $attr_required     = false()
     $current_page      = $form_data.current_page
     $header_text       = $form_definition.name
     $pages_count       = $form_data.all_pages|count()
     $send_button       = cond( $pages_count|eq( $form_data.current_page|inc( 1 ) ), 'Send'|i18n( 'formmaker/front' ), 'Next'|i18n( 'formmaker/front' ) )
     $send_name         = cond( $pages_count|eq( $form_data.current_page|inc( 1 ) ), 'form-send', 'form-next' ) }

{* including CSS file *}
{ezcss_load( array( 'formmaker.css', 'http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css', 'select2.css' ) )}

{* Including JS files *}
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'jquery.functions.js', 'jquery.validation.js', 'select2.min.js' ) )}
{ezscript_require( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}

{include uri="design:form_steps.tpl" form_data=$form_data all_pages=$form_data.all_pages form_definition=$form_definition current_page=$current_page}

{if is_set( $form_data.summary_page )}
    {set $header_text = $form_definition.summary_label}
{elseif is_set( $form_data.success )}
    {set $header_text = $form_definition.receipt_label}
{/if}

<div class="form-container">
    
    <h1>{$header_text|wash()|i18n( 'formmaker/front' )}</h1>

    <form id="mwezform" method="POST" action={$node.url_alias|ezurl()} {if $form_definition.multipart}enctype="multipart/form-data"{/if}>

        <input type="hidden" name="form_id" value="{$form_definition.id}"/>
        <input type="hidden" name="node_id" value="{$node.node_id}"/>
        <input type="hidden" name="current_page" value="{$current_page}" />
        <input type="hidden" id="date-validator" value="{$form_data.date_validator}" />
        <input type="hidden" id="form-datepicker-format" value="{$form_definition.datepicker_format}" />
            
        {if is_set( $form_data.summary_page )}
            {* $form_data.summary_page contains rendered template summary_page.tpl *}
            {$form_data.summary_page}
        {elseif is_set( $form_data.success )}
            {* $form_data.success contains rendered template form_processed.tpl *}
            {$form_data.success}
        {else}
            {include uri="design:form_error.tpl" errors=$errors attribute_id=0}

            {foreach $form_attributes as $attribute}
                {set $attr_required = fetch( 'formmaker', 'is_attrib_required', hash( 'attribute_id', $attribute.id ) )}
                <div class="form-element-container" id="form_element_{$attribute.id}">
                    <div class="{if and( is_set( $counted_validators[$attribute.id] ), $counted_validators[$attribute.id] )}validate-it{/if} form_attribute_content">
                        {include uri=concat('design:form_attributes/', $attribute.type_data.template ) attribute=$attribute is_required=$attr_required year_validator=$form_data.date_year_validator}
                    </div>
                    <div class="form_error_content">
                        <span class="form_notification">{include uri="design:form_error.tpl" errors=$errors attribute_id=$attribute.id}</span>
                    </div>
                </div>
            {/foreach}
            <div class="form-footer">
                <div class="form-footer-back">
                    {if $form_data.current_page|gt( 0 )}
                        <input type="submit" value="{'Back'|i18n( 'formmaker/front' )}" name="form-back"/>
                    {/if}
                </div>
                <div class="form-footer-next">
                    <input type="submit" name="{$send_name}" value="{$send_button}"/>
                    <input type="hidden" name="validation" value="false"/>                
                </div>
            </div>
        {/if}
    </form>
</div>
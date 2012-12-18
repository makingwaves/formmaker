{* Template renders view of form attribute *}

{def $form_data         = fetch( 'formmaker', 'data', hash( 'form_id', $node.data_map.form.content.form_id ) )
     $errors            = $form_data.validation
     $form_definition   = $form_data.definition
     $form_attributes   = $form_data.attributes 
     $counted_validators= $form_data.counted_validators
     $attr_required     = false()
     $current_page      = $form_data.current_page
     $send_button       = cond( $form_data.pages_count|eq( $form_data.current_page|inc( 1 ) ), 'Send'|i18n( 'extension/formmaker/front' ), 'Next'|i18n( 'extension/formmaker/front' ) )
     $send_name         = cond( $form_data.pages_count|eq( $form_data.current_page|inc( 1 ) ), 'form-send', 'form-next' )
     $has_ajax_access   = has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'formmaker' ) )}

{* including CSS file *}
{ezcss_load( array( 'formmaker.css' ) )}

{* Including JS files *}
{ezscript_load( array( 'ezjsc::jquery', 'ezjsc::jqueryio' ) )}
{ezscript_require( array( 'jquery.functions.js' ) )}
{if $has_ajax_access}
    {ezscript_require( array( 'jquery.validation.js' ) )}
{/if}

<div class="form-container">
    <h1>{$form_definition.name|wash()}</h1>

    {if is_set( $form_data.success )}
        {* $form_data.success contains rendered template form_processed.tpl *}
        {$form_data.success}
    {else}
        {include uri="design:form_error.tpl" errors=$errors attribute_id=0}
        <form id="mwezform" method="POST" action={$node.url_alias|ezurl()}>

            <input type="hidden" name="form_id" value="{$form_definition.id}"/>
            <input type="hidden" name="node_id" value="{$node.node_id}"/>
            <input type="hidden" name="current_page" value="{$current_page}" />
            
            {foreach $form_attributes as $attribute}
                {set $attr_required = fetch( 'formmaker', 'is_attrib_required', hash( 'attribute_id', $attribute.id ) )}
                <div class="form-element-container" id="form_element_{$attribute.id}">
                    <div class="{if and( is_set( $counted_validators[$attribute.id] ), $counted_validators[$attribute.id] )}validate-it{/if} form_attribute_content">
                        {include uri=concat('design:form_attributes/', $attribute.type_data.template ) attribute=$attribute is_required=$attr_required}
                    </div>
                    <div class="form_error_content">
                        <span class="form_notification">{include uri="design:form_error.tpl" errors=$errors attribute_id=$attribute.id}</span>
                    </div>
                </div>
            {/foreach}
            <div class="form-footer">
                <div class="form-footer-back">
                    {if $form_data.current_page|gt( 0 )}
                        <input type="submit" value="{'Back'|i18n( 'extension/formmaker/front' )}" name="form-back"/>
                    {/if}
                </div>
                <div class="form-footer-next">
                    <input type="submit" name="{$send_name}" value="{$send_button}"/>
                    <input type="hidden" name="validation" value="false"/>                
                </div>
            </div>
        </form>
    {/if}
</div>
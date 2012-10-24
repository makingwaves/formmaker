{* Template renders view of mwform attribute *}

{def $form_data         = fetch( 'formmaker', 'data', hash( 'form_id', $node.data_map.form.content.mwform_id ) )
     $errors            = $form_data.validation
     $form_definition   = $form_data.definition
     $form_attributes   = $form_data.attributes 
     $counted_validators= $form_data.counted_validators
     $attr_required     = NULL
     $has_ajax_access   = has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'formmaker' ) )}

{* including CSS file *}
{ezcss_load( array( 'formmaker.css' ) )}

{* Including JS files *}
{ezscript_load( array( 'ezjsc::jquery', 'ezjsc::jqueryio' ) )}
{ezscript_require( array( 'jquery.functions.js' ) )}
{if $has_ajax_access}
    {ezscript_require( array( 'jquery.validation.js' ) )}
{/if}

<div class="mwform_container">
    <h1>{$form_definition.name|wash()}</h1>

    {if is_set( $form_data.success )}
        {* $form_data.success contains rendered template mwform_processed.tpl *}
        {$form_data.success}
    {else}
        {include uri="design:mwform_error.tpl" errors=$errors attribute_id=0}
        <form id="mwezform" method="POST" class="{$form_definition.css_class}" action={$node.url_alias|ezurl()}>

            <input type="hidden" name="mwform_id" value="{$form_definition.id}"/>
            <input type="hidden" name="node_id" value="{$node.node_id}"/>
            {foreach $form_attributes as $attribute}
                {set $attr_required = fetch( 'formmaker', 'is_attrib_required', hash( 'attribute_id', $attribute.id ) )}
                <div class="mwform_element_container" id="mwform_element_{$attribute.id}">
                    
                    <div class="{if and( is_set( $counted_validators[$attribute.id] ), $counted_validators[$attribute.id] )}validate-it{/if} mwform_attribute_content {$attribute.css_class}">
                        {include uri=concat('design:form_attributes/', $attribute.type, '.tpl') attribute=$attribute is_required=$attr_required}
                    </div>
                    <div class="mwform_error_content">
                        <span class="mwform_notification">{include uri="design:mwform_error.tpl" errors=$errors attribute_id=$attribute.id}</span>
                    </div>
                </div>
            {/foreach}
            <input id="mwezform-submit" type="submit" value="{'Send'|i18n( 'extension/formmaker/front' )}"/>
            <input type="hidden" name="validation" value="false"/>
        </form>
    {/if}
</div>
{* Template renders create/edit page for the forms, parameters:
- $error_message - string conatining error message comming from validation
- $form_elements - array containing items if adding-new-form form
- $form_attributes - array of attribute objects
- $id - edited form id
- $form_name
- $separator_id - integer, is defined in class constant formTypes::SEPARATOR_ID
- $validator_email_id - integer, is defined in class constant formValidators::EMAIL_ID
- $validator_custom_regex_id - integer, is defined in class constant formValidators::CUSTOM_REGEX
- $input_types - array of all available input types *}

{ezcss_require( array( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', 'style.css' ) )}
{ezscript_load( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}
{ezscript_require(array( 'edit.js', 'fixed_toolbar.js' ) )}

{def $selected = ''
     $activeValidatorsIDs = array()
     $activeValidators = array() }

<div id="dialog-confirm">{'Are you sure?'|i18n( 'formmaker/admin' )}</div>

<div class="form-box-container">
    <form action={concat( '/formmaker/edit/', $id )|ezurl()} method="post" enctype="multipart/form-data" id="editform">
        
        {include uri="design:formmaker/buttons_top.tpl" show_attributes=cond( $id, true(), false() )} 
                        
        {if $id}
            <h2 id="formmaker-edit-header">{'Editing form'|i18n( 'formmaker/admin' )} `{$form_name|wash()}`</h2>
            <p class="formmaker-language-information">
                {'Please note that label values, which you need to add to each attribute, are processed by eZPublish translation system (so to add new translation, just add translations into the language file).'|i18n( 'formmaker/admin' )}
            </p>
        {else}
            <h2 id="formmaker-edit-header">New form</h2>
        {/if}                        
        
        <input type="hidden" id="list-url" value={'formmaker/list'|ezurl( 'double', 'full' )}/>
        <h3>
            Definition
            {if $id}
                <span class="show-hide-definition">
                    <a class="show-definition" href="#">{'show'|i18n( 'formmaker/admin' )}</a>
                    <input type="hidden" id="show-definition" value="{'show'|i18n( 'formmaker/admin' )}"/>
                    <input type="hidden" id="hide-definition" value="{'hide'|i18n( 'formmaker/admin' )}"/>
                </span>
            {/if}        
        </h3>
        <hr/>

        <div id="content-sub-items-list" class="content-navigation-childlist yui-dt {if $id}hide{/if}">
            <div class="form_error">{$error_message|wash()}</div>
            {foreach $form_elements as $identifier => $element}
                <div class="{if is_set( $element.css )}{$element.css|wash()}{/if} formmaker-attribute">
                    {switch match=$element.type}
                        {case match='text'}
                            <label>
                                {if is_set($element.label)}
                                    <span class="attribute-label">{$element.label|wash()|i18n( 'formmaker/admin' )}</span>
                                    {if $element.required}<span class="form_attribute_required"> *</span>{/if}<br/>
                                {/if}
                                <input type="text" name="{$identifier}" value="{$element.value|wash()}" {if $element.required}required{/if}/>
                            </label>
                        {/case}
                        {case match='checkbox'}
                            <label>
                                <input type="checkbox" name="{$identifier}" {if $element.value}checked="checked"{/if}/>
                                <span class="attribute-label">{$element.label|wash()|i18n( 'formmaker/admin' )}</span>
                                {if $element.required}<span class="form_attribute_required"> *</span>{/if}<br/>
                            </label>
                        {/case}
                        {case match='textarea'}
                            <label>
                                {if is_set($element.label)}
                                    <span class="attribute-label">{$element.label|wash()|i18n( 'formmaker/admin' )}</span>
                                    {if $element.required}<span class="form_attribute_required"> *</span>{/if}<br/>
                                {/if}
                                <textarea name="{$identifier}" {if $element.required}required{/if}/>{$element.value|wash()}</textarea>
                            </label>
                        {/case}                       
                    {/switch}
                </div>
           {/foreach}
        </div>

        {if $id|not()} {* if this is a new form *}
            {include uri="design:formmaker/buttons_bottom.tpl" show_attributes=false()} 
        {else}
            <h3>Attributes</h3>
            <hr/>
            <input type="hidden" name="definition_id" value="{$id}" />
            <input type="hidden" id="separator-id" value="{$separator_id}"/>
            <input type="hidden" id="custom-regex-validator-id" value="{$validator_custom_regex_id}" />
            <input type="hidden" id="email-validator-id" value="{$validator_email_id}" />
            <input type="hidden" id="form-dynamic-validators" value="{$validator_email_id},{$validator_custom_regex_id}" />

            <div class="sortable-attributes">
                {foreach $form_attributes as $attribute}
                    {include uri=concat( 'design:formmaker/types/', $attribute.type_data.template ) data=$attribute validator_email_id=$validator_email_id
                             input_id=$attribute.id input=$attribute.type_data validator_custom_regex_id=$validator_custom_regex_id}
                {/foreach}
            </div>                    
            {include uri="design:formmaker/buttons_bottom.tpl" show_attributes=true()}     
        {/if}
    </form>
</div>
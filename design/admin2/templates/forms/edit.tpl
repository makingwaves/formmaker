{* Template renders create/edit page for the forms, parameters:
- $error_message - string conatining error message comming from validation
- $form_elements - array containing items if adding-new-form form
- $form_attributes - array of attribute objects
- $id - edited form id
- $form_name
- $separator_id - integer, is defined in class constant formTypes::SEPARATOR_ID
- $validator_email_id - integer, is defined in class constant formValidators::EMAIL_ID
- $input_types - array of all available input types *}

{* jquery UI *}
{ezscript_require(array('ezjsc::jqueryUI'))}
{ezcss_require( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css' )}

{ezscript_require(array( 'edit.js' ) )}
{ezcss_require(array( 'style.css') )}

{def $selected = ''
     $activeValidatorsIDs = array()
     $activeValidators = array() }

<div id="dialog-confirm">{'Are you sure?'|i18n( 'extension/formmaker/admin' )}</div>

{if $id}
    <h2>{'Editing form'|i18n( 'extension/formmaker/admin' )} `{$form_name}`</h2>
    <p class="formmaker-language-information">
        {'Please note that label values, which you need to add to each attribute, are processed by eZPublish translation system (so to add new translation, just add translations into the language file).'|i18n( 'extension/formmaker/admin' )}
    </p>
{else}
    <h2>New form</h2>
{/if}

<div class="form-box-container">
    <form action={concat( '/formmaker/edit/', $id )|ezurl()} method="post" enctype="multipart/form-data" id="form-editor">
        <h3>Definition</h3>
        <hr/>

        <div id="content-sub-items-list" class="content-navigation-childlist yui-dt">
            <div class="form_error">{$error_message}</div>
            {foreach $form_elements as $identifier => $element}
                <div class="{if is_set( $element.css )}{$element.css}{/if} formmaker-attribute">
                    {switch match=$element.type}
                        {case match='text'}
                            <label>
                                {if is_set($element.label)}
                                    <span class="attribute-label">{$element.label}</span>{if $element.required}<span class="form_attribute_required"> *</span>{/if}<br/>
                                {/if}
                                <input type="text" name="{$identifier}" value="{$element.value}" {if $element.required}required{/if}/>
                            </label>
                        {/case}
                        {case match='checkbox'}
                            <label>
                                <input type="checkbox" name="{$identifier}" {if $element.value}checked="checked"{/if}/>
                                <span class="attribute-label">{$element.label|i18n( 'extension/formmaker/admin' )}</span>{if $element.required}<span class="form_attribute_required"> *</span>{/if}<br/>
                            </label>
                        {/case}
                    {/switch}
                </div>
           {/foreach}
        </div>

        {if $id|not()} {* if this is a new form *}
            <div class="controlbar" id="controlbar-top">
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="button-right">
                            <input type="submit" value="Save" name="SubmitButton" class="defaultbutton">
                        </div>
                        <div class="float-break"></div>
                    </div>
                </div>
            </div>
        {else}
            <h3>Attributes</h3>
            <hr/>
            <input type="hidden" name="definition_id" value="{$id}" />
            <input type="hidden" id="separator-id" value="{$separator_id}"/>
            <input type="hidden" id="validator-email-id" value="{$validator_email_id}" />

            <div class="sortable-attributes">
                {foreach $form_attributes as $attribute}
                    {include uri=concat( 'design:forms/types/', $attribute.type_data.template ) data=$attribute validator_email_id=$validator_email_id
                             input_id=$attribute.id input=$attribute.type_data}
                {/foreach}
            </div>                    

            <input type="button" class="button" name="add_field" value="{'Add field'|i18n( 'extension/formmaker/admin' )}"/>
            <select name="new-field-type">
                {foreach $input_types as $field}
                    <option value="{$field.id}">{$field.name}</option>
                {/foreach}
            </select>    

            <div class="clear"></div>

            <div class="controlbar" id="controlbar-top">
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="button-right">
                            <input type="submit" value="Save" name="SubmitButton" class="defaultbutton">
                        </div>
                        <div class="float-break"></div>
                    </div>
                </div>
            </div>

        {/if}
    </form>
</div>
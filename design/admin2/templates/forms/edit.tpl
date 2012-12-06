{* Template renders create/edit page for the forms, parameters:
- $error_message - string conatining error message comming from validation
- $form_elements - array containing items if adding-new-form form
- $form_attributes - array of attribute objects
- $id - edited form id
- $form_name
- $input_types - array of all available input types *}

{* jquery UI *}
{ezscript_require(array('ezjsc::jqueryUI'))}
{ezcss_require( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css' )}

{ezscript_require(array( 'edit.js' ) )}
{ezcss_require(array( 'style.css') )}

{def $selected = ''
     $activeValidatorsIDs = array()
     $activeValidators = array() }

<div id="dialog-confirm" title="Remove attribute">
    <p><span class="ui-icon ui-icon-alert"></span>{'Are you sure?'|i18n( 'extension/formmaker/admin' )}</p>
</div>

{if $id}
    <h2>{'Editing form'|i18n( 'extension/formmaker/admin' )} `{$form_name}`</h2>
    <div class="formmaker-language-information">
        {'Please note that label values, which you need to add to each attribute, are processed by eZPublish translation system (so to add new translation, just add translations into the language file).'|i18n( 'extension/formmaker/admin' )}
    </div>
{else}
    <h2>New form</h2>
{/if}

<div class="block">
    <div class="left">

        {if and( not($form_attributes), $id )} {* if form has any attributes... *}
        <p class="info" id="noneAttributesWarning">
            {'This form does not have any fields. Add some!'|i18n( 'extension/formmaker/admin' )}
        </p>
        {/if}

        <form action={concat( '/formmaker/edit/', $id )|ezurl()} method="post" enctype="multipart/form-data" id="tagadd" name="tagadd">
            <h3>Definition</h3>
            <hr/>

            <div id="content-sub-items-list" class="content-navigation-childlist yui-dt">
                <div class="mwform_error">{$error_message}</div>
                {foreach $form_elements as $identifier => $element}
                    <div class="formmaker-attribute">
                        <label>
                            <span class="attribute-label">{$element.label}</span>{if $element.required}<span class="mwform_attribute_required"> *</span>{/if}<br/>
                            <input type="text" name="{$identifier}" value="{$element.value}" {if $element.required}required{/if}/>
                        </label>
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

                <div class="sortable-attributes">
                    {foreach $form_attributes as $attribute}
                        {include uri=concat( 'design:forms/types/', $attribute.type_data.template ) data=$attribute
                                 input_id=$attribute.id input=$attribute.type_data}
                    {/foreach}
                </div>
                
                <input type="button" class="button" name="add_field" value="{'Add field'|i18n( 'extension/formmaker/admin' )}"/>
                <select name="new_field_type">
                    {foreach $input_types as $field}
                        <option class="form-{$field.id}" value="{$field.id}">{$field.name}</option>
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
</div>
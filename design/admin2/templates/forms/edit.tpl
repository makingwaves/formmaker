{* jquery UI *}
{ezscript_require(array('ezjsc::jqueryUI'))}
{ezcss_require( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css' )}

{ezscript_require(array( 'edit.js' ) )}
{ezcss_require(array( 'style.css') )}

{def $fields = ezini('AvailableTypes', 'AvailableTypes', 'content.ini')
     $selected = ''}

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

{def $action = concat('/formmaker/edit/', $id)}
<form action={$action|ezurl()} method="post" enctype="multipart/form-data" id="tagadd" name="tagadd">

<h3>Definition</h3>

<hr/>
    
<div id="content-sub-items-list" class="content-navigation-childlist yui-dt">
    <div class="mwform_error">{$error_message}</div>
    {foreach $form_elements as $identifier => $element}
        <div class="formmaker-attribute">
            <label>
                <span class="attribute-label">{$element.label}</span>{if $element.required}<span class="mwform_attribute_required"> *</span>{/if}<br/>
                <input type="text" name="{$identifier}" value="{$element.value}"/>
            </label>
        </div>
    {/foreach}
</div>

{if and( $id|not() )} {* if this is a new form *}
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

{if $id|not()}
</form>
{/if}


{if $id}
<h3>Attributes</h3>
<hr/>
<input type="hidden" name="definition_id" value="{$id}" />

<div class="sortable-attributes">
    {def $activeValidatorsIDs = array()}
    {def $activeValidators = array()}

    {foreach $form_attributes as $key => $item}

        {set $activeValidatorsIDs = array()}
        {set $activeValidators = array()}

        {foreach $item.validators as $validator}
        {set $activeValidators = $activeValidators|append($validator)}
        {/foreach}

        {foreach $activeValidators as $activeValidator}
            {set $activeValidatorsIDs = $activeValidatorsIDs|append($activeValidator.validator_id)}
        {/foreach}

        {if eq($item.type, 'text')}
            {include uri='design:forms/types/text.tpl' disabled=false()}
        {elseif eq($item.type, 'textarea')}
            {include uri='design:forms/types/textarea.tpl' disabled=false()}
        {elseif eq($item.type, 'checkbox')}
            {include uri='design:forms/types/checkbox.tpl' disabled=false()}
        {/if}

    {/foreach}

    {* hidden - used by js *}
    {include uri='design:forms/types/text.tpl' disabled=true()}
    {include uri='design:forms/types/checkbox.tpl' disabled=true()}
    {include uri='design:forms/types/textarea.tpl' disabled=true()}
    {* /hidden - used by js *}    


    <a class="addField">{'Add field'|i18n( 'extension/formmaker/admin' )}</a>

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
</div>
</form>

</div>
</div>
{/if}

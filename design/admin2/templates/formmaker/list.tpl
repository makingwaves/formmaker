{def $remove_access = fetch( 'user', 'has_access_to', hash( 'module', 'formmaker', 'function', 'remove' ) )}

{ezcss_require( array( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', 'tablesorter.css', 'style.css' ) )} 
{ezscript_require( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}
{ezscript_require( array( 'jquery.tablesorter.js', 'list.js' ) )}

<div id="dialog-confirm">{"You're about to remove the form with all possible answers. Are you sure you want to do that?"|i18n( 'formmaker/admin' )}</div>

<div class="context-block tags-dashboard">
    <div class="box-header">
        <h1 class="context-title">{'List of forms'|i18n( 'formmaker/admin' )}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-content">
        <div class="block">
            <div class="form-box-container">
                <div id="content-sub-items-list" class="content-navigation-childlist">
                    <table border="0" cellspacing="1" cellpadding="0" class="tablesorter">
                        <thead>
                            <tr>
                                <th class="mwezform-list-name">{'Name'|i18n( 'formmaker/admin' )}</th>
                                <th>{'Created'|i18n( 'formmaker/admin' )}</th>    
                                <th>{'Author'|i18n( 'formmaker/admin' )}</th>                                 
                                <th style="width: 160px; text-align: center;">{'Actions'|i18n( 'formmaker/admin' )}</th>
                            </tr>
                        </thead>

                        <tbody>
                            {foreach $forms as $form}
                                <tr>
                                    <td>{$form.name|wash()}</td>
                                    <td>{$form.create_date}</td>            
                                    <td>{$form.user.contentobject.name}</td>                                       
                                    <td style="text-align: center;">
                                        <input type="hidden" name="form-id" value="{$form.id}" />
                                        <a class="formmaker_edit_form" href={concat('formmaker/edit/', $form.id)|ezurl()}>Edit</a>
                                        {if $remove_access}
                                            | <a class="formmaker_remove_form" href={concat('formmaker/remove/', $form.id)|ezurl()}>Remove</a>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}

                            {if not( $forms|count() )}
                                <tr><td class="formmaker_no_forms" colspan="4">{'There are no forms for now. Add some!'|i18n( 'formmaker/admin' )}</td></tr>
                            {/if}
                        </tbody>
                    </table>      
                </div>

                <div id="controlbar-top" class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="button-right">
                                <input class="defaultbutton" type="button" name="CreateButton" value="{"Create new form"|i18n( "formmaker/admin" )}" />
                                <input type="hidden" id="edit-url" value={'formmaker/edit'|ezurl( 'double', 'full' )}/>
                            </div>
                            <div class="float-break"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-break"></div>
        </div>
    </div>
</div>

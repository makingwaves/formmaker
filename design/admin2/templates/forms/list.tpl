{def $remove_access = fetch( 'user', 'has_access_to', hash( 'module', 'formmaker', 'function', 'remove' ) )}

{* jquery UI *}
{ezscript_require( array('ezjsc::jqueryUI')) }
{ezcss_require( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css' )}

{ezscript_require(array( 'edit.js' ) )}
{ezcss_require(array( 'style.css' ) )}

{* site specific JS *}
{ezscript_require(array( concat( 'list.js' ) ) )}
   

<div id="dialog-confirm" title="Remove form">
    <p><span class="ui-icon ui-icon-alert"></span>{'Are you sure?'|i18n( 'extension/formmaker/admin' )}</p>
</div>

<div class="context-block tags-dashboard">
    <div class="box-header">
        <h1 class="context-title">{'List forms'|i18n( 'extension/formmaker/admin' )}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-content">
        <div class="block">
            <div class="left">
                <div id="content-sub-items-list" class="content-navigation-childlist yui-dt">
                    <table>
                        <thead>
                            <tr class="yui-dt-first yui-dt-last">
                                <th id="yui-dt0-th-name" class="yui-dt0-col-name yui-dt-col-name yui-dt-resizeable" style="width: 20px">
                                    <div class="yui-dt-resizerliner">
                                        <div id="yui-dt0-th-name-liner" class="yui-dt-liner">
                                            <span class="yui-dt-label">{'ID'|i18n( 'extension/formmaker/admin' )}</span>
                                        </div>
                                        <div id="yui-dt0-th-name-resizer" class="yui-dt-resizer" style="left: auto; right: 0px; top: auto; bottom: 0px; height: 23px;"></div>
                                    </div>
                                </th>

                                <th id="yui-dt0-th-name" class="mwezform-list-name yui-dt0-col-name yui-dt-col-name yui-dt-resizeable">
                                    <div class="yui-dt-resizerliner">
                                        <div id="yui-dt0-th-name-liner" class="yui-dt-liner">
                                            <span class="yui-dt-label">{'Name'|i18n( 'extension/formmaker/admin' )}</span>
                                        </div>
                                        <div id="yui-dt0-th-name-resizer" class="yui-dt-resizer" style="left: auto; right: 0px; top: auto; bottom: 0px; height: 23px;"></div>
                                    </div>
                                </th>
                                
                                <th id="yui-dt0-th-name" class="yui-dt0-col-name yui-dt-col-name yui-dt-resizeable">
                                    <div class="yui-dt-resizerliner">
                                        <div id="yui-dt0-th-name-liner" class="yui-dt-liner">
                                            <span class="yui-dt-label">{'Created'|i18n( 'extension/formmaker/admin' )}</span>
                                        </div>
                                        <div id="yui-dt0-th-name-resizer" class="yui-dt-resizer" style="left: auto; right: 0px; top: auto; bottom: 0px; height: 23px;"></div>
                                    </div>
                                </th>                                

                                <th id="yui-dt0-th-name" class="yui-dt0-col-name yui-dt-col-name yui-dt-resizeable" style="width: 160px; text-align: center;">
                                    <div class="yui-dt-resizerliner">
                                        <div id="yui-dt0-th-name-liner" class="yui-dt-liner">
                                            <span class="yui-dt-label">{'Actions'|i18n( 'extension/formmaker/admin' )}</span>
                                        </div>
                                        <div id="yui-dt0-th-name-resizer" class="yui-dt-resizer" style="left: auto; right: 0px; top: auto; bottom: 0px; height: 23px;"></div>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="yui-dt-message">
                            {foreach $forms as $form}
                                <tr class="yui-dt-first yui-dt-last">
                                    <td class="yui-dt-empty">
                                        <div class="yui-dt-liner">{$form.id}</div>
                                    </td>
                                    <td class="yui-dt-empty">
                                        <div class="yui-dt-liner">{$form.name}</div>
                                    </td>
                                    <td class="yui-dt-empty">
                                        <div class="yui-dt-liner">{$form.create_date}</div>
                                    </td>                                    
                                    <td class="yui-dt-empty" style="text-align: center;">
                                        <div class="yui-dt-liner">
                                            <a class="formmaker_edit_form" href={concat('formmaker/edit/', $form.id)|ezurl()}>Edit</a>
                                                {if $remove_access}
                                                |
                                                <a class="formmaker_remove_form" href={concat('formmaker/remove/', $form.id)|ezurl()}>Remove</a>
                                            {/if}
                                        </div>
                                    </td>
                                </tr>
                            {/foreach}

                            {if not( $forms|count() )}
                                <tr><td class="formmaker_no_forms" colspan="3">{'There are no forms for now. Add some!'|i18n( 'extension/formmaker/admin' )}</td></tr>
                            {/if}
                        </tbody>
                    </table>      
                </div>

                <div id="controlbar-top" class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="button-right">
                                <form name="tagadd" id="tagadd" style="float:left;" enctype="multipart/form-data" method="post" action={'formmaker/edit'|ezurl}>
                                    <input class="defaultbutton" type="submit" name="SubmitButton" value="{"Create new form"|i18n( "extension/formmaker/admin" )}" />
                                </form>
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

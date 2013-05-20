
{ezcss_require( array( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', 'tablesorter.css', 'style.css' ) )}
{ezscript_load( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}
{ezscript_require( array( 'jquery.tablesorter.js' ) )}

{def $limit = 5
     $answers = fetch( 'formmaker', 'answers', hash( 'form_id', $view_parameters.form_id,
                                                     'limit', $limit,
                                                     'offset', $view_parameters.offset ) )
     $answers_count = fetch( 'formmaker', 'answers_count', hash( 'form_id', $view_parameters.form_id ) )}

<div class="context-block tags-dashboard">
    <div class="box-header">
        <h1 class="context-title">{'Answers'|i18n( 'formmaker/admin' )}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-bc">
        <div class="box-content">
            <table border="0" cellspacing="1" cellpadding="0" class="tablesorter">
                <thead>
                    <tr>
                        <th class="mwezform-list-name">{'Form name'|i18n( 'formmaker/admin' )}</th>
                        <th>{'Answer date'|i18n( 'formmaker/admin' )}</th>
                        <th>{'Author'|i18n( 'formmaker/admin' )}</th>
                    </tr>
                </thead>

                <tbody>
                    {foreach $answers as $answer sequence array('odd', 'even') as $row_class}
                        <tr>
                            <td>test</td>
                            <td>{$answer.answer_date}</td>
                            <td>test</td>
                        </tr>
                    {/foreach}

                    {if not( $answers|count() )}
                        <tr><td class="formmaker_no_forms" colspan="3">{'There are no answers for now.'|i18n( 'formmaker/admin' )}</td></tr>
                    {/if}
                </tbody>
            </table>
        </div>
    </div>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/formmaker/answers'
         view_parameters=$view_parameters
         item_count=$answers_count
         item_limit=$limit}
</div>
{* Template displays a list on answers in admin interface. Params
- $view_parameters array
*}

{ezcss_require( array( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', 'tablesorter.css', 'style.css' ) )}
{ezscript_load( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}
{ezscript_require( array( 'jquery.tablesorter.js', 'answers.js' ) )}

{def $limit = 20
     $answers = fetch( 'formmaker', 'answers', hash( 'form_id', $view_parameters.form_id,
                                                     'limit', $limit,
                                                     'offset', $view_parameters.offset ) )
     $answers_count = fetch( 'formmaker', 'answers_count', hash( 'form_id', $view_parameters.form_id ) )
     $forms = fetch( 'formmaker', 'answers_forms', hash( 'only_collectors', true() ) )}

<div class="context-block tags-dashboard">
    <div class="box-header">
        <h1 class="context-title">{'Answers'|i18n( 'formmaker/admin' )}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-bc formmaker-set-answers-container">
        <form action={concat( 'formmaker/answers/(offset)/', $view_parameters.offset )|ezurl( 'duble', 'full' )}>
            {'Select form:'|i18n( 'formmaker/admin' )}
            <select name="form_id">
                <option value="0">{'- all forms -'|i18n( 'formmaker/admin' )}</option>
                {foreach $forms as $form}
                    <option {if $form.id|eq( $view_parameters.form_id )}selected{/if} value="{$form.id}">{$form.name|wash()}</option>
                {/foreach}
            </select>
            <input id="formmaker-set-answers-form" class="button" type="button" value="{'Set'|i18n( 'formmaker/admin' )}"/>
        </form>
    </div>

    <div class="box-bc">
        <div class="box-content">
            <table border="0" cellspacing="1" cellpadding="0" class="tablesorter">
                <thead>
                    <tr>
                        <th class="formmaker-answers-count-column"></th>
                        <th>{'Answer date'|i18n( 'formmaker/admin' )}</th>
                        <th>{'Form name'|i18n( 'formmaker/admin' )}</th>
                        <th>{'Author'|i18n( 'formmaker/admin' )}</th>
                    </tr>
                </thead>

                <tbody>
                    {foreach $answers as $i => $answer}
                        <tr>
                            <td class="formmaker-answers-count-column">{$i|inc()|sum( $view_parameters.offset )}</td>
                            <td>{$answer.answer_date}</td>
                            <td>{$answer.form_data.name|wash()}</td>
                            <td>{$answer.user.contentobject.name}</td>
                        </tr>
                    {/foreach}

                    {if not( $answers|count() )}
                        <tr><td class="formmaker_no_forms" colspan="4">{'There are no answers for now.'|i18n( 'formmaker/admin' )}</td></tr>
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

{* Rendering single answer view, params:
- $answer
*}

{ezcss_require( array( 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', 'tablesorter.css', 'style.css' ) )}
{ezscript_require( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' )}
{ezscript_require( array( 'jquery.tablesorter.js' ) )}

<div class="context-block tags-dashboard">
    <div class="box-header">
        <h1 class="context-title">
            {'"%form_name" answer, %date'|i18n( 'formmaker/admin', '', hash( '%form_name', $answer.form_data.name, '%date', $answer.answer_date ) )}
        </h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-bc">
        <div class="box-content">
            <table border="0" cellspacing="1" cellpadding="0" class="tablesorter">
                <thead>
                    <tr>
                        <th class="formmaker-answer-query-column">{'Query'|i18n( 'formmaker/admin' )}</th>
                        <th>{'Answer'|i18n( 'formmaker/admin' )}</th>
                        <th class="formmaker-answer-type-column">{'Field type'|i18n( 'formmaker/admin' )}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>{'Author name'|i18n( 'formmaker/admin' )}</td>
                        <td>{$answer.user.contentobject.name}</td>
                        <td></td>
                    </tr>
                    {foreach $answer.attributes as $attribute}
                        <tr>
                            <td>{$attribute.structure.label|wash()}</td>
                            <td>{$attribute.answer|wash()}</td>
                            <td>{$attribute.structure.type_data.name|wash()}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
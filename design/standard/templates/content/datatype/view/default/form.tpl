{* Template renders view of form attribute *}

{def $form_data         = fetch( 'formmaker', 'data', hash( 'form_id', $node.data_map.form.content.form_id,
                                                            'view', first_set( $node.data_map.view_type.contentclass_attribute.content.options[$node.data_map.view_type.content.0].name, 'default' ) ) )
     $errors            = $form_data.validation
     $form_definition   = $form_data.definition
     $form_attributes   = $form_data.attributes
     $counted_validators= $form_data.counted_validators
     $attr_required     = false()
     $current_page      = $form_data.current_page
     $header_text       = $form_definition.name
     $pages_count       = $form_data.all_pages|count()
     $send_button       = cond( $pages_count|eq( $form_data.current_page|inc( 1 ) ), 'Send', 'Next' )
     $send_name         = cond( $pages_count|eq( $form_data.current_page|inc( 1 ) ), 'form-send', 'form-next' ) }

{* including CSS file *}
{ezcss_load( array( 'formmaker.css', 'http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css', 'select2.css' ) )}
{* Including JS files *}
{ezscript_load( array( 'http://code.jquery.com/ui/1.9.2/jquery-ui.js' ) )}
{ezscript_require( array( 'ezjsc::jqueryio', 'select2.min.js', 'jquery.functions.js', 'jquery.validation.js' ) )}

{include uri="design:formmaker/form_steps.tpl" form_data=$form_data all_pages=$form_data.all_pages form_definition=$form_definition current_page=$current_page}

{if is_set( $form_data.summary_page )}
    {set $header_text = $form_definition.summary_label}
{elseif is_set( $form_data.success )}
    {set $header_text = $form_definition.receipt_label}
{/if}

<div class="form-container">

    <h1>{$header_text|wash()|i18n( 'formmaker/front' )}</h1>

    <form id="mwezform" class="{$form_definition.css_class}" method="POST" action={$node.url_alias|ezurl()} {if $form_definition.multipart}enctype="multipart/form-data"{/if}>

        <input type="hidden" name="form_id" value="{$form_definition.id}"/>
        <input type="hidden" name="node_id" value="{$node.node_id}"/>
        <input type="hidden" name="current_page" value="{$current_page}" />
        <input type="hidden" id="date-validator" value="{$form_data.date_validator}" />
        <input type="hidden" id="form-datepicker-format" value="{$form_definition.datepicker_format}" />

        {if is_set( $form_data.summary_page )}
            {* $form_data.summary_page contains rendered template summary_page.tpl *}
            {$form_data.summary_page}
        {elseif is_set( $form_data.success )}
            {* $form_data.success contains rendered template form_processed.tpl *}
            {$form_data.success}
        {else}
            {include uri="design:formmaker/form_error.tpl" errors=$errors attribute_id=0}

            {foreach $form_attributes as $attribute}
                {set $attr_required = fetch( 'formmaker', 'is_attrib_required', hash( 'attribute_id', $attribute.id ) )}
                <div class="form-element-container" id="form_element_{$attribute.id}">
                    <div class="{if and( is_set( $counted_validators[$attribute.id] ), $counted_validators[$attribute.id] )}validate-it{/if} form_attribute_content">
                        {include uri=concat('design:formmaker/form_attributes/', $attribute.type_data.template ) attribute=$attribute is_required=$attr_required year_validator=$form_data.date_year_validator}
                    </div>
                    <div class="form_error_content">
                        <span class="form_notification">{include uri="design:formmaker/form_error.tpl" errors=$errors attribute_id=$attribute.id}</span>
                    </div>
                </div>
            {/foreach}
            {include uri="design:formmaker/form_buttons.tpl" send_name=$send_name send_value=$send_button back_value='Back'
                     back_display=cond( $form_data.current_page|gt( 0 ), true(), false() ) }
        {/if}
    </form>
</div>
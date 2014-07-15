{* Template to edit Form object attribute *}

<select id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}"
        class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}"
        name="{$attribute_base}_form_{$attribute.id}">

    <option value="">{'Not specified'|i18n( 'design/standard/content/datatype' )}</option>

    {if gt( $attribute.content.forms_list|count(), 0 )}
        {foreach $attribute.content.forms_list as $form}
            <option {if eq( $form.id, $attribute.content.form_id )}selected="selected"{/if} value="{$form.id|wash()}">{$form.name|wash()}</option>
        {/foreach}
    {/if}
</select>
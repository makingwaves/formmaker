{* Template takes following parameters:
- $attribute - array containing attribute data
- $is_required - boolean
*}

{def $css_class = 'form_element' 
     $default_value = '' }
     
{if ezini( 'AdditionalElements', 'css', 'formmaker.ini' )|eq( 'enabled' )}
    {set $css_class = concat( $css_class, ' ', $attribute.css|wash() ) }
{/if}

{if ezini( 'AdditionalElements', 'description', 'formmaker.ini' )|eq( 'enabled' )}
    <p class="form-element-description">{$attribute.description}</p>
{/if}

<label for="form_{$attribute.type_id}_{$attribute.id}">
    {$attribute.label|wash()|i18n( 'formmaker/front' )}
    {include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label>



{if $attribute.default_value)  } {* if there's image already*}

    {if is_image($attribute.default_value)}
        {def $thumb = $attribute.default_value|explode('.') }
        {set $thumb = concat($thumb.0, '_thumb.', $thumb.1)}
        <img src={$thumb|ezroot()}/>
        {undef $thumb}
    {else}
        {def $extension = $attribute.default_value|explode('.')}
        <span>{$attribute.label|i18n( 'formmaker/front' )}:
        <a target="_blank" href="/{$attribute.default_value}">{$extension.1}</a>
        <br/>
        {undef $extension}
    {/if}

    <input id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="hidden" value="{$attribute.default_value}"/>

    <a class="upload-new-file">{'Upload new file'|i18n( 'formmaker/front' )}</a>
    <input class="hidden {$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="file" value="{$attribute.default_value}"/>

{else}
    <input class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="file" value="{$attribute.default_value}"/>
{/if}
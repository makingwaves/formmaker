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
    {$attribute.label}
{include uri="design:form_attributes/parts/required.tpl" is_required=$is_required}
</label> <br/>

{if $attribute.default_value} {* if there's image already*}
	{def $thumb = $attribute.default_value|explode('.jpg') }
	{set $thumb = concat($thumb.0, '_thumb.jpg')}
	<img src="/{$thumb}" /> <br/>
	<input id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="hidden" value="{$attribute.default_value}"/>

	<a class="upload-new-file" style="cursor: pointer;">Upload new file</a> <br/>
	<input style="display: none;" class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="file" value="{$attribute.default_value}"/>

{else}
	<input class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="file" value="{$attribute.default_value}"/>
{/if}

{*
<input class="{$css_class}" id="form_{$attribute.type_id}_{$attribute.id}" name="field_{$attribute.type_id}_{$attribute.id}" type="file" value="{$attribute.default_value}"/>
*}

{literal}
<script>
$('a.upload-new-file').click(function() {
	$(this).prev().remove();
	$(this).prev().prev().remove();
	$(this).next().next().show();

});
</script>
{/literal}
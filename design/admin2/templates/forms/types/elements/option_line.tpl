{* Template for rendering single option element. Params:
- $input_id - int, id of an attribute
- $option_id - int, id of an attribute option
- $default_value - int, represents the id of default attribue option
- $label - string *}

<li>
    <input type="text" name="formelement_{$input_id}[options][{$option_id}]" value="{$label}" />
    <span class="default-radio" >
        {'Default'|i18n( 'extension/formmaker/admin' )} 
        <input type="checkbox" option_id="{$option_id}" connected="formelement_{$input_id}[default]" {if eq( $default_value, $option_id )}checked="checked"{/if} />
    </span>
    <a class="option-move-down"><img src={'button-move_down.gif'|ezimage()}/></a>
    <a class="option-move-up"><img src={'button-move_up.gif'|ezimage()}/></a>
    <a class="option-remove"><img src={'delete.png'|ezimage()} /></a>
</li>
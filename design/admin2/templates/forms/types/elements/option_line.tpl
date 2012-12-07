{* Template for rendering single option element. Params:
- $input_id 
- $option_id
- $label *}

<li>
    <input type="text" name="formelement_{$input_id}[options][{$option_id}]" value="{$label}" />
    <a class="option-move-down"><img src={'button-move_down.gif'|ezimage()}/></a>
    <a class="option-move-up"><img src={'button-move_up.gif'|ezimage()}/></a>
    <a class="option-remove"><img src={'delete.png'|ezimage()} /></a>
</li>
{* Template renders form buttons visible on form pages and summary page
- $send_name - string
- $send_value - string
- $back_display - boolean
- $back_value - string
*}

<div class="form-footer">
    <div class="form-footer-back">
        {if $back_display}
            <input type="submit" value="{$back_value|i18n( 'formmaker/front' )}" name="form-back"/>
        {/if}
    </div>
    <div class="form-footer-next">
        <input type="submit" name="{$send_name}" value="{$send_value|i18n( 'formmaker/front' )}"/>
        <input type="hidden" name="validation" value="false"/>
    </div>
</div>
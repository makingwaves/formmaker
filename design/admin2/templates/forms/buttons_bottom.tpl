{* Template renders edit form buttons displayed on the botton. Params:
- $show_attributes, boolean *}

<div class="formmaker-bottom-buttons">
    <div class="buttons-left">
        {if $show_attributes}
            <input type="submit" value="{'Save and exit'|i18n( 'formmaker/admin' )}" name="SaveExitButton" class="defaultbutton">
            <input type="submit" class="button" name="SaveButton" value="{'Save'|i18n( 'formmaker/admin' )}"/>
        {else}
            <input type="submit" value="{'Save'|i18n( 'formmaker/admin' )}" name="SaveButton" class="defaultbutton">
        {/if}
        <input type="button" class="button" name="CancelButton" value="{'Cancel'|i18n( 'formmaker/admin' )}"/>      
    </div>
    {if $show_attributes}
        <div class="buttons-right">
            <select name="new-field-type">
                {foreach $input_types as $field}
                    <option value="{$field.id}">{$field.name|wash()}</option>
                {/foreach}
            </select>       
            <input type="button" class="button" name="add_field" value="{'Add attribute'|i18n( 'formmaker/admin' )}"/>
        </div>
    {/if}
</div>
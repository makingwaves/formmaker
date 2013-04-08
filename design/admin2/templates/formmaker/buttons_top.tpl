{* Template renders edit form buttons displayed on the top. Params:
- $show_attributes, boolean *}

<div id="controlbar-top" class="controlbar controlbar-fixed">
    <div class="box-bc">
        <div class="box-ml">
            <div class="block">
                <div class="element">
                    {if $show_attributes}
                        <input type="submit" value="{'Save and exit'|i18n( 'formmaker/admin' )}" name="SaveExitButton" class="defaultbutton">
                        <input type="submit" class="button" name="SaveButton" value="{'Save'|i18n( 'formmaker/admin' )}"/> 
                    {else}
                        <input type="submit" value="{'Save'|i18n( 'formmaker/admin' )}" name="SaveButton" class="defaultbutton">
                    {/if}
                    <input type="button" class="button" name="CancelButton" value="{'Cancel'|i18n( 'formmaker/admin' )}"/>   
                </div>
                {if $show_attributes}
                    <div class="element">
                        <select name="new-field-type">
                            {foreach $input_types as $field}
                                <option value="{$field.id}">{$field.name|wash()}</option>
                            {/foreach}
                        </select>       
                        <input type="button" class="button" name="add_field" value="{'Add attribute'|i18n( 'formmaker/admin' )}"/>
                    </div>
                {/if}
                <div class="form-ajax-loader element"></div>
                <div class="float-break"></div>
            </div>
        </div>
    </div>
</div>
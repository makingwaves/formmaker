{* Template for rendering label element. If logged in user has no access to special functions identifier is readonly. Params:
- $input_id
- $identifier *}

{def $access = fetch( 'user', 'has_access_to', hash('module',   'formmaker',
                                                     'function', 'special',
                                                     'user_id',  $current_user.contentobject_id) )}
<div class="form-field-attribute">
    {'Identifier:'|i18n( 'formmaker/admin' )}
    <input type="text" value="{$identifier}" name="formelement_{$input_id}[identifier]"{if $access|not()} readonly {/if}/>
</div>
{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_messaging)}
        {if $edit_messaging.is_fetched}<span class="label label-info">ID #{$edit_messaging.id}</span>{/if}
    {/if}
</div>
{include file="content/parts/flag-selector.tpl" lang_list=$edit_messaging.languages default_lang=$edit_messaging.default_language_id}
<div class="edit-zone">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#accounts" aria-controls="accounts" role="tab" data-toggle="tab">{nabu_static key=tab_accounts}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            {if is_array($edit_messaging)}
                {strip}
                    <dl class="list-messages">
                        {if !array_key_exists('accounts', $edit_messaging) || count($edit_messaging.accounts) === 0}
                            {nabu_assign var=accounts_empty section=accounts_empty}
                            <dt class="text-danger">{$accounts_empty.translation.title}</dt>
                            <dd>{$accounts_empty.translation.content}<br><ul class="list-inline"><li><a href="#">{nabu_static key=btn_new_account}</a></li></ul></dd>
                        {/if}
                    </dl>
                {/strip}
            {/if}
        </div>
        <div class="tab-pane" role="tabpanel" id="accounts">
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            {nabu_open_modal type=success target=modal_new_repository anchor_text="<i class=\"fa fa-check\"></i>&nbsp;{nabu_static key=btn_save}"}
        </div>
    </div>
</div>

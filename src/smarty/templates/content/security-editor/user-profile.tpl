{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_user}
    {if $edit_user.validation_status==='F'}<span class="label label-warning">{nabu_static key=lbl_activation_pending}</span>
    {elseif $edit_user.validation_status==='T'}<span class="label label-success">{nabu_static key=lbl_active}</span>
    {elseif $edit_user.validation_status==='B'}<span class="label label-error">{nabu_static key=lbl_banned}</span>
    {/if}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    <ul id="main_tabs" class="nav nav-tabs" role="tablist" data-toggle="tab-persistence" data-persistence-id="{$edit_user.id}">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#sites" aria-controls="sites" role="tab" data-toggle="tab"><i class="fa fa-globe"></i>&nbsp;{nabu_static key=tab_sites}</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            {include file="content/security-editor/parts/user-edit-main.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="sites">
            {include file="content/security-editor/parts/user-edit-sites.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="config">
            {include file="content/security-editor/parts/user-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

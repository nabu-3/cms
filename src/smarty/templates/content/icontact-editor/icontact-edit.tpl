{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_icontact}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_icontact.languages default_lang=$nb_language.id}
    <ul id="main_tabs" class="nav nav-tabs" role="tablist" data-toggle="tab-persistence" data-persistence-id="{$edit_icontact.id}">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#status_types" aria-controls="status_types" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_status_types}</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            {*include file="content/icontact-editor/parts/icontact-edit-main.tpl"*}
        </div>
        <div class="tab-pane" role="tabpanel" id="languages">
            {include file="content/icontact-editor/parts/icontact-edit-languages.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="status_types">
            {include file="content/icontact-editor/parts/icontact-edit-status.tpl"}
        </div>
        <div class="tab-pane edit-container" role="tabpanel" id="config">
            {include file="content/icontact-editor/parts/icontact-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

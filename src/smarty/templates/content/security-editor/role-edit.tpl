{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_role}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_role.languages default_lang=$nb_site.default_language_id}
    <ul class="nav nav-tabs" role="tablist"data-toggle="tab-persistence" data-persistence-id="{$edit_role.id}">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Idiomas</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuración</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/security-editor/parts/role-edit-languages.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
            {include file="content/security-editor/parts/role-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

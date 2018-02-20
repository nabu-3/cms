{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_site}
    {if is_array($edit_site) && $edit_site.is_fetched && $edit_site.published==='T'}<span class="label label-success">Publicado</span>{else}<span class="label label-danger">Sin publicar</span>{/if}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages default_lang=$edit_site.default_language_id}
    <ul class="nav nav-tabs" role="tablist" data-toggle="tab-persistence" data-persistence-id="{$edit_site.id}">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#targets" aria-controls="targets" role="tab" data-toggle="tab"><i class="fa fa-book"></i>&nbsp;{nabu_static key=tab_end_points}</a></li>
        <li role="presentation"><a href="#sitemaps" aria-controls="sitemaps" role="tab" data-toggle="tab"><i class="fa fa-sitemap"></i>&nbsp;{nabu_static key=tab_site_maps}</a></li>
        <li role="presentation"><a href="#statics" aria-controls="statics" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i>&nbsp;{nabu_static key=tab_static_contents}</a></li>
        <li role="presentation"><a href="#messaging" aria-control="messaging" role="tab" data-toggle="tab"><i class="fa fa-envelope"></i>&nbsp;{nabu_static key=tab_messaging}</a></li>
        <li role="presentation"><a href="#roles" aria-controls="roles" role="tab" data-toggle="tab"><i class="fa fa-users"></i>&nbsp;{nabu_static key=tab_roles}</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/site-editor/parts/site-edit-languages.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="targets">
            {include file="content/site-editor/parts/site-edit-targets.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="sitemaps">
            {include file="content/site-editor/parts/site-map-tree.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="statics">
            {include file="content/site-editor/parts/site-edit-static-contents.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="messaging">
            {include file="content/site-editor/parts/site-edit-messaging.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="roles">
            {include file="content/site-editor/parts/site-edit-roles.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="config" data-toggle="toggable-lang">
            {include file="content/site-editor/parts/site-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>
{include file="content/site-editor/visual/target-editor.tpl"}
<div id="ve_remote_modal_container"></div>

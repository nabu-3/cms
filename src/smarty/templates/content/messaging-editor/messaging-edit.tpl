{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_messaging)}
        {if $edit_messaging.is_fetched}
            <span class="label label-info">ID #{$edit_messaging.id}</span>
            {if nb_isValidGUID($edit_messaging.hash)}<span class="label label-info">GUID {$edit_messaging.hash}</span>{/if}
            {if nb_isValidKey($edit_messaging.key)}<span class="label label-info">KEY {$edit_messaging.key}</span>{/if}
        {/if}
    {/if}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_messaging.languages default_lang=$edit_messaging.default_language_id}
    <ul id="main_tabs" class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#services" aria-controls="services" role="tab" data-toggle="tab"><i class="fa fa-cube"></i>&nbsp;{nabu_static key=tab_services}</a></li>
        <li role="presentation"><a href="#templates" aria-controls="templates" role="tab" data-toggle="tab"><i class="fa fa-file-text"></i>&nbsp;{nabu_static key=tab_templates}</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            {include file="content/messaging-editor/parts/messaging-edit-main.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="languages">
            {include file="content/messaging-editor/parts/messaging-edit-languages.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="services">
            {include file="content/messaging-editor/parts/messaging-edit-services.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="templates">
            {include file="content/messaging-editor/parts/messaging-edit-templates.tpl"}
        </div>
        <div class="tab-pane edit-container" role="tabpanel" id="config">
            {include file="content/messaging-editor/parts/messaging-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>
{include file="content/messaging-editor/modals/test-service.tpl"}

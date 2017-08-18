{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_catalog}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_catalog.languages default_lang=$edit_catalog.default_language_id}
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#taxonomy" aria-controls="taxonomy" role="tab" data-toggle="tab">{nabu_static key=tab_taxonomies}</a></li>
        <li role="presentation"><a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">{nabu_static key=tab_labels}</a></li>
        <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">{nabu_static key=tab_items}</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/catalog-editor/parts/catalog-edit-languages.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="taxonomy">
            {include file="content/catalog-editor/parts/catalog-edit-taxonomies.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="tags">
            {include file="content/catalog-editor/parts/catalog-edit-tags.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="items">
            {include file="content/catalog-editor/parts/catalog-edit-items.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

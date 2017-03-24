{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_commerce)}
        {if $edit_commerce.is_fetched}
            <span class="label label-info">ID #{$edit_commerce.id}</span>
            {if nb_isValidGUID($edit_commerce.hash)}<span class="label label-info">GUID {$edit_commerce.hash}</span>{/if}
            {if nb_isValidKey($edit_commerce.key)}<span class="label label-info">KEY {$edit_commerce.key}</span>{/if}
        {/if}
    {/if}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_commerce.languages default_lang=$edit_commerce.default_language_id}
    <ul id="main_tabs" class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#categories" aria-controls="categories" role="tab" data-toggle="tab"><i class="fa fa-th-large"></i>&nbsp;{nabu_static key=tab_categories}</a></li>
        <li role="presentation"><a href="#products" aria-controls="products" role="tab" data-toggle="tab"><i class="fa fa-shopping-bag"></i>&nbsp;{nabu_static key=tab_products}</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            {include file="content/commerce-editor/parts/commerce-edit-main.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="languages">
            {include file="content/commerce-editor/parts/commerce-edit-languages.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="categories">
            {include file="content/commerce-editor/parts/commerce-edit-categories.tpl"}
        </div>
        <div class="tab-pane" role="tabpanel" id="products">
            {include file="content/commerce-editor/parts/commerce-edit-products.tpl"}
        </div>
        <div class="tab-pane edit-container" role="tabpanel" id="config">
            {include file="content/commerce-editor/parts/commerce-edit-config.tpl"}
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

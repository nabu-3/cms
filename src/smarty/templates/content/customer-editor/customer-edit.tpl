{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {include file="content/parts/common-labels.tpl" data=$edit_customer}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_customer.languages default_lang=$edit_customer.default_language_id}
    <ul class="nav nav-tabs" role="tablist"data-toggle="tab-persistence" data-persistence-id="{$edit_customer.id}">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        {*<li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Idiomas</a></li>
        <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuración</a></li>*}
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
            {include file="content/customer-editor/parts/customer-edit-main.tpl"}
        </div>
        {*<div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/customer-editor/parts/customer-edit-languages.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="items">
            {include file="content/customer-editor/parts/customer-edit-items.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
        </div>*}
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

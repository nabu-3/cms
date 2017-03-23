{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_medioteca)}
        {if $edit_medioteca.is_fetched}
            <span class="label label-info">ID #{$edit_medioteca.id}</span>
            {if nb_isValidGUID($edit_medioteca.hash)}<span class="label label-info">GUID {$edit_medioteca.hash}</span>{/if}
            {if nb_isValidKey($edit_medioteca.key)}<span class="label label-info">KEY {$edit_medioteca.key}</span>{/if}
        {/if}
    {/if}
</div>
<div id="edit_zone" class="edit-zone" data-toggle="nabu-multiform">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_medioteca.languages default_lang=$edit_medioteca.default_language_id}
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Idiomas</a></li>
        <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuración</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/medioteca-editor/parts/medioteca-edit-languages.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="items">
            {include file="content/medioteca-editor/parts/medioteca-edit-items.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button" data-toggle="nabu-multiform-save"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>

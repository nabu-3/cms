{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_catalog)}
        {if $edit_catalog.is_fetched}<span class="label label-info">ID #{$edit_catalog.id}</span>{/if}
    {/if}
</div>
{include file="content/parts/flag-selector.tpl" lang_list=$edit_catalog.languages default_lang=$edit_catalog.default_language_id}
<div class="edit-zone">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Idiomas</a></li>
        <li role="presentation"><a href="#taxonomy" aria-controls="taxonomy" role="tab" data-toggle="tab">Taxonomía</a></li>
        <li role="presentation"><a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">Etiquetas</a></li>
        <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/catalog-editor/parts/language-list.tpl" data=$edit_catalog}
        </div>
        <div role="tabpanel" class="tab-pane" id="taxonomy">
            {include file="content/catalog-editor/parts/taxonomy-list.tpl" data=$edit_catalog}
        </div>
        <div role="tabpanel" class="tab-pane" id="tags">
        </div>
        <div role="tabpanel" class="tab-pane" id="items">
            {include file="content/catalog-editor/parts/items-tree.tpl"}
        </div>
    </div>
</div>

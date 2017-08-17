{nabu_model model="bootstrap-3.3.7"}
<div class="split-panels" data-toggle="nabu-split-panels" data-split-direction="horizontal" data-split-method="flex">
    <div class="split-content">
        {nabu_tree id=items_tree languages=$edit_catalog.languages
                   data=$edit_catalog.items field_id=id field_name=title field_childs=childs
                   template="content/catalog-editor/parts/catalog-edit-items-item.tpl"}
    </div>
    <div class="split-separator"><div class="split-separator-inner"></div></div>
    <div class="split-content" id="items_tree_editor">
        {include file="content/parts/myst.tpl"}
    </div>
</div>

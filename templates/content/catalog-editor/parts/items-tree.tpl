{nabu_model model="bootstrap-3.3.7"}
{nabu_tree class="col-sm-4" id=tree_sitemap languages=$edit_catalog.languages
           data=$edit_catalog.items field_id=id field_name=title field_childs=childs
           template="content/catalog-editor/parts/items-tree-item.tpl"}
<div class="col-sm-8" id="sitemap_edit">
    <div class="myst hide"><i class="fa fa-spin fa-refresh"></i></div>
    <div class="panel panel-info">Selecciona un elemento en el árbol de navegación para editarlo.</div>
    <div class="edit-container hide"></div>
</div>

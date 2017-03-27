{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_site)}
        {if $edit_site.is_fetched}
            <span class="label label-info">ID #{$edit_site.id}</span>
            {if nb_isValidGUID($edit_site.hash)}<span class="label label-info">GUID {$edit_site.hash}</span>{/if}
            {if nb_isValidKey($edit_site.key)}<span class="label label-info">KEY {$edit_site.key}</span>{/if}
            {if $edit_site.published==='T'}<span class="label label-success">Publicado</span>{else}<span class="label label-danger">Sin publicar</span>{/if}
        {/if}
    {/if}
</div>
<div class="edit-zone">
    {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages default_lang=$edit_site.default_language_id}
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;{nabu_static key=tab_languages}</a></li>
        <li role="presentation"><a href="#targets" aria-controls="targets" role="tab" data-toggle="tab">Entradas</a></li>
        <li role="presentation"><a href="#sitemaps" aria-controls="sitemaps" role="tab" data-toggle="tab">Site Maps</a></li>
        <li role="presentation"><a href="#statics" aria-controls="statics" role="tab" data-toggle="tab">Estáticos</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=tab_config}</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
            {include file="content/site-editor/parts/site-language-list.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="targets">
            {include file="content/site-editor/parts/site-target-list.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="sitemaps" data-toggle="toggable-lang">
            {include file="content/site-editor/parts/site-map-tree.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="statics">
            {include file="content/site-editor/parts/static-content-list.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
        </div>
    </div>
</div>

{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">{strip}
    {if is_array($edit_site)}
        {if $edit_site_target.is_fetched}
            <span class="label label-info">ID #{$edit_site_target.id}</span>
            <span class="label label-success">#{$edit_site_target.order}</span>
        {/if}
        {*if $edit_site.published==='T'}<span class="label label-success">Publicado</span>{else}<span class="label label-danger">Sin publicar</span>{/if*}
    {/if}
{/strip}</div>
{include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages default_lang=$edit_site.default_language_id}
<div id="edit_zone" class="edit-zone"{if isset($edit_site_target.id)} data-id="{$edit_site_target.id}"{/if} data-parent-id="{$edit_site.id}">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#published" aria-controls="published" role="tab" data-toggle="tab">Publicación</a></li>
        <li role="presentation"><a href="#page" aria-controls="page" role="tab" data-toggle="tab">Página</a></li>
        <li role="presentation"><a href="#sections" aria-controls="sections" role="tab" data-toggle="tab">Secciones</a></li>
        <li role="presentation"><a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">SEO</a></li>
        <li role="presentation"><a href="#ctas" aria-controls="ctas" role="tab" data-toggle="tab">Conexiones</a></li>
        <li role="presentation"><a href="#policies" aria-controls="policies" role="tab" data-toggle="tab">Seguridad</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuración</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane edit-container" id="published" data-toggle="toggable-lang">
            {include file="content/site-editor/parts/site-target-published-edit.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane edit-container" id="page" data-toggle="toggable-lang">
            {include file="content/site-editor/parts/site-target-page-edit.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="sections" data-toggle="toggable-lang">
            {include file="content/site-editor/parts/site-target-sections-edit.tpl"}
        </div>
        <div role="tabpanel" class="tab-pane" id="seo" data-toggle="toggable-lang">
        </div>
        <div role="tabpanel" class="tab-pane" id="ctas" data-toggle="toggable-lang">
        </div>
        <div role="tabpanel" class="tab-pane" id="policies" data-toggle="toggable-lang">
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
        </div>
    </div>
</div>

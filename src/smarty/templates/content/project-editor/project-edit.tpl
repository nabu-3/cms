{nabu_model model="bootstrap-3.3.7"}
<div class="opening">
    {$edit_project.translation.opening}
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="box box-default">
            <div class="box-heading">Versión del proyecto</div>
            <div class="box-body">
                {if !is_array($edit_project.versions) || count($edit_project.versions)===0}
                    <div class="alert alert-info">No hay ninguna versión configurada.</div>
                {elseif !is_numeric($edit_project.current_version_id) || !array_key_exists($edit_project.current_version_id, $edit_project.versions)}
                    <div class="alert alert-warning">El proyecto no tiene ninguna versión asignada de las que existen.</div>
                {else}
                    {assign var=current value=$edit_project.versions[$edit_project.current_version_id]}
                    <h3>Versión {$current.code}&nbsp;&minus;&nbsp;{$current.translation.name}</h3>
                    {$current.translation.description}
                {/if}
            </div>
            <div class="box-footer">
                <div class="btn-toolbar">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal_new_version">Nueva versión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="content/project-editor/modals/new-version.tpl"}

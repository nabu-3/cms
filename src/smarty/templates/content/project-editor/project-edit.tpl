{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api_project cta=api_project}
<!--{$api_project|print_r:true}-->
<div class="opening">
    {$edit_project.translation.opening}
</div>
<div id="edit_zone" class="edit-zone box-grid" data-toggle="nabu-multiform">
    <div class="row">
        <div class="col col-sm-4">
            <div class="box box-default">
                <div class="box-heading">Versión del proyecto</div>
                <div class="box-body">
                    {if !is_array($edit_project.versions) || count($edit_project.versions)===0}
                        <div class="alert alert-info">No hay ninguna versión configurada.</div>
                    {elseif !is_numeric($edit_project.current_version_id) || !array_key_exists($edit_project.current_version_id, $edit_project.versions)}
                        <div class="alert alert-warning">El proyecto no tiene ninguna versión asignada de las que existen.</div>
                        {nabu_form layout="inline" method="ajax-post" action=$api_project.translations[$nb_site.api_language_id].final_url|sprintf:$edit_project.id}
                            {nabu_form_select from=$edit_project field=current_version_id options=$edit_project.versions options_name="name" label="Nueva versión"}
                            {nabu_form_command type=submit anchor_text="Guardar" class="btn btn-success"}
                        {/nabu_form}
                    {else}
                        {assign var=current value=$edit_project.versions[$edit_project.current_version_id]}
                        <h3>Versión {$current.code}&nbsp;&minus;&nbsp;{$current.translation.name}</h3>
                        {$current.translation.description}
                        {nabu_form layout="inline" method="ajax-post" action=$api_project.translations[$nb_site.api_language_id].final_url|sprintf:$edit_project.id}
                            {nabu_form_select from=$edit_project field=current_version_id options=$edit_project.versions options_name="name" label="Cambiar versión"}
                            {nabu_form_command type=submit anchor_text="Guardar" class="btn btn-success"}
                        {/nabu_form}
                    {/if}
                </div>
                <div class="box-footer">
                    <div class="btn-toolbar">
                        {if is_numeric($edit_project.current_version_id) && is_array($edit_project.versions) && array_key_exists($edit_project.current_version_id, $edit_project.versions)}
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal_send_version">Enviar test</button>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal_send_emailing">Enviar e-Mailing</button>
                            </div>
                        {/if}
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal_new_version">Nueva versión</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="content/project-editor/modals/new-version.tpl"}
{include file="content/project-editor/modals/send-email.tpl"}

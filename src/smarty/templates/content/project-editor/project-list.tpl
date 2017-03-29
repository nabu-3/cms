{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=without_projects section=without_projects}
{nabu_assign var=choose_project section=choose_project}
{nabu_assign var=item_edit cta=item_edit}
{if strlen($nb_site_target.translation.opening)}{$nb_site_target.translation.opening}{/if}
<h2>{$choose_project.translation.title}</h2>
{if !isset($data) || !is_array($data) || count($data)===0}
    <div class="alert alert-info alert-dismissible fade in">
        <button type="button" class="close" data-dismiss="alert" aria-label="{nabu_static key=lbl_close}"><span aria-hidden="true">&times;</span></button>
        {$without_projects.translation.content}
    </div>
{else}
    {$choose_project.translation.opening}
{/if}
<div class="row">
    {if isset($data) && is_array($data) && count($data)>0}
        {foreach from=$data item=project}
            <div class="col-sm-4">
                {nabu_panel type="default" title=$project.translation.title}
                    {$project.translation.opening}
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-default"><i class="fa fa-eye"></i></button>
                            <a href="{$item_edit.translation.final_url|sprintf:$project.id}" class="btn btn-sm btn-danger"><i class="fa fa-pencil"></i></a>
                        </div>
                    </div>
                {/nabu_panel}
            </div>
        {/foreach}
    {/if}
    <div class="col-sm-4">
        {nabu_panel type="info" title="{$without_projects.translation.title}"}<button class="btn btn-lg btn-default" type="button" data-toggle="nabu-modal" data-target="new_project"><i class="fa fa-plus fa-4x"></i></button>{/nabu_panel}
    </div>
</div>

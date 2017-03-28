{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=item_edit cta=item_edit}
{if strlen($nb_site_target.translation.opening)}{$nb_site_target.translation.opening}{/if}
{if isset($data) && is_array($data) && count($data)>0}
    <h2>Elige un proyecto para empezar a trabajar</h2>
    <div class="row">
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
    </div>
{/if}

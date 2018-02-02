{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_role}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_site_role.key}
        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-maximize"><i class="fa fa-window-maximize"></i></button>
                <button type="button" class="btn btn-link btn-xs hide" data-toggle="box-restore"><i class="fa fa-window-restore"></i></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-close"><i class="fa fa-window-close-o"></i></button>
            </div>
        </div>
    </div>
    <div class="box-body" data-toggle="toggable-lang">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_site_role!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_role.role_id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:site_role:{if $edit_site_role!==null}{$edit_site_role.id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {if $edit_site_role===null}
                            {nabu_form_select from=null name=role_id options=$nb_work_customer.roles options_name=name class="col-sm-6" label={nabu_static key=lbl_role}}
                        {else}
                            {nabu_form_static from=$edit_role field=name class="col-sm-6" multilang=$edit_site.languages label={nabu_static key=lbl_role}}
                        {/if}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

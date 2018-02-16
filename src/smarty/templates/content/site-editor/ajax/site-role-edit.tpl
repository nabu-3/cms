{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_role}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_role.translation.name}
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
            {nabu_form method="ajax-post" layout=vertical multiform=":root:site_role:{if $edit_site_role!==null}{$edit_site_role.role_id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {if $edit_site_role===null}
                    {nabu_form_fieldset title={nabu_static key=tit_references}}
                        {nabu_form_row}
                            {nabu_form_select from=null name=role_id options=$nb_work_customer.roles options_name=name class="col-sm-6" label={nabu_static key=lbl_role}}
                        {/nabu_form_row}
                    {/nabu_form_fieldset}
                {/if}
                {nabu_form_fieldset title={nabu_static key=tit_messaging_templates}}
                    {if is_numeric($edit_site.messaging_id) && is_array($nb_work_customer.messagings) && array_key_exists($edit_site.messaging_id, $nb_work_customer.messagings)}
                        {assign var=templates value=$nb_work_customer.messagings[$edit_site.messaging_id].templates}
                    {else}
                        {assign var=templates value=null}
                    {/if}
                    {nabu_assign var=ajax_refresh cta=ajax_messaging_reload}
                    <div class="messaging_templates_container" data-refresh="{$ajax_refresh.translation.final_url|sprintf:$edit_site.id:$edit_role.id:'%s'}">
                        {include file="content/site-editor/parts/site-edit-messaging-templates.tpl"
                                 edit_source=$edit_site_role templates=$templates class="col-md-4 col-sm-4"}
                    </div>
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

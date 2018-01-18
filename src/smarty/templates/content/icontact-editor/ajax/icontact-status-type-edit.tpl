{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_status_type}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_prospect_status_type.key}
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
    <div class="box-body">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_prospect_status_type!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_icontact.id:$edit_prospect_status_type.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_icontact.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:category:{if $edit_prospect_status_type!==null}{$edit_prospect_status_type.id}{else}%s{/if}" action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_prospect_status_type field=key name=key label="Key" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_prospect_status_type field=hash name=hash label="GUID" class="col-sm-7"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_content}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_prospect_status_type field=name label="{nabu_static key=lbl_name}" multilang=$edit_icontact.languages class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

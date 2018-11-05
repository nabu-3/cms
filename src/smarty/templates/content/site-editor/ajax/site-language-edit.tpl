{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_languages}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_language.name}
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
            {if $edit_language!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_language.language_id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:'%s'}"}
                {assign var=url_field value=language_id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:language:{if $edit_language!==null}{$edit_language.id}{else}_new{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {if $edit_site.public_base_path_enabled=='T'}
                    {nabu_form_fieldset title="{nabu_static key=tit_location}"}
                        {nabu_form_row}
                            {nabu_form_textbox from=$edit_language field=public_base_path label="{nabu_static key=lbl_public_base_path}" class="col-sm-12"}
                        {/nabu_form_row}
                    {/nabu_form_fieldset}
                {/if}
                {nabu_form_fieldset title="{nabu_static key=tit_language}"}
                    {nabu_form_row}
                        {nabu_form_select from=$edit_language field=language_id options=$nb_all_languages options_name=name class="col-sm-6" label="{nabu_static key=lbl_language}"}
                        <div class="col-sm-6 container-checkbox">
                            {nabu_form_checkbox from=$edit_language field=enabled check='T' uncheck='F' label="{nabu_static key=chk_active}"}
                        </div>
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title={nabu_static key=tit_content}}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_language field=name label="{nabu_static key=lbl_name}" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_assign var=formats section=formats}
                {nabu_form_fieldset title=$formats.translation.title}
                    {$formats.translation.opening}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_language field=short_datetime_format label={nabu_static key=lbl_short_datetime_format} class="col-sm-3"}
                        {nabu_form_textbox from=$edit_language field=middle_datetime_format label={nabu_static key=lbl_middle_datetime_format} class="col-sm-4"}
                        {nabu_form_textbox from=$edit_language field=full_datetime_format label={nabu_static key=lbl_full_datetime_format} class="col-sm-5"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_language field=short_date_format label={nabu_static key=lbl_short_date_format} class="col-sm-3"}
                        {nabu_form_textbox from=$edit_language field=middle_date_format label={nabu_static key=lbl_middle_date_format} class="col-sm-4"}
                        {nabu_form_textbox from=$edit_language field=full_date_format label={nabu_static key=lbl_full_date_format} class="col-sm-5"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_language field=short_time_format label={nabu_static key=lbl_short_time_format} class="col-sm-3"}
                        {nabu_form_textbox from=$edit_language field=full_time_format label={nabu_static key=lbl_full_time_format} class="col-sm-4"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

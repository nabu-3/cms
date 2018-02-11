{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_user_site}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_site.translation.name}
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
            {if is_array($edit_site)}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_user.id:$edit_site.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_user.id:'%s'}"}
                {assign var=url_field value=language_id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:site_user:{if $edit_site!==null}{$edit_site.id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title={nabu_static key=tit_location}}
                    {nabu_form_row}
                        {if is_array($edit_site)}
                            {nabu_form_static from=$edit_site field=name multilang=$edit_site.languages label={nabu_static key=lbl_site} class="col-sm-6"}
                        {else}
                            {nabu_form_select from=$edit_site_user field=site_id
                                              options=$nb_available_sites options_name=name
                                              mandatory=true rule=selected
                                              class="col-sm-6" label={nabu_static key=lbl_site}}
                        {/if}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {if is_array($edit_site)}
                    {nabu_form_fieldset title={nabu_static key=tit_security}}
                        {nabu_form_row}
                            {nabu_form_select from=$edit_site_user field=role_id
                                              options=$edit_site.roles options_name=name
                                              mandatory=true rule=selected
                                              class="col-sm-6" label={nabu_static key=lbl_role}}
                    {/nabu_form_fieldset}
                    {nabu_form_fieldset title={nabu_static key=tit_profile}}
                        {nabu_form_row}
                            {nabu_form_select from=$edit_site_user field=language_id
                                              options=$edit_site.languages options_name=name
                                              mandatory=true rule=selected
                                              class="col-sm-6" label={nabu_static key=lbl_language}}
                            <div class="col-sm-6 container-checkbox">
                                {nabu_form_checkbox from=$edit_site_user field=force_default_lang check='T' uncheck='F' label="{nabu_static key=chk_force_default_lang}"}
                            </div>
                        {/nabu_form_row}
                    {/nabu_form_fieldset}
                    {nabu_form_fieldset title={nabu_static key=tit_config}}
                        {nabu_form_row}
                            <div class="col-sm-12" data-toggle="ace-editor" data-ace-theme="solarized_light" data-ace-mode="json" data-ace-min-lines="5" data-ace-max-lines="20">
                                {nabu_form_textbox type=textarea rows=5 id=attrs from=$edit_site_user field=attributes name=attrs label={nabu_static key=lbl_attributes}}
                            </div>
                        {/nabu_form_row}
                    {/nabu_form_fieldset}
                {/if}
            {/nabu_form}
        {/if}
    </div>
</div>

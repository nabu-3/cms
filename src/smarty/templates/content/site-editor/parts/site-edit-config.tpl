{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_site}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
        {nabu_form_fieldset title="{nabu_static key=tit_security}"}
            {nabu_form_row}
                <div class="col-sm-5 col-md-4">
                    {nabu_form_select from=$edit_site field=default_role_id options=$edit_site.roles options_name=name}
                </div>
            {/nabu_form_row}
        {/nabu_form_fieldset}
        {nabu_form_fieldset title="{nabu_static key=tit_forwardings}"}
            <div class="row">
                <div class="form-group col-md-3 col-sm-4">
                    <label>Entrada principal</label>
                    <div class="input-group">
                        <span class="input-group-addon"><input type="radio" name="default_target_use_uri" {if $edit_site.default_target_use_uri==='N'} checked{/if}aria-label="" value="N"></span>
                        <p class="form-control-static">Sin asignar</p>
                    </div>
                    {nabu_form_select layout="input-group" from=$edit_site field=default_target_id
                                      addon_left=radiobox addon_left_name=default_target_use_uri addon_left_check=T
                                      value_left=$edit_site.default_target_use_uri
                                      options=$edit_site.targets options_name=title
                                      options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned}}
                    {nabu_form_textbox from=$edit_site field=default_target_url multilang=$edit_site.languages
                                       addon_left=radiobox addon_left_name=default_target_use_uri addon_left_check=U
                                       value_left=$edit_site.default_target_use_uri}
                </div>
            </div>
        {/nabu_form_fieldset}
    {/nabu_form}
{/if}

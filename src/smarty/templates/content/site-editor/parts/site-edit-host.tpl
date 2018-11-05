{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_site}
<div class="edit-container">
    {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
        {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}"}
        {nabu_form method="ajax-post" layout=vertical multiform=":root:security:" action=$url}
            {nabu_form_fieldset title={nabu_static key=tit_location}}
                {nabu_form_row}
                    <div class="col-sm-12 col-md-12">
                        {nabu_form_checkbox from=$edit_site field=public_base_path_enabled check=T uncheck=F label={nabu_static key=lbl_public_base_path_enabled}}
                    </div>
                {/nabu_form_row}
            {/nabu_form_fieldset}
            {nabu_form_fieldset title="HTTP / HTTPS"}
                {nabu_form_row}
                    <div class="col-sm-12 col-md-12">
                        <label for="http_https">Soporte por HTTP / HTTPS</label>
                        <div class="form-inline form-group">
                            <div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="checkbox" name="http_support"{if $edit_site.http_support==='T'} checked{/if} aria-label="HTTP" value="T" data-value-unchecked="F"></span>
                                    <p class="form-control-static">HTTP</p>
                                {/strip}
                            </div>
                            <div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="checkbox" name="https_support"{if $edit_site.https_support==='T'} checked{/if} aria-label="HTTPS" value="T" data-value-unchecked="F"></span>
                                    <p class="form-control-static">HTTPS</p>
                                {/strip}
                            </div>
                        </div>
                    </div>
                {/nabu_form_row}
            {/nabu_form_fieldset}
        {/nabu_form}
    {/if}
</div>

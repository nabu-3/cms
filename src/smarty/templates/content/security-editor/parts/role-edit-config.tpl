{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_role}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_role.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
        {nabu_form_fieldset title={nabu_static key=tit_references}}
            {nabu_form_row}
                {nabu_form_textbox class="col-sm-3" from=$edit_role field=key label={nabu_static key=lbl_key}}
            {/nabu_form_row}
        {/nabu_form_fieldset}
        {nabu_form_fieldset title="{nabu_static key=tit_security}"}
            {nabu_form_row}
                <div class="col-sm-5 col-md-3">
                    {nabu_form_checkbox from=$edit_role field=root label={nabu_static key=lbl_is_root} check=T uncheck=F}
                </div>
            {/nabu_form_row}
        {/nabu_form_fieldset}
    {/nabu_form}
{/if}

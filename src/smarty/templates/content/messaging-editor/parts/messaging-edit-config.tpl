{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_messaging}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_messaging.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
        {nabu_form_fieldset title="{nabu_static key=tit_references}"}
            {nabu_form_row}
                {nabu_form_textbox from=$edit_messaging field=key name=key label="Key" class="col-sm-5 col-md-3"}
                {nabu_form_textbox from=$edit_messaging field=hash name=hash label="GUID" class="col-sm-7 col-md-5"}
            {/nabu_form_row}
        {/nabu_form_fieldset}
        {nabu_form_fieldset title="{nabu_static key=tit_preferences}"}
            {nabu_form_row}
                {nabu_form_select from=$edit_messaging field=default_language_id name=default_lang_id
                                  label="Idioma por defecto" options=$nb_all_languages options_id=id options_name=name
                                  class="col-sm-6 col-md-4"}
            {/nabu_form_row}
        {/nabu_form_fieldset}
    {/nabu_form}
{else}
{/if}

{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_user}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_user.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
        {nabu_form_fieldset title="Cambia tu contraseña"}
            {nabu_form_row}
                {nabu_form_textbox type=password class="col-md-3" label="Introduce tu Contraseña" id=pass_1 name=pass_1}
                {nabu_form_textbox type=password class="col-md-3" label="Repite tu Contraseña" id=pass_2 name=pass_2}
            {/nabu_form_row}
        {/nabu_form_fieldset}
    {/nabu_form}
{/if}

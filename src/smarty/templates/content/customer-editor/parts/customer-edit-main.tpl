{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_customer}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_user.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url validation=live reflection="form-group"}
        {nabu_form_row}
            {nabu_form_textbox class="col-md-4" label={nabu_static key=lbl_fiscal_name} maxlength=64 from=$edit_customer field=fiscal_name mandatory=true rule=filled}
        {/nabu_form_row}
    {/nabu_form}
{/if}

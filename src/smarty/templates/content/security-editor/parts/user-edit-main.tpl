{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_user}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_user.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url validation=live reflection="form-group"}
        {nabu_form_row}
            {nabu_form_textbox class="col-md-3" label={nabu_static key=lbl_user_nick} maxlength=64 from=$edit_user field=login mandatory=true rule=filled}
            {nabu_form_textbox class="col-md-6" label={nabu_static key=lbl_email_address} from=$edit_user field=email mandatory=true rule="uri:email"}
        {/nabu_form_row}
        {nabu_form_row}
            {nabu_form_textbox class="col-md-4" label={nabu_static key=lbl_first_name} id=first_name name=first_name autofocus=true from=$edit_user field=first_name}
            {nabu_form_textbox class="col-md-8" label={nabu_static key=lbl_last_name} id=last_name name=last_name from=$edit_user field=last_name}
        {/nabu_form_row}
    {/nabu_form}
{/if}

{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_site}
<div class="edit-container">
    {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
        {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}"}
        {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
            {nabu_form_fieldset title={nabu_static key=tit_general_forwardings}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='default' label={nabu_static key=lbl_default_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='policies' label={nabu_static key=lbl_policies_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='page_not_found' label={nabu_static key=lbl_page_not_found_target} use_http_code=true}
            {/nabu_form_fieldset}
            {nabu_form_fieldset title={nabu_static key=tit_access_forwardings}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='login' label={nabu_static key=lbl_login_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='login_redirection' label={nabu_static key=lbl_login_redirection_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='logout_redirection' label={nabu_static key=lbl_logout_redirection_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='login_max_fails' label={nabu_static key=lbl_login_max_fails_target} use_http_code=true}
            {/nabu_form_fieldset}
            {nabu_form_fieldset title={nabu_static key=tit_alias_forwardings}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='alias_not_found' label={nabu_static key=lbl_alias_not_found_target}}
                {include file="content/site-editor/parts/site-edit-redir-item.tpl" pattern='alias_locked' label={nabu_static key=lbl_alias_locked_target}}
            {/nabu_form_fieldset}
        {/nabu_form}
    {/if}
</div>

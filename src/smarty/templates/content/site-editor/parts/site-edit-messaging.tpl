{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_site}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}"}
    {nabu_form method="ajax-post" layout=vertical multiform=":root:" action=$url}
        {nabu_form_fieldset title={nabu_static key=tit_config}}
            {nabu_form_row}
                <div class="col-sm-4 col-md-3">
                    {nabu_form_select id=messaging_select from=$edit_site field=messaging_id options=$nb_work_customer.messagings options_name=name}
                </div>
            {/nabu_form_row}
        {/nabu_form_fieldset}
        {nabu_form_fieldset id=messaging_templates title={nabu_static key=tit_templates}}
            {include file="content/site-editor/parts/site-edit-messaging-templates.tpl"}
        {/nabu_form_fieldset}
    {/nabu_form}
{/if}

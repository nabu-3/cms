{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_languages}
<div class="box box-default">
    <div class="box-heading">{$nb_site_target.translation.title|sprintf:$edit_language.name}</div>
    <div class="box-body">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_language!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:$edit_language.id}"}
            {else}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:''}"}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:language:{if $edit_language!==null}{$edit_language.id}{else}_new{/if}" action=$url}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_language field=name name=name label="Nombre" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

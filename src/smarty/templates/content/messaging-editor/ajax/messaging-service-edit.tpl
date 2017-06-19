{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_services}
<div class="box box-default">
    <div class="box-heading">{$nb_site_target.translation.title|sprintf:$edit_service.name}</div>
    <div class="box-body">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_service!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:$edit_service.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}

            {nabu_form method="ajax-post" layout=vertical multiform=":root:service:{if $edit_service!==null}{$edit_service.id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_service field=key name=key label="{nabu_static key=lbl_key}" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_service field=hash name=hash label="{nabu_static key=lbl_GUID}" class="col-sm-7"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_service field=name name=name label="Nombre del Servicio" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_connection}"}
                    {nabu_form_row}
                        {nabu_form_checkbox from=$edit_service field=status check=E uncheck=D label="Activo" class="col-sm-6"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_service field=provider label="Proveedor" class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_service field=interface label="Interfaz" class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea rows=5 from=$edit_service field=attributes name=attrs label="Atributos" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="Plantillas"}
                    {if count($nb_messaging.templates) === 0}
                        <div class="alert alert-warning">No has definido ninguna <b>Plantilla</b> todavía. Recuerda que si estás creando una en estos momentos tendrás que guardarla y recargar la página para poder asignar la plantilla.</div>
                    {else}
                        {assign var=tpl_count value=0}
                        {foreach from=$nb_messaging.templates key=template_id item=mess_temp}
                            {if count($mess_temp.translations) > 0}
                                {assign var=lang_id value=null}
                                {if array_key_exists($nb_language.id, $mess_temp.translations)}
                                    {assign var=lang_id value=$nb_language.id}
                                {elseif array_key_exists($nb_messaging.default_language_id, $mess_temp.translations)}
                                    {assign var=lang_id value=$nb_messaging.default_language_id}
                                {else}
                                    {foreach from=$mess_temp.translations key=lang_id item=value}{break}{/foreach}
                                {/if}
                                {if $lang_id !== null}
                                    {assign var=tpl_count value=$tpl_count+1}
                                    {if count($edit_service.templates) > 0 && array_key_exists($template_id, $edit_service.templates)}
                                        {assign var=checked value=T}
                                    {else}
                                        {assign var=checked value=F}
                                    {/if}
                                    {nabu_form_row}
                                        <div class="col-sm-12">
                                            {nabu_form_checkbox name=template index=$template_id value=$checked check=T uncheck=F label="{$mess_temp.translations[$lang_id].name}"}
                                        </div>
                                    {/nabu_form_row}
                                {/if}
                            {/if}
                        {/foreach}
                        {if $tpl_count > 0}
                            <div class="alert alert-info">Las plantillas mostradas son aquellas que has guardado y que tienen idiomas definidos. Para ver plantillas nuevas tendrás que recargar la página.</div>
                        {else}
                            <div class="alert alert-danger">Al parecer ninguna de las plantillas que has definido tienen configurados sus idiomas. Revisa tus plantillas e introduce los textos de los idiomas que necesites.</div>
                        {/if}
                    {/if}
                    <p class="help-block">
            {/nabu_form}
        {/if}
    </div>
</div>

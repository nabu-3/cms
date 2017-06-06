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
            {/nabu_form}
        {/if}
    </div>
</div>

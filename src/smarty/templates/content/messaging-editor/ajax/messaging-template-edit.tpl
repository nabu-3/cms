{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_templates}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_template.translation.name}
        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-maximize"><i class="fa fa-window-maximize"></i></button>
                <button type="button" class="btn btn-link btn-xs hide" data-toggle="box-restore"><i class="fa fa-window-restore"></i></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-close"><i class="fa fa-window-close-o"></i></button>
            </div>
        </div>
    </div>
    <div class="box-body" data-toggle="toggable-lang">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_template!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:$edit_template.id}"}
            {else}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_messaging.id:''}"}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:template:{if $edit_template!==null}{$edit_template.id}{else}_new{/if}" action=$url}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_template field=key name=key label="Key" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_template field=hash name=hash label="GUID" class="col-sm-7"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_template field=name name=name multilang=$nb_all_languages label="Nombre" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_connection}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_template field=render_provider label="Proveedor de Render" class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_template field=render_interface label="Interfaz de Render" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_content}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_template field=subject multilang=$nb_all_languages label="Asunto" class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ckeditor">
                            {nabu_form_textbox type=textarea rows=5 from=$edit_template field=html multilang=$nb_all_languages label="Cuerpo HTML" class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea rows=5 from=$edit_template field=text multilang=$nb_all_languages label="Cuerpo de texto" class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

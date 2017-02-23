{nabu_model model="bootstrap-3.3.7"}
{nabu_raw_assign}
    lookup_path: [
        U: "URL Estática"
        R: "Expresión Regular"
        L: "SQL Like"
    ]
    lookup_zones: [
        B: "Todas"
        O: "Pública"
        P: "Privada"
    ]
    lookup_output_types: [
        HTML: "HTML"
        JSON: "JSON"
        IMG: "Imagen"
        FILE: "Fichero"
    ]
{/nabu_raw_assign}
{nabu_assign var=cta cta=update_published_data}
{if $edit_site_target!==null}
    {assign var=form_action value={$cta.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id}}
{else}
    {assign var=form_action value={$cta.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:''}}
{/if}
{nabu_form id=published_edit layout=vertical method='ajax-post' action=$form_action}
    {nabu_form_fieldset title='Punto de Entrada'}
        {nabu_form_row}
            <div class="col-sm-12 col-md-12 col-lg-9">
                {nabu_form_textbox name=url from=$edit_site_target field=url label='Ruta'
                                   addon_left=dropdown addon_left_options=$lookup_path addon_left_name=url_filter
                                   addon_left_field=url_filter}
            </div>
        {/nabu_form_row}
        {nabu_form_row}
            <div class="col-sm-8 col-md-8 col-lg-7">
                {nabu_form_textbox name=url_rebuild from=$edit_site_target field=url_rebuild
                                   label='Plantilla de reconstrucción de Ruta'}
            </div>
            <div class="col-sm-4 col-md-4 col-lg-2">
                {nabu_form_textbox name=order from=$edit_site_target field=order label='Orden de búsqueda'}
            </div>
        {/nabu_form_row}
    {/nabu_form_fieldset}
    {nabu_form_fieldset title='Zonas'}
        {nabu_form_row}
            <div class="col-sm-4 col-md-3 col-lg-2">
                {nabu_form_select name=zone from=$edit_site_target field=zone options=$lookup_zones label='Zona'}
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3">
                {nabu_form_checkbox name=use_http from=$edit_site_target field=use_http value='T' label='Publicar usando HTTP'}
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3">
                {nabu_form_checkbox name=use_https from=$edit_site_target field=use_https value='T' label='Publicar usando HTTPS'}
            </div>
        {/nabu_form_row}
    {/nabu_form_fieldset}
    {nabu_form_fieldset title='Respuesta'}
        {nabu_form_row}
            <div class="col-sm-2">
                {nabu_form_textbox name=mimetype_id from=$edit_site_target field=mimetype_id label='Tipo MIME'}
            </div>
            <div class="col-sm-2">
                {nabu_form_select name=output_type from=$edit_site_target field=output_type label='Formato de Salida'
                                  options=$lookup_output_types}
            </div>
        {/nabu_form_row}
    {/nabu_form_fieldset}
    {nabu_form_commands}
        {nabu_form_command type=submit action=Save anchor_text='Guardar' class="btn btn-success"}
    {/nabu_form_commands}
{/nabu_form}

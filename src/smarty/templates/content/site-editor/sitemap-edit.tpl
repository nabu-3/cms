{nabu_model model="bootstrap-3.3.7"}
<ul class="nav nav-tabs nav-stacked nav-stacked-right" role="tablist">
    <li role="presentation" class="active"><a href="#sitemap_main" aria-controls="sitemap_main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#sitemap_link" aria-controls="sitemap_link" role="tab" data-toggle="tab">Enlace</a></li>
    <li role="presentation"><a href="#sitemap_contents" aria-controls="sitemap_contents" role="tab" data-toggle="tab">Contenidos</a></li>
    <li role="presentation"><a href="#sitemap_options" aria-controls="sitemap_options" role="tab" data-toggle="tab">Opciones</a></li>
    <li role="presentation"><a href="#sitemap_policies" aria-controls="sitemap_policies" role="tab" data-toggle="tab">Seguridad</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="sitemap_main">
        <div class="label-list">
            {if is_array($edit_sitemap)}
                {if $edit_sitemap.is_fetched}<span class="label label-info">ID #{$edit_sitemap.id}</span>{/if}
                <span class="label label-success">#{$edit_sitemap.order}</span>
            {/if}
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="sitemap_link">
        {nabu_form vertical=true method=POST}
            {if is_array($edit_sitemap) && $edit_sitemap.use_uri!=='N'}
                {assign var=use_uri_enabled value=T}
            {else}
                {assign var=use_uri_enabled value=F}
            {/if}
            <fieldset>
                <legend>Enlace</legend>
                {nabu_form_row}
                    <div class="col-sm-12">
                        {nabu_form_checkbox name=use_uri_enabled check=T label='Activar enlace' from=$use_uri_enabled}
                    </div>
                {/nabu_form_row}
                {nabu_form_row}
                    <div class="col-sm-11 col-sm-offset-1">
                        {*nabu_form_radiobox name=use_uri check=T label='Entrada' from=$edit_sitemap field=use_uri*}
                        {nabu_form_select options=$edit_site_targets options_id=id options_name=title
                                          from=$edit_sitemap field=site_target_id
                                          addon_left=radiobox addon_left_name=use_uri addon_left_field=use_uri
                                          addon_left_label='Entrada' addon_left_check=T}
                    </div>
                {/nabu_form_row}
                {nabu_form_row}
                    <div class="col-sm-11 col-sm-offset-1">
                        {nabu_form_textbox name=url from=$edit_sitemap field=url
                                           addon_left=radiobox addon_left_name=use_uri addon_left_field=use_uri
                                           addon_left_label='URL' addon_left_check=U}
                    </div>
                {/nabu_form_row}
            </fieldset>
            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button class="btn btn-success">Guardar</button>
                </div>
            </div>
        {/nabu_form}
    </div>
    <div role="tabpanel" class="tab-pane" id="sitemap_options">
        {nabu_form vertical=true method=POST}
            <fieldset>
                <legend>Localización</legend>
                {nabu_form_row}
                    <div class="col-sm-5">
                        {nabu_form_textbox name=key label='Key' from=$edit_sitemap field=key}
                    </div>
                    <div class="col-sm-2">
                        {nabu_form_textbox name=order label='Orden' class="disabled" from=$edit_sitemap field=order}
                    </div>
                {/nabu_form_row}
            </fieldset>
            <fieldset>
                <legend>Comportamiento</legend>
                {nabu_form_row}
                    {nabu_raw_assign}
                        options: [
                            B: "Ignorar cliente"
                            T: "Se requiere un cliente activo"
                            F: "Sin cliente activo"
                        ]
                    {/nabu_raw_assign}
                    <div class="col-sm-5">
                        {nabu_form_select name=customer_required options=$options label='Cliente'
                                          from=$edit_sitemap field=customer_required}
                    </div>
                {/nabu_form_row}
                {nabu_form_row}
                    <div class="col-sm-4">
                        {nabu_form_checkbox name=open_popup from=$edit_sitemap field=open_popup check=T label="Abrir aparte"}
                    </div>
                    <div class="col-sm-4">
                        {nabu_form_checkbox name=visible from=$edit_sitemap field=visible check=T label="Visible"}
                    </div>
                {/nabu_form_row}
            </fieldset>
            <fieldset>
                <legend>Apariencia</legend>
                {nabu_form_row}
                    <div class="col-sm-6">
                        {nabu_form_textbox name=icon from=$edit_sitemap field=icon label="Icono"}
                    </div>
                    <div class="col-sm-6">
                        {nabu_form_textbox name=css_class from=$edit_sitemap field=css_class label="Clase CSS"}
                    </div>
                {/nabu_form_row}
                {nabu_form_row}
                    <div class="col-sm-8">
                        {nabu_form_checkbox name=separator from=$edit_sitemap check=separator value=T label="Separador"}
                    </div>
                {/nabu_form_row}
            </fieldset>
            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button class="btn btn-success">Guardar</button>
                </div>
            </div>
        {/nabu_form}
    </div>
    <div role="tabpanel" class="tab-pane" id="sitemap_contents">
        {assign var=lookup_lang value=$edit_site.languages}
        {nabu_raw_assign}
            table_metadata: [
                fields: [
                    language_id: [
                        title: 'Idioma'
                        order: 'number'
                        id: true
                        lookup: $lookup_lang
                        lookup_field_name: 'name'
                        lookup_field_image: 'flag_url'
                        lookup_field_image_class: 'flag'
                    ]
                    title: [
                        title: 'Nombre'
                        order: 'alpha'
                    ]
                ]
            ]
        {/nabu_raw_assign}
        {nabu_table data=$edit_sitemap.translations metadata=$table_metadata selectable=true
                    bordered=true striped=true hover=true condensed=true
                    search=false pager=false size=25 column_selector=true
                    api=api_call editor=item_edit edit_button=line}
    </div>
    <div role="tabpanel" class="tab-pane" id="sitemap_policies">
    </div>
</div>

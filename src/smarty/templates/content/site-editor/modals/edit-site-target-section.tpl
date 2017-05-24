{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_edit_site_target_seo size=lg caller=site_edit_targets aria_labelledby=ve_edit_site_target_seo_head}
    {nabu_assign var=api cta=api_site_target}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout=vertical method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id}
            {nabu_modal_header dismiss=true aria_label_id=ve_edit_site_target_seo_head}{$nb_site_target.translation.title|sprintf:$edit_site_target.translations[$edit_site.default_language_id].title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {nabu_form_textbox label="{nabu_static key=lbl_head_title}" from=$edit_site_target field=head_title multilang=$edit_site.languages help="Introduce el contenido de la etiqueta <code>&lt;title&gt;</code> de la cabecera de tu página."}
                        {nabu_form_textbox label="{nabu_static key=lbl_meta_robots}" from=$edit_site_target field=meta_robots help="Introduce el contenido de la etiqueta <code>&lt;meta name=\"robots\"&gt;</code> de la cabecera de tu página."}
                        {nabu_form_textbox type=textarea rows=3 label="{nabu_static key=lbl_meta_description}" from=$edit_site_target field=meta_description multilang=$edit_site.languages help="Introduce el contenido de la etiqueta <code>&lt;meta name=\"description\"&gt;</code> de la cabecera de tu página."}
                        {nabu_form_textbox label="{nabu_static key=lbl_meta_keywords}" from=$edit_site_target field=meta_keywords multilang=$edit_site.languages help="Introduce el contenido de la etiqueta <code>&lt;meta name=\"keywords\"&gt;</code> de la cabecera de tu página."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_edit_site_target_seo"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

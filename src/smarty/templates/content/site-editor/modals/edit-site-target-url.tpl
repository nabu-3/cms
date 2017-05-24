{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_edit_site_target_url size=lg caller=site_edit_targets aria_labelledby=ve_edit_site_target_url_head}
    {nabu_assign var=api cta=api_site_target}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout=vertical method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id}
            {nabu_modal_header dismiss=true aria_label_id=ve_edit_site_target_url_head}{$nb_site_target.translation.title|sprintf:$edit_site_target.translations[$edit_site.default_language_id].title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {if $edit_site.http_support==='F' && $edit_site.https_support==='F'}
                            <div class="alert alert-danger">El sitio no tiene habilitados los accesos por HTTP o HTTPS. La página no será visible.</div>
                        {/if}
                        {if $edit_site_target.url_filter==='R'}
                            {nabu_form_textbox label="{nabu_static key=lbl_url}" from=$edit_site_target field=url_rebuild multilang=$edit_site.languages help="Escribe la URL de la página. Deberá contener formateadores que permitan introducir las partes variables cuando lo necesites. Puedes usar sprintf o nb_vnsprintf para rellenar los formateadores."}
                            {nabu_form_textbox label="{nabu_static key=lbl_url_regexpr}" from=$edit_site_target field=url multilang=$edit_site.languages help="Escribe la expresión regular que debe cumpliar la URL de la página. Para un correcto uso, las partes variables extraíbles deberían coincidir con la posición de los formateadores usados en la URL."}
                        {else}
                            {nabu_form_textbox label="{nabu_static key=lbl_url}" from=$edit_site_target field=url multilang=$edit_site.languages help="Escribe la URL de la página."}
                        {/if}
                        {if $edit_site.http_support==='T' || $edit_site.https_support==='T'}
                            <div class="row">
                                {if $edit_site.http_support==='T'}
                                    <div class="col-sm-4">{nabu_form_checkbox label="Acceso por HTTP" from=$edit_site_target field=use_http check=T uncheck=F}</div>
                                {/if}
                                {if $edit_site.https_support==='T'}
                                    <div class="col-sm-4">{nabu_form_checkbox label="Acceso por HTTPS" from=$edit_site_target field=use_https check=T uncheck=F}</div>
                                {/if}
                            </div>
                        {/if}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_edit_site_target_url"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

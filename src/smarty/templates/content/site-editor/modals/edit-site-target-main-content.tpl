{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_edit_target_main_content size=lg aria_labelledby=ve_edit_target_main_content_head}
    {nabu_modal_header dismiss=true aria_label_id=ve_edit_target_main_content_head}{$nb_site_target.translation.title|sprintf:$edit_site_target.translations[$edit_site.default_language_id].title}{/nabu_modal_header}
    {nabu_assign var=api cta=api_site_target}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout=vertical method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {nabu_form_textbox label={nabu_static key=lbl_title} from=$edit_site_target field=title multilang=$edit_site.languages help="Escribe el título de la página."}
                        {nabu_form_textbox label={nabu_static key=lbl_image} from=$edit_site_target field=main_image multilang=$edit_site.languages help="Escribe la URL de imagen principal de la página."}
                        {nabu_form_textbox label={nabu_static key=lbl_subtitle} from=$edit_site_target field=subtitle multilang=$edit_site.languages help="Escribe el subtítulo de la página."}
                        {nabu_form_textbox type=textarea rows=3 label={nabu_static key=lbl_opening} from=$edit_site_target field=opening multilang=$edit_site.languages help="Escribe la entradilla de la página."}
                        {nabu_form_textbox type=textarea rows=6 label={nabu_static key=lbl_content} from=$edit_site_target field=content multilang=$edit_site.languages help="Escribe el cuerpo de la página."}
                        {nabu_form_textbox type=textarea rows=3 label={nabu_static key=lbl_footer} from=$edit_site_target field=footer multilang=$edit_site.languages help="Escribe el pie de la página."}
                        {nabu_form_textbox type=textarea rows=3 label={nabu_static key=lbl_aside} from=$edit_site_target field=aside multilang=$edit_site.languages help="Escribe el texto lateral de la página."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_edit_target_main_content"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

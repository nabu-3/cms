{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=section_new_page section=ve_new_page}
{nabu_modal id=ve_new_page size=lg caller=site_edit_targets aria_labelledby=ve_new_page_head}
{nabu_assign var=api cta=api_site_target}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout="horizontal:2:10" method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:""}
            {nabu_modal_header dismiss=true aria_label_id=ve_new_page_head}{$section_new_page.translation.title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$section_new_page.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3">
                        {nabu_form_textbox label="{nabu_static key=lbl_title}" name=title index=$nb_language.id maxlength=100 help="Escribe el título de la página."}
                        {nabu_form_textbox label="{nabu_static key=lbl_url}" name=url index=$nb_language.id maxlength=100 help="Escribe la URL de la página."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Crear</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

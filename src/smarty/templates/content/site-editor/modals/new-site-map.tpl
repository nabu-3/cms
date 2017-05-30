{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_new_site_map size=lg caller=site_edit_maps aria_labelledby=ve_new_site_map_head}
    {nabu_assign var=api cta=api_site_map}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout=vertical method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:""}
            {nabu_form_variable name=customer_required value=B}
            {nabu_form_variable name=visible value=T}
            {nabu_modal_header dismiss=true aria_label_id=ve_new_site_map_head}{$nb_site_map.translation.title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_map.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {nabu_form_textbox label="{nabu_static key=lbl_anchor_text}" name=content multilang=$edit_site.languages help="Escribe el texto de anclaje."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_new_site_map"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Crear</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

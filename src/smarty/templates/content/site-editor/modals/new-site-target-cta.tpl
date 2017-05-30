{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_edit_site_target_cta size=lg caller=site_edit_targets aria_labelledby=ve_edit_site_target_cta_head}
    {nabu_assign var=api cta=api_site_target_cta}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {if $edit_site_target_cta===null}
            {assign var=url value=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id:''}
        {else}
            {assign var=url value=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id:$edit_site_target_cta.id}
        {/if}
        {nabu_form layout=vertical method="ajax-post" action=$url}
            {nabu_modal_header dismiss=true aria_label_id=ve_edit_site_target_cta_head}{$nb_site_target.translation.title|sprintf:$edit_site_target.translations[$edit_site.default_language_id].title:$edit_site_target_cta.translations[$edit_site.default_language_id].title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {nabu_form_textbox label="{nabu_static key=lbl_key}" from=$edit_site_target_section field=key help="Escribe la clave para localizar este CTA desde el código."}
                        {nabu_form_textbox label="{nabu_static key=lbl_anchor_text}" from=$edit_site_target_cta field=anchor_text multilang=$edit_site.languages help="Escribe el texto de anclaje."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_edit_site_target_cta"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

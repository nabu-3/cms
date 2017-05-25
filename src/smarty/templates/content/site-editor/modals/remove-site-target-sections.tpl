{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_edit_site_target_section size=lg caller=site_edit_targets aria_labelledby=ve_edit_site_target_section_head}
    {nabu_assign var=api cta=api_site_target_section}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {assign var=url value=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_site_target.id:''}
        {nabu_form layout=vertical method="ajax-delete" action=$url}
            {nabu_modal_header dismiss=true aria_label_id=ve_edit_site_target_section_head}{$nb_site_target.translation.title|sprintf:$edit_site_target.translations[$edit_site.default_language_id].title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        <ul class="list-unstyled">
                            {foreach from=$edit_site_target.sections item=section}
                                <li>{nabu_form_checkbox label={$section.translations[$edit_site.default_language_id].title} name="ids[{$section.id}]" check=T}</li>
                            {/foreach}
                        </ul>
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_edit_site_target_section"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Borrar</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

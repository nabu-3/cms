{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=ve_new_site_target size=lg caller=site_edit_targets aria_labelledby=ve_new_site_target_head}
    {nabu_assign var=api cta=api_site_target}
    {if array_key_exists($nb_site.api_language_id, $api.translations)}
        {nabu_form layout=vertical method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:""}
            {nabu_form_variable from=$edit_site field=http_support name=use_http}
            {nabu_form_variable from=$edit_site field=https_support name=use_https}
            {if array_key_exists('type', $smarty.get)}
                {if ($smarty.get.type==='page-multi' || $smarty.get.type==='document-multi')}
                    {nabu_form_variable name=url_filter value=R}
                    {else}
                    {nabu_form_variable name=url_filter value=U}
                {/if}
                {if $smarty.get.type==='document' || $smarty.get.type=='document-multi'}
                    {nabu_form_variable name=attachment value=T}
                {else}
                    {nabu_form_variable name=attachment value=F}
                {/if}
            {/if}
            {nabu_form_variable name=zone value=B}
            {nabu_modal_header dismiss=true aria_label_id=ve_new_site_target_head}{$nb_site_target.translation.title}{/nabu_modal_header}
            {nabu_modal_body}
                <div class="row">
                    <aside class="col-sm-3">{$nb_site_target.translation.opening}</aside>
                    <section class="col-sm-9 col-sm-offset-3" data-toggle="toggable-lang">
                        {nabu_form_textbox autofocus=true label="{nabu_static key=lbl_title}" name=title multilang=$edit_site.languages help="Escribe el título de la página."}
                        {nabu_form_textbox label="{nabu_static key=lbl_url}" name=url multilang=$edit_site.languages help="Escribe la URL de la página."}
                    </section>
                </div>
            {/nabu_modal_body}
            {nabu_modal_footer}
                {include file="content/parts/flag-selector.tpl" lang_list=$edit_site.languages
                         default_lang=$edit_site.default_language_id target="#ve_new_site_target"}
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Crear</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/if}
{/nabu_modal}

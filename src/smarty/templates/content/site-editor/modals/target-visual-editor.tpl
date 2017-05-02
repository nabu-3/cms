{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=modal_visual_editor_targets size=full caller=site_edit_targets aria_labelledby=modal_visual_editor_targets_head}
    {nabu_modal_header dismiss=true aria_label_id=modal_download_head}nabu-3 Visual Editor{/nabu_modal_header}
    {nabu_modal_body}
        {nabu_assign var=cta_source cta=api_target_visual_editor}
        {if array_key_exists($nb_site.api_language_id, $cta_source.translations)}
            {assign var=source_url value="{$cta_source.translations[$nb_site.api_language_id].final_url|sprintf:$nb_site.id}?lang={$nb_language.id}"}
            <div data-toggle="visual-editor" style="width: 100%; height: 100%;" data-source="{$source_url}"></div>
        {/if}
    {/nabu_modal_body}
{/nabu_modal}

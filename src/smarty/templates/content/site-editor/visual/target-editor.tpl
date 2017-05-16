<style type="text/css">
    [data-toggle="visual-editor"] {
        z-index: 0;
    }
    .modal-body .btn-toolbar:first-child {
    	margin: 0;
    	padding: 5px 10px;
    	background-color: #586e75;
    }
    body div.mxPopupMenu {
        -webkit-box-shadow: 3px 3px 6px #C0C0C0;
        -moz-box-shadow: 3px 3px 6px #C0C0C0;
        box-shadow: 3px 3px 6px #C0C0C0;
        background: white;
        position: absolute;
        border: 3px solid #e7e7e7;
        padding: 3px;
    }
    body table.mxPopupMenu {
        border-collapse: collapse;
        margin: 0px;
    }
    body tr.mxPopupMenuItem {
        color: black;
        cursor: default;
    }
    body td.mxPopupMenuItem {
        padding: 6px 60px 6px 30px;
        font-family: Arial;
        font-size: 10pt;
    }
    body td.mxPopupMenuIcon {
        background-color: white;
        padding: 0px;
    }
    body tr.mxPopupMenuItemHover {
        background-color: #eeeeee;
        color: black;
    }
    table.mxPopupMenu hr {
        border-top: solid 1px #cccccc;
        margin: 5px 0;
    }
    table.mxPopupMenu tr {
        font-size: 4pt;
    }
</style>
{nabu_model model="bootstrap-3.3.7"}
{nabu_modal id=modal_visual_editor_targets size=full caller=site_edit_targets aria_labelledby=modal_visual_editor_targets_head}
    {nabu_modal_header dismiss=true aria_label_id=modal_download_head}nabu-3 Visual Editor{/nabu_modal_header}
    {nabu_modal_body}
        {nabu_assign var=cta_source cta=api_site_visual_editor}
        {if array_key_exists($nb_site.api_language_id, $cta_source.translations)}
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button class="btn btn-sm btn-default btn-zoom-in"><i class="fa fa-search-plus"></i></button>
                    <button class="btn btn-sm btn-default btn-zoom-actual">1:1</button>
                    <button class="btn btn-sm btn-default btn-zoom-fit"><i class="fa fa-arrows-alt"></i></button>
                    <button class="btn btn-sm btn-default btn-zoom-out"><i class="fa fa-search-minus"></i></button>
                </div>
            </div>
            {assign var=source_url value="{$cta_source.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}?lang={$nb_language.id}"}
            <div data-toggle="ve-site" style="width: 100%; height: 100%;" data-source="{$source_url}"></div>
        {/if}
    {/nabu_modal_body}
{/nabu_modal}
{include file="content/site-editor/visual/new-page.tpl"}

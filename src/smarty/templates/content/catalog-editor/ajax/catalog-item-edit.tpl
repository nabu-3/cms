{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_items}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_item.translation.title}
        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-maximize"><i class="fa fa-window-maximize"></i></button>
                <button type="button" class="btn btn-link btn-xs hide" data-toggle="box-restore"><i class="fa fa-window-restore"></i></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-xs" data-toggle="box-close"><i class="fa fa-window-close-o"></i></button>
            </div>
        </div>
    </div>
    <div class="box-body" data-toggle="toggable-lang">
        {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
            {if $edit_item!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_catalog.id:$edit_item.id}"}
            {else}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_catalog.id:''}"}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:item:{if $edit_item!==null}{$edit_item.id}{else}_new{/if}" action=$url}
                {nabu_form_fieldset title={nabu_static key=tit_references}}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=key label={nabu_static key=lbl_key} class="col-sm-5"}
                        {nabu_form_textbox from=$edit_item field=hash label={nabu_static key=lbl_GUID} class="col-sm-7"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=sku multilang=$nb_all_languages label={nabu_static key=lbl_sku} class="col-sm-3"}
                        {nabu_form_textbox from=$edit_item field=slug multilang=$nb_all_languages label={nabu_static key=lbl_slug} class="col-sm-9"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title={nabu_static key=tit_appearance}}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=css_class label={nabu_static key=lbl_css_class} class="col-sm-6"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title={nabu_static key=tit_content}}
                    {nabu_form_row}
                        {nabu_form_select from=$edit_item field=catalog_taxonomy_id options=$nb_catalog.taxonomies options_name=title class="col-sm-9" label={nabu_static key=lbl_taxonomy}}
                    {/nabu_form_row}
                    {*nabu_form_row}
                        <div class="col-sm-12">
                            {include file="content/parts/medioteca-selector.tpl"}
                        </div>
                    {/nabu_form_row*}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=image multilang=$nb_all_languages label={nabu_static key=lbl_image} class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=title multilang=$nb_all_languages label={nabu_static key=lbl_title} class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=subtitle multilang=$nb_all_languages label={nabu_static key=lbl_subtitle} class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=anchor_text multilang=$nb_all_languages label={nabu_static key=lbl_anchor_text} class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ckeditor">
                            {nabu_form_textbox type=textarea rows=5 id=opening from=$edit_item field=opening multilang=$nb_all_languages label={nabu_static key=lbl_opening} class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ckeditor">
                            {nabu_form_textbox type=textarea rows=5 id=content from=$edit_item field=content multilang=$nb_all_languages label={nabu_static key=lbl_content} class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ckeditor">
                            {nabu_form_textbox type=textarea rows=5 id=footer from=$edit_item field=footer multilang=$nb_all_languages label={nabu_static key=lbl_footer} class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ckeditor">
                            {nabu_form_textbox type=textarea rows=5 id=aside from=$edit_item field=aside multilang=$nb_all_languages label={nabu_static key=lbl_aside} class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title={nabu_static key=tit_config}}
                    {nabu_form_row}
                        <div class="col-sm-12" data-toggle="ace-editor" data-ace-theme="solarized_light" data-ace-mode="json" data-ace-min-lines="5" data-ace-max-lines="20">
                            {nabu_form_textbox type=textarea rows=5 id=attrs from=$edit_item field=attributes name=attrs label={nabu_static key=lbl_main_attributes}}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div data-toggle="ace-editor" data-ace-theme="solarized_light" data-ace-mode="json" data-ace-min-lines="5" data-ace-max-lines="20">
                            {nabu_form_textbox type=textarea rows=5 id=attrs_lang from=$edit_item field=attributes name=lang_attrs multilang=$nb_all_languages label={nabu_static key=lbl_lang_attributes} class="col-sm-12"}
                        </div>
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

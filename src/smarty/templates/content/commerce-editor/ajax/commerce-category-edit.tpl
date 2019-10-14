{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_categories}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_category.key}
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
            {if $edit_category!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_commerce.id:$edit_category.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_commerce.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:category:{if $edit_category!==null}{$edit_category.id}{else}%s{/if}" action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_category field=key name=key label="Key" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_category field=hash name=hash label="GUID" class="col-sm-7"}
                        {nabu_form_textbox from=$edit_category field=slug name=slug label="Slug" multilang=$edit_commerce.languages class="col-sm-7"}
                    {/nabu_form_row}
                    {nabu_form_fieldset title="{nabu_static key=tit_visibility}"}
                        {nabu_form_row}
                            {nabu_form_textbox from=$edit_category field=order label="{nabu_static key=lbl_position}" class="col-sm-2"}
                        {/nabu_form_row}
                    {/nabu_form_fieldset}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_content}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_category field=title label="{nabu_static key=lbl_title}" multilang=$edit_commerce.languages class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_config}"}
                    {nabu_form_row}
                        <div class="col-sm-12" data-toggle="ace-editor" data-ace-theme="solarized_light" data-ace-mode="json" data-ace-min-lines="5" data-ace-max-lines="20">
                            {nabu_form_textbox type=textarea rows=5 from=$edit_category field=attributes name=attrs label="Atributos generales"}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div class="col-sm-12" data-toggle="ace-editor" data-ace-theme="solarized_light" data-ace-mode="json" data-ace-min-lines="5" data-ace-max-lines="20">
                            {nabu_form_textbox type=textarea rows=5 from=$edit_category field=attributes name=attrs_lang label="Atributos de idioma" multilang=$edit_commerce.languages}
                        </div>
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

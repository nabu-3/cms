{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_items}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_item.key}
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
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_medioteca.id:$edit_item.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$nb_medioteca.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:item:{if $edit_item!==null}{$edit_item.id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=key name=key label="Key" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_item field=hash name=hash label="GUID" class="col-sm-7"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_visibility}"}
                    {nabu_form_row}
                        <div class="col-sm-2 container-checkbox">
                            {nabu_form_checkbox from=$edit_item field=visible name=visible check='T' uncheck='F' label="{nabu_static key=lbl_visible}"}
                        </div>
                        {nabu_form_textbox from=$edit_item field=order label="{nabu_static key=lbl_position}" class="col-sm-2"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_appearance}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=css_class label="{nabu_static key=lbl_css_class}" class="col-sm-6"}
                        {nabu_form_textbox from=$edit_item field=icon label="{nabu_static key=lbl_icon}" class="col-sm-6"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_linked_object}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=mime_type label="{nabu_static key=lbl_mime_type}" multilang=$nb_all_languages class="col-sm-4"}
                        {nabu_form_textbox from=$edit_item field=url label="{nabu_static key=lbl_url}" multilang=$nb_all_languages class="col-sm-8"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=public_path label="{nabu_static key=lbl_public_url}" multilang=$nb_all_languages class="col-sm-8"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea from=$edit_item field=html_object label="{nabu_static key=lbl_html_object}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_content}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=title label="{nabu_static key=lbl_title}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_item field=subtitle label="{nabu_static key=lbl_subtitle}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea from=$edit_item field=opening label="{nabu_static key=lbl_opening}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea from=$edit_item field=content label="{nabu_static key=lbl_content}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea from=$edit_item field=footer label="{nabu_static key=lbl_footer}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

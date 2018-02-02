{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_static_contents}
<div class="box box-default">
    <div class="box-heading">
        {$nb_site_target.translation.title|sprintf:$edit_static_content.key}
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
            {if $edit_static_content!==null}
                {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:$edit_static_content.id}"}
                {assign var=url_tpl value=null}
                {assign var=url_field value=null}
            {else}
                {assign var=url value=null}
                {assign var=url_tpl value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id:'%s'}"}
                {assign var=url_field value=id}
            {/if}
            {nabu_form method="ajax-post" layout=vertical multiform=":root:static-content:{if $edit_static_content!==null}{$edit_static_content.id}{else}%s{/if}"
                       action=$url action_template=$url_tpl action_template_field=$url_field}
                {nabu_form_fieldset title="{nabu_static key=tit_references}"}
                    {nabu_form_row}
                        {nabu_form_textbox from=$edit_static_content field=key name=key label="Key" class="col-sm-5"}
                        {nabu_form_textbox from=$edit_static_content field=hash name=hash label="GUID" class="col-sm-7"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title="{nabu_static key=tit_content}"}
                    {nabu_form_row}
                        {nabu_form_textbox type=textarea from=$edit_static_content field=text label="{nabu_static key=lbl_content}" multilang=$nb_all_languages class="col-sm-12"}
                    {/nabu_form_row}
                {/nabu_form_fieldset}
                {nabu_form_fieldset title={nabu_static key=tit_hack_it}}
                    <h4>Smarty</h4>
                    <code class="block">{ldelim}nabu_static key="{$edit_static_content.key}"{rdelim}</code>
                    <h4>PHP</h4>
                    <p>{nabu_static key="lbl_by_id"}</p>
                    <code class="block">$this->nb_site->getStaticContent({$edit_static_content.id});</code>
                    <p>{nabu_static key="lbl_by_key"}</p>
                    <code class="block">$this->nb_site->getStaticContentByKey('{$edit_static_content.key}');</code>
                {/nabu_form_fieldset}
            {/nabu_form}
        {/if}
    </div>
</div>

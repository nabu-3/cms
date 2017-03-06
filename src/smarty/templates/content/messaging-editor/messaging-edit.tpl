{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_messaging)}
        {if $edit_messaging.is_fetched}<span class="label label-info">ID #{$edit_messaging.id}</span>{/if}
    {/if}
</div>
{include file="content/parts/flag-selector.tpl" lang_list=$edit_messaging.languages default_lang=$edit_messaging.default_language_id}
<div id="edit_zone" class="edit-zone">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">{nabu_static key=tab_main}</a></li>
        <li role="presentation"><a href="#services" aria-controls="services" role="tab" data-toggle="tab">{nabu_static key=tab_services}</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="main">
            <div class="row">
                <div class="col-sm-9">
                    <div class="box box-info">
                        {nabu_assign var=section section=status}
                        <div class="box-heading">{$section.translation.title}</div>
                        <div class="box-body">
                            <div class="help-block">{$section.translation.opening}</div>
                            {if is_array($edit_messaging)}
                                {strip}
                                    <dl class="list-messages">
                                        {if !array_key_exists('services', $edit_messaging) || count($edit_messaging.services) === 0}
                                            {nabu_assign var=services_empty section=services_empty}
                                            <dt class="text-danger">{$services_empty.translation.title}</dt>
                                            <dd>{$services_empty.translation.content}<br>
                                                <ul class="list-inline">
                                                    <li>
                                                        {nabu_open_modal type=link target=modal_new_service anchor_text="<i class=\"fa fa-plus\"></i>&nbsp;{nabu_static key=btn_new_service}"}
                                                    </li>
                                                </ul>
                                            </dd>
                                        {/if}
                                    </dl>
                                {/strip}
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <aside class="box box-info">
                        {if !isset($service_interfaces) || count($service_interfaces) === 0}
                            {nabu_assign var=section section=service_modules_empty}
                            <div class="box-heading">{$section.translation.title}</div>
                            <div class="box-body"><div class="help-block">{$section.translation.opening}</div></div>
                        {else}
                            {nabu_assign var=section section=service_modules_available}
                            <div class="box-heading">{$section.translation.title}</div>
                            <div class="box-body">
                                <div class="help-block">{$section.translation.opening}</div>
                                {nabu_raw_assign}
                                    table_metadata: [
                                        fields: [
                                            interface_name: [
                                                title: 'Nombre'
                                                order: 'alpha'
                                            ]
                                        ]
                                    ]
                                {/nabu_raw_assign}
                                {nabu_table id=item_list data=$service_interfaces metadata=$table_metadata selectable=false
                                            bordered=true striped=true hover=true condensed=true
                                            search=false pager=false size=25 column_selector=true
                                            api=api_call editor=item_edit edit_button=line}
                            </div>
                        {/if}
                    </aside>
                </div>
            </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="services">
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success" type="button"><i class="fa fa-check"></i>&nbsp;{nabu_static key=btn_save}</button>
        </div>
    </div>
</div>
{nabu_assign var=modal_new section=new_service}
{nabu_assign var=modal_new_success section=new_service_success}
{nabu_assign var=modal_new_error section=new_service_error}
{nabu_assign var=api cta=new_service}
{nabu_modal id=modal_new_service size=lg caller=edit_zone aria_labelledby=modal_new_service_head}
    {nabu_form layout="horizontal:2:10" method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:""}
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_new_service_head}{$modal_new.translation.title}{/nabu_modal_header}
                {nabu_modal_body}
                    <div class="row">
                        <aside class="col-sm-3">{$modal_new.translation.opening}</aside>
                        <section class="col-sm-9 col-sm-offset-3">
                            {nabu_form_textbox label=Key name=key maxlength=30 help="Establece una <b>key</b> para usar tu repositorio desde el código."}
                            {nabu_form_textbox label=Nombre name=name index=$nb_language.id maxlength=100 help="Pon un <b>nombre</b> a tu repositorio para identificarlo en todo momento."}
                        </section>
                    </div>
                {/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear</button>
                {/nabu_modal_footer}
            </div>
        </div>
        <div class="modal-panels hide">
            <div class="modal-panel" data-action="success">
                {nabu_modal_header dismiss=true}{$modal_new_success.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_new_success.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    <a class="btn btn-info"{if is_array($editor) && count($editor)>0} data-toggle="modal-btn-editor" data-editor="{$editor.translation.final_url}"{/if}>Continuar</a>
                {/nabu_modal_footer}
            </div>
            <div class="modal-panel" data-action="error">
                {nabu_modal_header dismiss=true}{$modal_new_error.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_new_error.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                {/nabu_modal_footer}
            </div>
        </div>
    {/nabu_form}
{/nabu_modal}

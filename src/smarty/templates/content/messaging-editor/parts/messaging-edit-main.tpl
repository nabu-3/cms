{nabu_model model="bootstrap-3.3.7"}
<div class="row">
    <div class="col-sm-7 col-md-8 col-lg-9">
        <div class="box box-info">
            {nabu_assign var=section section=status}
            <div class="box-heading">{$section.translation.title}</div>
            <div class="box-body">
                <div class="help-block">{$section.translation.opening}</div>
                {capture assign=dlCapture}{strip}
                    {assign var=total_alerts value=0}
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
                        {assign var=total_alerts value=$total_alerts+1}
                    {/if}
                    {if !is_numeric($edit_messaging.default_language_id) || !array_key_exists($edit_messaging.default_language_id, $nb_all_languages)}
                        {nabu_assign var=default_lang_empty section=default_lang_empty}
                        <dt class="text-warning">{$default_lang_empty.translation.title}</dt>
                        <dd>{$default_lang_empty.translation.content}<br>
                            <ul class="list-inline">
                                <li role="presentation">
                                    <a href="#config" class="btn btn-link" aria-controls="config" role="link" data-toggle="nabu-tab-link" data-tags="#main_tabs"><i class="fa fa-cog"></i>&nbsp;{nabu_static key=btn_assign}</a>
                                </li>
                            </ul>
                        </dd>
                    {/if}
                    {if !array_key_exists('templates', $edit_messaging) || count($edit_messaging.templates) === 0}
                        {nabu_assign var=templates_empty section=templates_empty}
                        <dt class="text-warning">{$templates_empty.translation.title}</dt>
                        <dd>{$templates_empty.translation.content}<br>
                            <ul class="list-inline">
                                <li>
                                    <a href="#templates" class="btn btn-link" aria-controls="templates" role="link" data-toggle="nabu-tab-link" data-tags="#main_tabs"><i class="fa fa-plus"></i>&nbsp;{nabu_static key=btn_new_template}</a>
                                </li>
                            </ul>
                        </dd>
                        {assign var=total_alerts value=$total_alerts+1}
                    {/if}
                {/strip}{/capture}
                {strip}
                    {if $total_alerts>0}
                        <dl class="list-messages">{$dlCapture}</dl>
                    {else}
                        {nabu_assign var=messaging_success section=messaging_success}
                        <dl class="list-messages">
                            {$dlCapture}
                            <dt class="text-success">{$messaging_success.translation.title}</dt>
                            <dd>{$messaging_success.translation.content}</dd>
                        </dl>
                    {/if}
                {/strip}
            </div>
        </div>
    </div>
    <div class="col-sm-5 col-md-4 col-lg-3">
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

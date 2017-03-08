{nabu_model model="bootstrap-3.3.7"}
<div class="row">
    <div class="col-sm-6">
        {nabu_raw_assign}
            table_metadata: [
                fields: [
                    id: [
                        title: 'ID'
                        order: 'alpha'
                    ]
                    key: [
                        title: 'Key'
                        order: 'alpha'
                    ]
                    provider: [
                        title: "Módulo"
                        order: 'alpha'
                    ]
                    name: [
                        title: 'Servicio'
                        order: 'alpha'
                    ]
                ]
            ]
        {/nabu_raw_assign}
        {if count($edit_messaging.services)>0}
            {nabu_table id=item_list data=$edit_messaging.services metadata=$table_metadata selectable=true
                        bordered=true striped=true hover=true condensed=true
                        search=false pager=false size=25 column_selector=true
                        api=api_call editor=item_edit edit_button=line}
        {else}
            {nabu_assign var=info_section section=empty_data}
            {nabu_panel type=info title=$info_section}{$info_section.translation.opening}{/nabu_panel}
        {/if}
    </div>
    <div class="col-sm-6" id="service_edit">
        {include file="content/parts/myst.tpl"}
        <div class="panel panel-info">Selecciona un elemento de la tabla para editarlo.</div>
        <div class="edit-container hide"></div>
    </div>
</div>

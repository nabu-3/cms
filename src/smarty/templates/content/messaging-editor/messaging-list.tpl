{nabu_model model="bootstrap-3.3.7"}
{nabu_raw_assign}
    table_metadata: [
        fields: [
            id: [
                title: 'ID'
                order: 'number'
                align: 'right'
                id: true
            ]
            key: [
                title: 'Key'
                order: 'alpha'
            ]
            title: [
                title: 'Nombre'
                order: 'alpha'
            ]
        ]
    ]
{/nabu_raw_assign}
<div class="edit-zone">
    {if count($data)>0}
        {nabu_table data=$data metadata=$table_metadata selectable=true
                    bordered=true striped=true hover=true condensed=true
                    search=false pager=false size=25 column_selector=true
                    api=api_call editor=item_edit edit_button=line}
    {else}
        {nabu_assign var=info_section section=empty_data}
        {nabu_panel type=info title=$info_section}{$info_section.translation.opening}{/nabu_panel}
    {/if}
    <div class="btn-toolbar" role="toolbar" aria-label="Table actions">
        <div class="btn-group pull-right" role="group">
            <button class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;{nabu_static key=btn_new_repository}</button>
        </div>
    </div>
</div>

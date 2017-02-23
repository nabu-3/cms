{nabu_model model="bootstrap-3.3.7"}
<div class="label-list">
    {if is_array($edit_medioteca) && $edit_medioteca.is_fetched}<span class="label label-info">ID #{$edit_medioteca.id}</span>{/if}
</div>
<div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Idiomas</a></li>
        <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuración</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
        </div>
        <div role="tabpanel" class="tab-pane" id="languages">
        </div>
        <div role="tabpanel" class="tab-pane" id="items">
            {nabu_raw_assign}
                items_metadata: [
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
                    ]
                ]
            {/nabu_raw_assign}
            {nabu_table data=$edit_medioteca.items metadata=$items_metadata selectable=true
                        bordered=true striped=true hover=true condensed=true
                        search=false pager=false size=25 column_selector=true
                        api=api_call editor=item_edit edit_button=line}
        </div>
        <div role="tabpanel" class="tab-pane" id="config">
        </div>
    </div>
</div>

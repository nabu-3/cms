{nabu_model model="bootstrap-3.3.7"}
{nabu_raw_assign}
    lookup_path: [
        U: "URL Estática"
        R: "Expresión Regular"
        L: "SQL Like"
    ]
    table_metadata: [
        fields: [
            id: [
                title: 'ID'
                order: 'number'
                align: 'right'
                id: true
            ]
            order: [
                title: 'Orden'
                order: 'number'
                align: 'right'
            ]
            key: [
                title: 'Key'
                order: 'alpha'
                align: 'left'
            ]
            use_http: [
                title: 'HTTP'
                order: 'alpha'
                align: 'center'
                lookup: [
                    T: 'Si'
                    F: 'No'
                ]
            ]
            use_https: [
                title: 'HTTPS'
                order: 'alpha'
                align: 'center'
                lookup: [
                    T: 'Si'
                    F: 'No'
                ]
            ]
            zone: [
                title: 'Zona'
                order: 'alpha'
                align: 'left'
                lookup: [
                    O: 'Pública'
                    P: 'Privada'
                    B: 'Todas'
                ]
            ]
            title: [
                title: 'Título'
                order: 'alpha'
                align: 'left'
            ]
            url_filter: [
                title: "Filtro URL"
                order: "alpha"
                align: "left"
                lookup: $lookup_path
            ]
            url: [
                title: "Ruta"
                order: "alpha"
                align: "left"
            ]
        ]
    ]
{/nabu_raw_assign}
{nabu_assign var=edit_target_url cta=target_edit}
{assign var=edit_target_url value={$edit_target_url.translation.final_url|sprintf:$edit_site.id:'%s'}}
{nabu_table data=$edit_targets metadata=$table_metadata selectable=true
            bordered=true striped=true hover=true condensed=true
            search=false pager=false size=25 column_selector=true
            api=api_call editor=$edit_target_url edit_button=line}

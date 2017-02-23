{nabu_model model="bootstrap-3.3.7"}
{assign var=lookup_lang value=$edit_site.languages}
{nabu_raw_assign}
    table_metadata: [
        fields: [
            language_id: [
                title: 'Idioma'
                order: 'alpha'
                lookup: $lookup_lang
                lookup_field_name: 'name'
                lookup_field_image: 'flag_url'
                lookup_field_image_class: 'flag'
            ]
            enabled: [
                title: 'Estado'
                order: 'alpha'
                align: 'center'
                lookup: [
                    T: 'Activo'
                    F: 'Bloqueado'
                ]
            ]
            name: [
                title: 'Nombre'
                order: 'alpha'
                align: 'left'
            ]
        ]
    ]
{/nabu_raw_assign}
{nabu_table data=$edit_site.translations metadata=$table_metadata selectable=true
            bordered=true striped=true hover=true condensed=true
            search=false pager=false size=25 column_selector=true
            api=api_call editor=item_edit edit_button=line}

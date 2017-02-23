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
            slug: [
                title: 'Slug'
                order: 'alpha'
            ]
        ]
    ]
{/nabu_raw_assign}
{include file="content/parts/flag-selector.tpl" lang_list=$nb_languages default_lang=$nb_language.id}
{nabu_table data=$data metadata=$table_metadata selectable=true
            bordered=true striped=true hover=true condensed=true
            search=false pager=false size=25 column_selector=true
            api=api_call editor=item_edit edit_button=line}

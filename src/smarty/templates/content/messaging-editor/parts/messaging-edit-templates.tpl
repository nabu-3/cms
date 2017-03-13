{nabu_raw_assign}
    table_metadata: [
        toolbar: [
            groups: [
                1: [
                    buttons: [
                        add: [
                            type: "default"
                            icon: "fa fa-plus"
                            apply: "all"
                        ]
                    ]
                ]
                2: [
                    buttons: [
                        test: [
                            type: "default"
                            icon: "fa fa-share"
                            apply: "single"
                        ]
                    ]
                ]
                3: [
                    buttons: [
                        delete: [
                            type: "danger"
                            icon: "fa fa-trash"
                            apply: "multiple"
                        ]
                    ]
                ]
            ]
        ]
        fields: [
            id: [
                title: "ID"
                order: "alpha"
                lookup: $nb_all_languages
                lookup_field_name: "name"
                lookup_field_image: "flag_url"
                lookup_field_image_class: "flag"
            ]
            key: [
                title: "Key"
                order: "alpha"
            ]
            name: [
                title: "Nombre"
                order: "alpha"
            ]
        ]
        translations: [
            search_button: "Buscar"
            columns_button: "Columnas"
            show_all_columns: "Mostrar todas"
            hide_all_columns: "Ocultar todas"
        ]
    ]
{/nabu_raw_assign}
{include file="content/parts/table-splitted-panels.tpl"
         data=$edit_messaging.templates metadata=$table_metadata section=templates_empty}

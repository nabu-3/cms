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
                        edit: [
                            type: "default"
                            icon: "fa fa-pencil"
                            apply: "single"
                        ]
                    ]
                ]
                2: [
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
            language_id: [
                title: "Idioma"
                order: "alpha"
                lookup: $nb_all_languages
                lookup_field_name: "name"
                lookup_field_image: "flag_url"
                lookup_field_image_class: "flag"
                id: true
            ]
            status: [
                title: "Estado"
                order: "alpha"
                align: "center"
                lookup: [
                    E: "Activo"
                    D: "Bloqueado"
                ]
            ]
            title: [
                title: "Nombre"
                order: "alpha"
            ]
            slug: [
                title: "Slug"
                order: "alpha"
            ]
        ]
    ]
{/nabu_raw_assign}
{nabu_assign var=ajax_editor cta=ajax_languages}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_catalog.id:'%s'}"}
{include file="content/parts/table-splitted-panels.tpl" id=languages_list editor=$ajax_editor editor_mode=ajax
         data=$edit_catalog.translations metadata=$table_metadata section=languages_empty}

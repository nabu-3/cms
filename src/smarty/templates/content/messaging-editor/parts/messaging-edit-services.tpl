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
                            icon: "fa fa-envelope"
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
                title: 'ID'
                order: 'alpha'
                id: true
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
        translations: [
            search_button: "{nabu_static key=btn_search}"
            columns_button: "{nabu_static key=btn_columns}"
            show_all_columns: "{nabu_static key=sel_show_all}"
            hide_all_columns: "{nabu_static key=sel_hide_all}"
        ]
    ]
{/nabu_raw_assign}
{nabu_assign var=ajax_editor cta=ajax_services}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_messaging.id:'%s'}"}
{include file="content/parts/table-splitted-panels.tpl" id=services_list editor=$ajax_editor editor_mode=ajax
         data=$edit_messaging.services metadata=$table_metadata section=services_empty}

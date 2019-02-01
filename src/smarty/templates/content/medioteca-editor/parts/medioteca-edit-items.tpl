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
                            icon:" fa fa-pencil"
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
            order: [
                title: 'Posición'
                order: 'number'
            ]
            visible: [
                title: "Visible"
                lookup: [
                    T: "{nabu_static key=lbl_yes}"
                    F: "{nabu_static key=lbl_no}"
                ]
            ]
            title: [
                title: "Título"
                order: "alpha"
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
{nabu_assign var=ajax_editor cta=ajax_items}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_medioteca.id:'%s'}"}
{include file="content/parts/table-splitted-panels.tpl" id=items_list editor=$ajax_editor editor_mode=ajax
         languages=$edit_medioteca.languages data=$edit_medioteca.items metadata=$table_metadata section=items_empty}

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
            id: [
                title: "{nabu_static key=lbl_id}"
                order: "number"
                align: "right"
                id: true
            ]
            slug: [
                title: "{nabu_static key=lbl_slug}"
                order: "alpha"
            ]
            title: [
                title: "{nabu_static key=lbl_title}"
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
{nabu_assign var=ajax_editor cta=ajax_tags}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_catalog.id:'%s'}"}
{include file="content/parts/table-splitted-panels.tpl" id=tags_list editor=$ajax_editor editor_mode=ajax
         data=$edit_catalog.tags metadata=$table_metadata languages=$edit_catalog.languages}

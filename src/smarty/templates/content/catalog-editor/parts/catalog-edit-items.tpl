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
        translations: [
            search_button: "{nabu_static key=btn_search}"
            columns_button: "{nabu_static key=btn_columns}"
            show_all_columns: "{nabu_static key=sel_show_all}"
            hide_all_columns: "{nabu_static key=sel_hide_all}"
        ]
    ]
{/nabu_raw_assign}
{nabu_assign var=ajax_editor cta=ajax_items}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_catalog.id:'%s'}"}
{include file="content/parts/tree-splitted-panels.tpl" id=items_tree editor=$ajax_editor editor_mode=ajax
         data=$edit_catalog.items field_id=id field_name=title field_childs=childs
         languages=$edit_catalog.languages metadata=$tree_metadata section=empty_items
         template="content/catalog-editor/parts/catalog-edit-items-item.tpl"}

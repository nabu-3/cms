{nabu_assign var=info_section section=empty_sites}
{assign var=lookup_sites value=$nb_work_customer.sites}
{assign var=lookup_roles value=$nb_work_customer.roles}
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
            site_id: [
                title: "ID"
                order: "alpha"
                align: "right"
                id: true
            ]
            key: [
                field: "site_id"
                title: "Key"
                lookup: $lookup_sites
                lookup_field_name: "key"
                order: "alpha"
            ]
            name: [
                field: "site_id"
                title: "Site"
                lookup: $lookup_sites
                lookup_field_name: "name"
                order: "alpha"
            ]
            role_id: [
                title: "Rol"
                lookup: $lookup_roles
                lookup_field_name: "name"
                order: "alpha"
            ]
            language_id: [
                title: "Idioma"
                lookup: $nb_all_languages
                lookup_field_name: "name"
                lookup_field_image: "flag_url"
                lookup_field_image_class: "flag"
                order: "alpha"
            ]
        ]
        translations: [
            search_button: "{nabu_static key=btn_search}"
            columns_button: "{nabu_static key=btn_columns}"
            show_all_columns: "{nabu_static key=sel_show_all}"
            hide_all_columns: "{nabu_static key=sel_hide_all}"
            empty_message: "{"\""|str_replace:"\\\"":$info_section.translation.content}"
            translation_not_available: "{nabu_static key=lbl_translation_not_available}"
        ]
    ]
{/nabu_raw_assign}
{nabu_assign var=ajax_editor cta=ajax_user_site}
{assign var=ajax_editor value="{$ajax_editor.translation.final_url|sprintf:$edit_user.id:'%s'}"}
{include file="content/parts/table-splitted-panels.tpl" id=sites_list editor=$ajax_editor editor_mode=ajax
         data=$edit_user.profiles metadata=$table_metadata}

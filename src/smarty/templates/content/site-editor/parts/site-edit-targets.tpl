{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=info_section section=empty_targets}
{nabu_raw_assign}
    lookup_path: [
        U: "<span class=\"label label-success\">{nabu_static key=tbl_static_url}</span>"
        R: "<span class=\"label label-info\">{nabu_static key=tbl_regular_expression}</span>"
        L: "<span class=\"label label-warning\">{nabu_static key=tbl_sql_like}</span>"
    ]
    lookup_zone: [
        O: "<span class=\"label label-success\">{nabu_static key=tbl_public_zone}</span>"
        P: "<span class=\"label label-danger\">{nabu_static key=tbl_private_zone}</span>"
        B: "<span class=\"label label-info\">{nabu_static key=tbl_overall_zone}</span>"
    ]
    lookup_yesno: [
        T: "Si"
        F: "No"
    ]
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
                3: [
                    buttons: [
                        builder: [
                            type: "info"
                            icon: "fa fa-puzzle-piece"
                            apply: "all"
                            modal: "modal_visual_editor_targets"
                        ]
                    ]
                ]
            ]
        ]
        fields: [
            id: [
                title: "{nabu_static key=tbl_id}"
                order: "number"
                align: "right"
                id: true
            ]
            order: [
                title: "{nabu_static key=tbl_order}"
                order: "number"
                align: "right"
            ]
            key: [
                title: "{nabu_static key=tbl_key}"
                order: "alpha"
                align: "left"
            ]
            use_http: [
                title: "HTTP"
                order: "alpha"
                align: "center"
                lookup: $lookup_yesno
            ]
            use_https: [
                title: "HTTPS"
                order: "alpha"
                align: "center"
                lookup: $lookup_yesno
            ]
            zone: [
                title: "{nabu_static key=tbl_zone}"
                order: "alpha"
                align: "left"
                lookup: $lookup_zone
            ]
            title: [
                title: "{nabu_static key=tbl_title}"
                order: "alpha"
                align: "left"
            ]
            url_filter: [
                title: "{nabu_static key=tbl_url_filter}"
                order: "alpha"
                align: "left"
                lookup: $lookup_path
            ]
            url: [
                title: "{nabu_static key=tbl_route}"
                order: "alpha"
                align: "left"
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
{nabu_assign var=edit_target_url cta=target_edit}
{assign var=edit_target_url value={$edit_target_url.translation.final_url|sprintf:$edit_site.id:'%s'}}
{nabu_table id=site_edit_targets data=$edit_targets metadata=$table_metadata selectable=true
            draw_empty=true bordered=true striped=true hover=true condensed=true
            search=false pager=true size=25 column_selector=true languages=$edit_site.languages
            api=api_call editor=$edit_target_url edit_button=line}

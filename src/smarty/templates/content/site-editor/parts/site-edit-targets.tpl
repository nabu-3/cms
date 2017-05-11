{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=info_section section=empty_targets}
{nabu_raw_assign}
    lookup_path: [
        U: "URL Estática"
        R: "Expresión Regular"
        L: "SQL Like"
    ]
    lookup_zone: [
        O: "Pública"
        P: "Privada"
        B: "Todas"
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
                title: "ID"
                order: "number"
                align: "right"
                id: true
            ]
            order: [
                title: "Orden"
                order: "number"
                align: "right"
            ]
            key: [
                title: "Key"
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
                title: "Zona"
                order: "alpha"
                align: "left"
                lookup: $lookup_zone
            ]
            title: [
                title: "Título"
                order: "alpha"
                align: "left"
            ]
            url_filter: [
                title: "Filtro URL"
                order: "alpha"
                align: "left"
                lookup: $lookup_path
            ]
            url: [
                title: "Ruta"
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
            search=false pager=false size=25 column_selector=true
            api=api_call editor=$edit_target_url edit_button=line}

{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=info_section section=empty_data}
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
                            modal: "modal_new_site"
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
                        download: [
                            type: "default"
                            icon: "fa fa-download"
                            apply: "multiple"
                            modal: "modal_download"
                        ]
                        upload: [
                            type: "danger"
                            icon: "fa fa-upload"
                            apply: "all"
                            modal: "modal_upload"
                        ]
                    ]
                ]
                4: [
                    buttons: [
                        notify: [
                            type: "default"
                            icon: "fa fa-envelope"
                            apply: "single"
                            modal: "modal_site_notify"
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
            name: [
                title: 'Nombre'
                order: 'alpha'
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
<div class="edit-zone">
    {include file="content/parts/flag-selector.tpl" lang_list=$nb_languages default_lang=$nb_language.id}
    {nabu_table id=site_list data=$data metadata=$table_metadata selectable=true languages=$nb_languages
                bordered=true striped=true hover=true condensed=true scrolled=true
                search=false pager=true size=25 column_selector=true draw_empty=true
                api=api_site editor=item_edit edit_button=line}
</div>
{include file="content/site-editor/modals/new-site.tpl"}
{include file="content/site-editor/modals/download-site.tpl"}
{include file="content/site-editor/modals/site-notify.tpl"}

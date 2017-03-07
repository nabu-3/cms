{nabu_model model="bootstrap-3.3.7"}
<div class="row">
    <div class="col-sm-6">
        {nabu_raw_assign}
            table_metadata: [
                fields: [
                    language_id: [
                        title: 'Idioma'
                        order: 'alpha'
                        lookup: $nb_all_languages
                        lookup_field_name: 'name'
                        lookup_field_image: 'flag_url'
                        lookup_field_image_class: 'flag'
                    ]
                    name: [
                        title: 'Nombre'
                        order: 'alpha'
                    ]
                    templates_status: [
                        title: 'Activo'
                        order: 'alpha'
                        lookup: [
                            D: {nabu_static key=lbl_no}
                            E: {nabu_static key=lbl_yes}
                        ]
                    ]
                ]
            ]
        {/nabu_raw_assign}
        {if count($edit_messaging.translations)>0}
            {nabu_table id=item_list data=$edit_messaging.translations metadata=$table_metadata selectable=true
                        bordered=true striped=true hover=true condensed=true
                        search=false pager=false size=25 column_selector=true
                        api=api_call editor=item_edit edit_button=line}
        {else}
            {nabu_assign var=info_section section=empty_data}
            {nabu_panel type=info title=$info_section}{$info_section.translation.opening}{/nabu_panel}
        {/if}
    </div>
    <div class="col-sm-6">
    </div>
</div>

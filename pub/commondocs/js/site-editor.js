$(document).ready(function() {
    $('#site_list')
        .on('pressed.download.toolbar.table.nabu', function (e, params) {
            if (params.action==="download" &&
                e.currentTarget &&
                e.currentTarget.nabuTable &&
                e.currentTarget.nabuTable instanceof Nabu.UI.Table
            ) {
                var nb_table = e.currentTarget.nabuTable;
                var placeholder = $('#modal_download #sites_placeholder');
                var form = placeholder.closest('[data-toggle="nabu-form"]');
                if (form.length === 1 && form[0].nabuForm && placeholder.length === 1) {
                    nb_form = form[0].nabuForm;
                    placeholder.empty();
                    for (i in params.selection) {
                        var idx = params.selection[i];
                        var text = nb_table.getCell(idx, 'name');
                        if (text !== null) {
                            placeholder.append(
                                '<label>' +
                                '<input type="checkbox" value="' + idx + '" name="ids[' + idx + ']" data-form-mandatory="yes" data-form-rule="checked">' +
                                '&nbsp;#' + idx + '&nbsp;' + text + '</label>');
                        }
                    }
                    nb_form.locateFields();
                }
            }
        })
        .on('pressed.notify.toolbar.table.nabu', function (e, params) {
            console.log('Notify');
            $('#modal_site_notify form').data('id', params.selection[0]);
        })
    ;

    $('#modal_site_notify form .btn-success').on('click', function() {
        $('#modal_site_notify').modal('hide');
    });

    $('#languages_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;

    $('#statics_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;

    $('#tree_sitemap').bind('click.tree.nabu', function(e, id) {
        if (typeof id !== 'undefined') {
            $('#sitemap_edit > .panel-info').addClass('hide');
            $('#sitemap_edit > .edit-container').addClass('hide');
            $('#sitemap_edit > .myst').removeClass('hide');
            var cont_id = 'sitemap_edit_' + id;
            var container = $('#' + cont_id);
            if (container.length > 0) {
                $('#sitemap_edit > #' + cont_id).removeClass('hide');
                $('#sitemap_edit > .myst').addClass('hide');
            } else {
                nabu.loadLibrary('Ajax', function() {
                    var ajax = new Nabu.Ajax.Connector('/es/productos/sites/ajax/sitemap-editor/' + id, 'GET', {

                    });
                    ajax.addEventListener(new Nabu.Event({
                        onLoad: function(e) {
                            $('#sitemap_edit').append('<div class="edit-container" id="' + cont_id + '"></div>');
                            var container = $('#sitemap_edit > #' + cont_id);
                            container.html(e.params.text).removeClass('hide');
                            $('#sitemap_edit > .myst').addClass('hide');
                            nbBootstrapToggleAll(container);
                        }
                    }));
                    ajax.execute();
                });
            }
        }
    });
});

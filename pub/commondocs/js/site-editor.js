$(document).ready(function() {
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

$(document).ready(function() {
    $('#tree_sections').bind('click.tree.nabu', function(e, id) {
        if (typeof id !== 'undefined') {
            var edit_zone = $('#edit_zone');
            var site_id = edit_zone.data('parent-id');
            var target_id = edit_zone.data('id');
            $('#section_edit > .panel-info').addClass('hide');
            $('#section_edit > .edit-container').addClass('hide');
            $('#section_edit > .myst').removeClass('hide');
            nabu.loadLibrary('Ajax', function() {
                var ajax = new Nabu.Ajax.Connector('/es/productos/sites/ajax/section-editor/' + site_id + '/' + target_id + '/' + id, 'GET', {

                });
                ajax.addEventListener(new Nabu.Event({
                    onLoad: function(e) {
                        var container = $('#section_edit > .edit-container');
                        container.html(e.params.text).removeClass('hide');
                        $('#section_edit > .myst').addClass('hide');
                        nbBootstrapToggleAll(container);
                    }
                }));
                ajax.execute();
            });
        }
    });
});

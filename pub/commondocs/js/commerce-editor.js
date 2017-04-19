$(document).ready(function() {
    $('#categories_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
});

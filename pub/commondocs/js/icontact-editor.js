$(document).ready(function() {
    $('#icontact_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            $('#modal_new_repository').modal('show');
        })
    ;
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
    $('#status_types_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
    ;
});

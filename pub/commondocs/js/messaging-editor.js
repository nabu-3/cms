$(document).ready(function() {
    $('languages_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;
    $('#services_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
        .on('pressed.test.toolbar.table.nabu', function(e, params) {
            console.log('Test');
            $('#modal_test_service form').data('id', params.selection[0]);
            //$('#modal_test_service').modal();
        })
    ;
    $('#templates_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;
});

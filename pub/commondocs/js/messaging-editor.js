$(document).ready(function() {
    $('#templates_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            console.log(e.namespace);
            console.log(params);
        })
        .on('pressed.test.toolbar.table.nabu', function(e, params) {
            console.log('Test');
            console.log(e.namespace);
            console.log(params);
        })
        .on('pressed.delete.toolbar.table.nabu', function(e, params) {
            console.log('Delete');
            console.log(e.namespace);
            console.log(params);
        })
    ;
});

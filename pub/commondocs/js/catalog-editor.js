$(document).ready(function() {
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

    $('#taxonomies_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;

    $('#tags_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            console.log('Add');
            this.nabuTable.editor();
        })
        .on('presed.edit.toolbar.table.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTable.editor(params.selection[0]);
        })
    ;

    $('#items_tree')
        .on('pressed.add.toolbar.tree.nabu', function(e, params) {
            console.log('Add');
            this.nabuTree.editor();
        })
        .on('pressed,edit.toolbar.tree.nabu', function(e, params) {
            console.log('Edit');
            this.nabuTree.editor(params.selection[0]);
        })
    ;
});

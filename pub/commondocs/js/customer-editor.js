$(document).ready(function() {
    $('#customer_list')
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
    ;
});

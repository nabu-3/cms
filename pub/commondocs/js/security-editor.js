$(document).ready(function() {
    $('#role_list')
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
    ;
    $('#user_list')
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
        .on('pressed.email.toolbar.table.nabu', function(e, param) {
            $('#send_mail_ids').val(param.selection);
        })
    ;
    $('#languages_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
    ;
    $('#sites_list')
        .on('pressed.add.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor();
        })
        .on('pressed.edit.toolbar.table.nabu', function(e, params) {
            this.nabuTable.editor(params.selection[0]);
        })
    ;
});

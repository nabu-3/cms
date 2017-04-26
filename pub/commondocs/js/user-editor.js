$(document).ready(function() {
    $('#user_list')
        .on('pressed.email.toolbar.table.nabu', function(e, param) {
            $('#send_mail_ids').val(param.selection);
        })
    ;
});

$(document).ready(function() {
    $('#modal_new_repository form').get(0).reset();
    $('#modal_new_repository form').on('response.form.nabu', function(e, params) {
        $(this).find('.modal-steps').addClass('hide');
        $(this).find('.modal-panels').removeClass('hide');
        $(this).find('.modal-panels .modal-panel').addClass('hide');
        var json = params.response.json;
        if (json.result.status==='OK') {
            $(this).find('.modal-panels .modal-panel[data-action="success"]').removeClass('hide');
        } else {
            $(this).find('.modal-panels .modal-panel[data-action="error"]').removeClass('hide');
        }
    });
});

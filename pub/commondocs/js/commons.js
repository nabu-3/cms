$(document).ready(function()
{
    $('#__x_nb_wc').on('change.nb.select', function(e)
    {
        $(this).closest('form').get(0).submit();
    });

    var modals = $('.modal form').closest('.modal');
    modals.find('form').each(function()
    {
        this.reset();
    });

    modals.find('form').on('response.form.nabu', function(e, params)
    {
        $(this).find('.modal-steps').addClass('hide');
        $(this).find('.modal-panels').removeClass('hide');
        $(this).find('.modal-panels .modal-panel').addClass('hide');
        var json = params.response.json;
        if (json.result.status==='OK') {
            $(this).find('.modal-panels .modal-panel[data-action="success"]').removeClass('hide');
        } else {
            $(this).find('.modal-panels .modal-panel[data-action="error"]').removeClass('hide');
        }
        $(this).data("id", json.data.id);
    });

    modals.find('form [data-toggle="modal-btn-editor"]').on("click", function() {
        var id = $(this).closest('form').data("id");
        var base = $(this).data('editor');
        var url = $.sprintf(base, id);
        document.location = url;
    });
});

$(document).ready(function()
{
    $('#__x_nb_wc').on('change.nb.select', function(e)
    {
        $(this).closest('form').get(0).submit();
    });

    $('[data-toggle="nabu-table"]').each(function() {
        var Self = this;
        nabu.loadLibrary('Table', function() {
            if (Self.nabuTable) {
                Self.nabuTable.addEventListener(new Nabu.Event({
                    onLoadEditor: function(e) {
                        $('[data-toggle="nabu-lang-selector"]').nabuLangSelector('refresh');
                    }
                }));
            }
        });
    });

    var modals = $('.modal form').closest('.modal');
    modals.find('form').each(function()
    {
        this.reset();
    });

    modals.find('form')
        .on('response.form.nabu', function(e, params) {
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
        })
        .on('beforesubmit.form.nabu', function(e, params) {
            console.log('beforesubmit');
            if (CKEDITOR) {
                for(var name in CKEDITOR.instances) {
                    CKEDITOR.instances[name].updateElement();
                }
            }
            return true;
        })
    ;

    modals.find('form [data-toggle="modal-btn-editor"]').on("click", function() {
        var id = $(this).closest('form').data("id");
        var base = $(this).data('editor');
        var url = $.sprintf(base, id);
        document.location = url;
    });

    modals = $('.modal .modal-steps').closest('.modal');
    modals.on('show.bs.modal', function(e) {
        var modal = $(this);
        modal.find('.modal-panels').addClass('hide');
        modal.find('.modal-panels .modal-panel').addClass('hide');
        modal.find('.modal-steps').removeClass('hide');
        modal.find('.modal-steps .modal-step').addClass('hide');
        modal.find('.modal-steps .modal-step[data-step="1"]').removeClass('hide');
        modal.find('form').each(function() {
            this.reset();
        });
    });
});

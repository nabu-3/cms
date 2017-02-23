$(document).ready(function() {
    $('#__x_nb_wc').on('change.nb.select', function(e) {
        $(this).closest('form').get(0).submit();
    });
});

$(document).ready(function() {
    $('[data-toggle="ckeditor"] textarea').ckeditor();
    $('#modal_new_version').on('hidden.bs.modal', function() {
        document.location.reload();
    });
});

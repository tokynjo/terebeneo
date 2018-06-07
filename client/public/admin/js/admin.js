/**
 *
 */
$('select#user_roles').removeAttr('multiple');
$(document).on('click', '.delete', function (e) {
    e.preventDefault();
    $('.confirm-delete').attr('href', $(this).attr('href'));
    $('#modal-delete-title').html($(this).attr('data-title'));
    $('#modal-delete-body').html($(this).attr('data-message'));

    $('#delete-modal').modal();
});
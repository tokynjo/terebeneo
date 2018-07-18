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
$(document).on('click', '.send-notification', function (e) {
    e.preventDefault();
    $('.confirm-notification').attr('href', $(this).attr('href'));
    $('#modal-notification-title').html($(this).attr('data-title'));
    $('#modal-notification-body').html($(this).attr('data-message'));

    $('#notification-modal').modal();
});

$(document).on('click', '.confirm-notification', function (e) {
    e.preventDefault();
    $('#modal-notification-body').html('Envoi en cours ...');
    $('.btn.notification-cancel').toggle();
    $('.btn.confirm-notification').toggle();

    $.ajax({
        url: $(this).attr("href"),
        type: 'POST',
        success: function (data) {
            $('#modal-notification-body').html('Notification envoyée');
            $('.btn.notification-cancel').toggle();
            $('.btn.confirm-notification').toggle();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#modal-notification-body').html('Echec de l\'envoi des notifications');
            $('.btn.notification-cancel').toggle();
            $('.btn.confirm-notification').toggle();
        }
    });
    return false;
});

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

$(document).ready(function() {
    $('.dataTable').DataTable({
        paging: true,
        responsive: true,
        "iDisplayLength": 20,
        "bLengthMenu": false,
        "lengthMenu": [[10, 20, 50,100], [10, 20, 50,100]],
        "aaSorting": [],
        "language": {
            "lengthMenu": "Affiche _MENU_ elements par page",
            "zeroRecords": "Il n'y a pas des résultats",
            "info": "Page _PAGE_ de _PAGES_",
            "infoEmpty": "Il n'y a pas des résultats",
            "infoFiltered": "(dans _MAX_ éléments)",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précedant"
            },
            "search": "Recherche : "
        },
        "column": [
            {"width": "50px"},
            null,
            {"width": "275px"},
            {"width": "150px"},
            {"width": "80px"},
            {"targets": 'no-sort', orderable: false}
        ]
    });
});

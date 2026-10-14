let tableNotifications;

/**
 * Charge les notifications depuis la base de données.
 */
function loadGestionNotifications() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_notifications",
        bJSON: 1
    };

    $.ajax({
        type: "POST",
        url: "route.php",
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        const tbody = $("#table_notifications tbody");

        // Évite les doublons en cas de rechargement.
        tbody.empty();

        for (let i = 0; i < result.length; i++) {
            const notification = result[i];

            const ligne = $("<tr>");

            $("<td>")
                .text(notification["id_notification"])
                .appendTo(ligne);

            $("<td>")
                .text(notification["type_notification"])
                .appendTo(ligne);

            tbody.append(ligne);
        }

        tableNotifications =
            $("#table_notifications").DataTable({
                order: [[1, "asc"]],
                pageLength: 10,
                language: {
                    search: "Rechercher :",
                    lengthMenu:
                        "_MENU_ notifications par page",
                    info:
                        "Notifications _START_ à _END_ sur _TOTAL_",
                    infoEmpty: "Aucune notification",
                    emptyTable:
                        "Aucune notification disponible",
                    zeroRecords:
                        "Aucune notification trouvée",
                    paginate: {
                        previous: "Précédent",
                        next: "Suivant"
                    }
                }
            });

        $("#divModalSaving").hide();
    })
    .fail(function (error) {
        $("#divModalSaving").hide();

        console.error(error.responseText);

        alert(
            "Erreur pendant le chargement des notifications."
        );
    });
}

$(document).ready(function () {
    loadGestionNotifications();
});
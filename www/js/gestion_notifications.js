let aOfNotifications = [];
let tableNotifications;
let indiceEditionNotification = null;

const configurationNotifications = {
    stateSave: false,
    order: [[1, "asc"]],
    pagingType: "simple_numbers",
    searching: true,
    pageLength: 10,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "Tous"]
    ],
    columnDefs: [
        {
            targets: [2, 3],
            orderable: false,
            searchable: false
        }
    ],
    language: {
        search: "Rechercher :",
        lengthMenu: "_MENU_ notifications par page",
        info: "Notifications _START_ à _END_ sur _TOTAL_",
        infoEmpty: "Aucune notification",
        emptyTable: "Aucune notification disponible",
        zeroRecords: "Aucune notification trouvée",
        paginate: {
            previous: "Précédent",
            next: "Suivant"
        }
    }
};

function loadGestionNotifications() {
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "liste_notifications",
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        aOfNotifications = Array.isArray(result) ? result : [];
        rebuildDataTableNotifications();
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant le chargement des notifications.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function constructTableNotifications() {
    const tbody = $("#table_notifications tbody");
    tbody.empty();

    for (let i = 0; i < aOfNotifications.length; i++) {
        const notification = aOfNotifications[i];
        const ligne = $("<tr>");

        $("<td>")
            .text(notification["id_notification"])
            .appendTo(ligne);

        $("<td>")
            .text(notification["type_notification"])
            .appendTo(ligne);

        const celluleModification = $("<td>");

        $("<button>", {
            type: "button",
            class: "btn btn-edit btn-table",
            text: "Modifier"
        })
        .on("click", function () {
            editNotification(i);
        })
        .appendTo(celluleModification);

        celluleModification.appendTo(ligne);

        const celluleSuppression = $("<td>");

        $("<button>", {
            type: "button",
            class: "btn btn-delete btn-table",
            text: "Supprimer"
        })
        .on("click", function () {
            supprimNotificationServeur(i);
        })
        .appendTo(celluleSuppression);

        celluleSuppression.appendTo(ligne);
        tbody.append(ligne);
    }
}

function rebuildDataTableNotifications() {
    if ($.fn.DataTable.isDataTable("#table_notifications")) {
        tableNotifications.clear();
        tableNotifications.destroy();
    }

    constructTableNotifications();
    tableNotifications = $("#table_notifications").DataTable(
        configurationNotifications
    );
}

function ajoutNotificationServeur() {
    const formulaire = document.getElementById("form_notification");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    const typeNotification = $("#type_notification").val().trim();
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "save_notification",
            type_notification: typeNotification,
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (
            result["save_notification"]
            && result["save_notification"]["error"] !== ""
        ) {
            alert(result["save_notification"]["error"]);
            return;
        }

        if (!result["id_notification_created"]) {
            alert("Le serveur n'a pas retourné l'identifiant de la notification.");
            return;
        }

        ajoutNotification(
            result["id_notification_created"],
            typeNotification
        );
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant l'ajout de la notification.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function ajoutNotification(idNotification, typeNotification) {
    aOfNotifications.push({
        id_notification: idNotification,
        type_notification: typeNotification
    });

    rebuildDataTableNotifications();
    clearFormNotification();
}

function majNotificationServeur() {
    const formulaire = document.getElementById("form_notification");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    if (
        indiceEditionNotification === null
        || !aOfNotifications[indiceEditionNotification]
    ) {
        alert("Aucune notification n'est sélectionnée.");
        return;
    }

    const typeNotification = $("#type_notification").val().trim();
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "update_notification",
            id_notification: $("#id_notification").val(),
            type_notification: typeNotification,
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["update_notification"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }

        if (result["update_notification"]["error"] !== "") {
            alert(result["update_notification"]["error"]);
            return;
        }

        majNotification(typeNotification);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la modification de la notification.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function majNotification(typeNotification) {
    aOfNotifications[indiceEditionNotification]["type_notification"] =
        typeNotification;

    rebuildDataTableNotifications();
    clearFormNotification();
}

function supprimNotificationServeur(indiceTableau) {
    if (!aOfNotifications[indiceTableau]) {
        alert("La notification sélectionnée est introuvable.");
        return;
    }

    const notification = aOfNotifications[indiceTableau];

    if (!confirm(
        'Supprimer la notification "'
        + notification["type_notification"]
        + '" ?'
    )) {
        return;
    }

    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "supprime_notification",
            id_notification: notification["id_notification"],
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["supprime_notification"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }

        if (result["supprime_notification"]["error"] !== "") {
            alert(result["supprime_notification"]["error"]);
            return;
        }

        supprimNotification(indiceTableau);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la suppression de la notification.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function supprimNotification(indiceTableau) {
    aOfNotifications.splice(indiceTableau, 1);
    clearFormNotification();
    rebuildDataTableNotifications();
}

function editNotification(indiceTableau) {
    if (!aOfNotifications[indiceTableau]) {
        alert("La notification sélectionnée est introuvable.");
        return;
    }

    indiceEditionNotification = indiceTableau;
    const notification = aOfNotifications[indiceTableau];

    $("#id_notification").val(notification["id_notification"]);
    $("#type_notification").val(notification["type_notification"]);
    $("#form_title_notification").text("Modifier la notification");

    $("#btn_ajouter_notification").addClass("hide");
    $("#btn_modifier_notification").removeClass("hide");
    $("#btn_annuler_notification").removeClass("hide");
    $("#type_notification").trigger("focus");
}

function clearFormNotification() {
    indiceEditionNotification = null;

    $("#id_notification").val("");
    $("#type_notification").val("");
    $("#form_title_notification").text("Ajouter une notification");

    $("#btn_ajouter_notification").removeClass("hide");
    $("#btn_modifier_notification").addClass("hide");
    $("#btn_annuler_notification").addClass("hide");
    $("#type_notification").trigger("focus");
}

$(document).ready(function () {
    loadGestionNotifications();
});

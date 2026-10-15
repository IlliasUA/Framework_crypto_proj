let aOfRisques = [];
let tableRisques;
let indiceEditionRisque = null;

const configurationRisques = {
    stateSave: false,
    order: [[0, "asc"]],
    pagingType: "simple_numbers",
    searching: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
    columnDefs: [{
        targets: [2, 3],
        orderable: false,
        searchable: false
    }],
    language: {
        search: "Rechercher :",
        lengthMenu: "_MENU_ niveaux par page",
        info: "Niveaux _START_ à _END_ sur _TOTAL_",
        infoEmpty: "Aucun niveau de risque",
        emptyTable: "Aucun niveau de risque disponible",
        zeroRecords: "Aucun niveau de risque trouvé",
        paginate: {previous: "Précédent", next: "Suivant"}
    }
};

function loadGestionRisques() {
    $("#divModalSaving").show();
    $.ajax({
        type: "POST",
        url: "route.php",
        data: {page: "liste_niveaux_risque", bJSON: 1},
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        aOfRisques = Array.isArray(result) ? result : [];
        rebuildDataTableRisques();
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant le chargement des niveaux de risque.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function constructTableRisques() {
    const tbody = $("#table_risques tbody");
    tbody.empty();

    for (let i = 0; i < aOfRisques.length; i++) {
        const risque = aOfRisques[i];
        const ligne = $("<tr>");

        $("<td>").text(risque["id_niveau_risque"]).appendTo(ligne);
        $("<td>").text(risque["risque"]).appendTo(ligne);

        const celluleModification = $("<td>");
        $("<button>", {
            type: "button",
            class: "btn btn-edit btn-table",
            text: "Modifier"
        }).on("click", function () {
            editRisque(i);
        }).appendTo(celluleModification);
        celluleModification.appendTo(ligne);

        const celluleSuppression = $("<td>");
        $("<button>", {
            type: "button",
            class: "btn btn-delete btn-table",
            text: "Supprimer"
        }).on("click", function () {
            supprimRisqueServeur(i);
        }).appendTo(celluleSuppression);
        celluleSuppression.appendTo(ligne);

        tbody.append(ligne);
    }
}

function rebuildDataTableRisques() {
    if ($.fn.DataTable.isDataTable("#table_risques")) {
        tableRisques.clear();
        tableRisques.destroy();
    }
    constructTableRisques();
    tableRisques = $("#table_risques").DataTable(configurationRisques);
}

function ajoutRisqueServeur() {
    const formulaire = document.getElementById("form_risque");
    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    const nomRisque = $("#risque").val().trim();
    $("#divModalSaving").show();
    $.ajax({
        type: "POST",
        url: "route.php",
        data: {page: "save_risque", risque: nomRisque, bJSON: 1},
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (result["save_risque"] && result["save_risque"]["error"] !== "") {
            alert(result["save_risque"]["error"]);
            return;
        }
        if (!result["id_risque_created"]) {
            alert("Le serveur n'a pas retourné l'identifiant du niveau de risque.");
            return;
        }
        ajoutRisque(result["id_risque_created"], nomRisque);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant l'ajout du niveau de risque.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function ajoutRisque(idRisque, nomRisque) {
    aOfRisques.push({id_niveau_risque: idRisque, risque: nomRisque});
    rebuildDataTableRisques();
    clearFormRisque();
}

function majRisqueServeur() {
    const formulaire = document.getElementById("form_risque");
    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }
    if (indiceEditionRisque === null || !aOfRisques[indiceEditionRisque]) {
        alert("Aucun niveau de risque n'est sélectionné.");
        return;
    }

    const nomRisque = $("#risque").val().trim();
    $("#divModalSaving").show();
    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "update_risque",
            id_niveau_risque: $("#id_niveau_risque").val(),
            risque: nomRisque,
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["update_risque"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }
        if (result["update_risque"]["error"] !== "") {
            alert(result["update_risque"]["error"]);
            return;
        }
        majRisque(nomRisque);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la modification du niveau de risque.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function majRisque(nomRisque) {
    aOfRisques[indiceEditionRisque]["risque"] = nomRisque;
    rebuildDataTableRisques();
    clearFormRisque();
}

function supprimRisqueServeur(indiceTableau) {
    if (!aOfRisques[indiceTableau]) {
        alert("Le niveau de risque sélectionné est introuvable.");
        return;
    }

    const risque = aOfRisques[indiceTableau];
    if (!confirm('Supprimer le niveau de risque "' + risque["risque"] + '" ?')) {
        return;
    }

    $("#divModalSaving").show();
    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "supprime_risque",
            id_niveau_risque: risque["id_niveau_risque"],
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["supprime_risque"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }
        if (result["supprime_risque"]["error"] !== "") {
            alert(result["supprime_risque"]["error"]);
            return;
        }
        supprimRisque(indiceTableau);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la suppression du niveau de risque.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function supprimRisque(indiceTableau) {
    aOfRisques.splice(indiceTableau, 1);
    clearFormRisque();
    rebuildDataTableRisques();
}

function editRisque(indiceTableau) {
    if (!aOfRisques[indiceTableau]) {
        alert("Le niveau de risque sélectionné est introuvable.");
        return;
    }
    indiceEditionRisque = indiceTableau;
    const risque = aOfRisques[indiceTableau];
    $("#id_niveau_risque").val(risque["id_niveau_risque"]);
    $("#risque").val(risque["risque"]);
    $("#form_title_risque").text("Modifier le niveau de risque");
    $("#btn_ajouter_risque").addClass("hide");
    $("#btn_modifier_risque").removeClass("hide");
    $("#btn_annuler_risque").removeClass("hide");
    $("#risque").trigger("focus");
}

function clearFormRisque() {
    indiceEditionRisque = null;
    $("#id_niveau_risque").val("");
    $("#risque").val("");
    $("#form_title_risque").text("Ajouter un niveau de risque");
    $("#btn_ajouter_risque").removeClass("hide");
    $("#btn_modifier_risque").addClass("hide");
    $("#btn_annuler_risque").addClass("hide");
    $("#risque").trigger("focus");
}

$(document).ready(function () {
    loadGestionRisques();
});

let aOfStrategies = [];
let tableStrategies;
let indiceEditionStrategie = null;

const configurationStrategies = {
    stateSave: false,
    order: [[1, "asc"]],
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
        lengthMenu: "_MENU_ stratégies par page",
        info: "Stratégies _START_ à _END_ sur _TOTAL_",
        infoEmpty: "Aucune stratégie",
        emptyTable: "Aucune stratégie disponible",
        zeroRecords: "Aucune stratégie trouvée",
        paginate: {
            previous: "Précédent",
            next: "Suivant"
        }
    }
};

function loadGestionStrategies() {
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {page: "liste_strategies", bJSON: 1},
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        aOfStrategies = Array.isArray(result) ? result : [];
        rebuildDataTableStrategies();
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant le chargement des stratégies.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function constructTableStrategies() {
    const tbody = $("#table_strategies tbody");
    tbody.empty();

    for (let i = 0; i < aOfStrategies.length; i++) {
        const strategie = aOfStrategies[i];
        const ligne = $("<tr>");

        $("<td>").text(strategie["id_strategies"]).appendTo(ligne);
        $("<td>").text(strategie["nom_strategies"]).appendTo(ligne);

        const celluleModification = $("<td>");
        $("<button>", {
            type: "button",
            class: "btn btn-edit btn-table",
            text: "Modifier"
        })
        .on("click", function () {
            editStrategie(i);
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
            supprimStrategieServeur(i);
        })
        .appendTo(celluleSuppression);
        celluleSuppression.appendTo(ligne);

        tbody.append(ligne);
    }
}

function rebuildDataTableStrategies() {
    if ($.fn.DataTable.isDataTable("#table_strategies")) {
        tableStrategies.clear();
        tableStrategies.destroy();
    }

    constructTableStrategies();
    tableStrategies = $("#table_strategies").DataTable(
        configurationStrategies
    );
}

function ajoutStrategieServeur() {
    const formulaire = document.getElementById("form_strategie");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    const nomStrategie = $("#nom_strategies").val().trim();
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "save_strategie",
            nom_strategies: nomStrategie,
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (
            result["save_strategie"]
            && result["save_strategie"]["error"] !== ""
        ) {
            alert(result["save_strategie"]["error"]);
            return;
        }

        if (!result["id_strategie_created"]) {
            alert("Le serveur n'a pas retourné l'identifiant de la stratégie.");
            return;
        }

        ajoutStrategie(result["id_strategie_created"], nomStrategie);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant l'ajout de la stratégie.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function ajoutStrategie(idStrategie, nomStrategie) {
    aOfStrategies.push({
        id_strategies: idStrategie,
        nom_strategies: nomStrategie
    });
    rebuildDataTableStrategies();
    clearFormStrategie();
}

function majStrategieServeur() {
    const formulaire = document.getElementById("form_strategie");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    if (
        indiceEditionStrategie === null
        || !aOfStrategies[indiceEditionStrategie]
    ) {
        alert("Aucune stratégie n'est sélectionnée.");
        return;
    }

    const nomStrategie = $("#nom_strategies").val().trim();
    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "update_strategie",
            id_strategies: $("#id_strategies").val(),
            nom_strategies: nomStrategie,
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["update_strategie"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }
        if (result["update_strategie"]["error"] !== "") {
            alert(result["update_strategie"]["error"]);
            return;
        }
        majStrategie(nomStrategie);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la modification de la stratégie.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function majStrategie(nomStrategie) {
    aOfStrategies[indiceEditionStrategie]["nom_strategies"] = nomStrategie;
    rebuildDataTableStrategies();
    clearFormStrategie();
}

function supprimStrategieServeur(indiceTableau) {
    if (!aOfStrategies[indiceTableau]) {
        alert("La stratégie sélectionnée est introuvable.");
        return;
    }

    const strategie = aOfStrategies[indiceTableau];
    if (!confirm('Supprimer la stratégie "' + strategie["nom_strategies"] + '" ?')) {
        return;
    }

    $("#divModalSaving").show();
    $.ajax({
        type: "POST",
        url: "route.php",
        data: {
            page: "supprime_strategie",
            id_strategies: strategie["id_strategies"],
            bJSON: 1
        },
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["supprime_strategie"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }
        if (result["supprime_strategie"]["error"] !== "") {
            alert(result["supprime_strategie"]["error"]);
            return;
        }
        supprimStrategie(indiceTableau);
    })
    .fail(function (error) {
        console.error(error.responseText);
        alert("Erreur pendant la suppression de la stratégie.");
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

function supprimStrategie(indiceTableau) {
    aOfStrategies.splice(indiceTableau, 1);
    clearFormStrategie();
    rebuildDataTableStrategies();
}

function editStrategie(indiceTableau) {
    if (!aOfStrategies[indiceTableau]) {
        alert("La stratégie sélectionnée est introuvable.");
        return;
    }

    indiceEditionStrategie = indiceTableau;
    const strategie = aOfStrategies[indiceTableau];

    $("#id_strategies").val(strategie["id_strategies"]);
    $("#nom_strategies").val(strategie["nom_strategies"]);
    $("#form_title_strategie").text("Modifier la stratégie");
    $("#btn_ajouter_strategie").addClass("hide");
    $("#btn_modifier_strategie").removeClass("hide");
    $("#btn_annuler_strategie").removeClass("hide");
    $("#nom_strategies").trigger("focus");
}

function clearFormStrategie() {
    indiceEditionStrategie = null;
    $("#id_strategies").val("");
    $("#nom_strategies").val("");
    $("#form_title_strategie").text("Ajouter une stratégie");
    $("#btn_ajouter_strategie").removeClass("hide");
    $("#btn_modifier_strategie").addClass("hide");
    $("#btn_annuler_strategie").addClass("hide");
    $("#nom_strategies").trigger("focus");
}

$(document).ready(function () {
    loadGestionStrategies();
});

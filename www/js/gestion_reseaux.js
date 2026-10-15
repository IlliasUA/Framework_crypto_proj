let aOfReseaux = [];
let tableReseaux;
let indiceEditionReseau = null;

/**
 * Configuration commune de DataTable.
 */
const configurationReseaux = {
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
        lengthMenu: "_MENU_ réseaux par page",
        info: "Réseaux _START_ à _END_ sur _TOTAL_",
        infoEmpty: "Aucun réseau",
        emptyTable: "Aucun réseau disponible",
        zeroRecords: "Aucun réseau trouvé",
        paginate: {
            previous: "Précédent",
            next: "Suivant"
        }
    }
};

/**
 * Charge les réseaux depuis la base de données.
 */
function loadGestionReseaux() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_reseaux",
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
        aOfReseaux = Array.isArray(result) ? result : [];

        rebuildDataTableReseaux();
    })
    .fail(function (error) {
        console.error(error.responseText);

        alert(
            "Erreur pendant le chargement des réseaux."
        );
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

/**
 * Construit les lignes HTML du tableau à partir de aOfReseaux.
 */
function constructTableReseaux() {
    const tbody = $("#table_reseaux tbody");

    tbody.empty();

    for (let i = 0; i < aOfReseaux.length; i++) {
        const reseau = aOfReseaux[i];
        const ligne = $("<tr>");

        $("<td>")
            .text(reseau["id_reseau"])
            .appendTo(ligne);

        $("<td>")
            .text(reseau["nom_blockchain"])
            .appendTo(ligne);

        const celluleModification = $("<td>");

        $("<button>", {
            type: "button",
            class: "btn btn-edit btn-table",
            text: "Modifier"
        })
        .on("click", function () {
            editReseau(i);
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
            supprimReseauServeur(i);
        })
        .appendTo(celluleSuppression);

        celluleSuppression.appendTo(ligne);

        tbody.append(ligne);
    }
}

/**
 * Reconstruit DataTable après une modification du tableau.
 */
function rebuildDataTableReseaux() {
    if ($.fn.DataTable.isDataTable("#table_reseaux")) {
        tableReseaux.clear();
        tableReseaux.destroy();
    }

    constructTableReseaux();

    tableReseaux = $("#table_reseaux").DataTable(
        configurationReseaux
    );
}

/**
 * Envoie le nouveau réseau au serveur.
 */
function ajoutReseauServeur() {
    const formulaire =
        document.getElementById("form_reseau");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    const nomBlockchain =
        $("#nom_blockchain").val().trim();

    const datas = {
        page: "save_reseau",
        nom_blockchain: nomBlockchain,
        bJSON: 1
    };

    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (
            result["save_reseau"]
            && result["save_reseau"]["error"] !== ""
        ) {
            alert(result["save_reseau"]["error"]);
            return;
        }

        if (!result["id_reseau_created"]) {
            alert(
                "Le serveur n'a pas retourné l'identifiant du réseau."
            );
            return;
        }

        ajoutReseau(
            result["id_reseau_created"],
            nomBlockchain
        );
    })
    .fail(function (error) {
        console.error(error.responseText);

        alert(
            "Erreur pendant l'ajout du réseau."
        );
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

/**
 * Ajoute le réseau reçu du serveur au tableau JavaScript.
 */
function ajoutReseau(idReseau, nomBlockchain) {
    aOfReseaux.push({
        id_reseau: idReseau,
        nom_blockchain: nomBlockchain
    });

    rebuildDataTableReseaux();
    clearFormReseau();
}

/**
 * Envoie les modifications du réseau au serveur.
 */
function majReseauServeur() {
    const formulaire =
        document.getElementById("form_reseau");

    if (!formulaire.checkValidity()) {
        formulaire.reportValidity();
        return;
    }

    if (
        indiceEditionReseau === null
        || !aOfReseaux[indiceEditionReseau]
    ) {
        alert("Aucun réseau n'est sélectionné.");
        return;
    }

    const idReseau = $("#id_reseau").val();
    const nomBlockchain =
        $("#nom_blockchain").val().trim();

    const datas = {
        page: "update_reseau",
        id_reseau: idReseau,
        nom_blockchain: nomBlockchain,
        bJSON: 1
    };

    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["update_reseau"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }

        if (result["update_reseau"]["error"] !== "") {
            alert(result["update_reseau"]["error"]);
            return;
        }

        majReseau(nomBlockchain);
    })
    .fail(function (error) {
        console.error(error.responseText);

        alert(
            "Erreur pendant la modification du réseau."
        );
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

/**
 * Met à jour le réseau dans le tableau JavaScript.
 */
function majReseau(nomBlockchain) {
    aOfReseaux[indiceEditionReseau]["nom_blockchain"] =
        nomBlockchain;

    rebuildDataTableReseaux();
    clearFormReseau();
}

/**
 * Demande au serveur de supprimer un réseau.
 */
function supprimReseauServeur(indiceTableau) {
    if (!aOfReseaux[indiceTableau]) {
        alert("Le réseau sélectionné est introuvable.");
        return;
    }

    const reseau = aOfReseaux[indiceTableau];

    const confirmation = confirm(
        'Supprimer le réseau "'
        + reseau["nom_blockchain"]
        + '" ?'
    );

    if (!confirmation) {
        return;
    }

    const datas = {
        page: "supprime_reseau",
        id_reseau: reseau["id_reseau"],
        bJSON: 1
    };

    $("#divModalSaving").show();

    $.ajax({
        type: "POST",
        url: "route.php",
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        if (!result["supprime_reseau"]) {
            alert("Réponse incorrecte du serveur.");
            return;
        }

        if (result["supprime_reseau"]["error"] !== "") {
            alert(result["supprime_reseau"]["error"]);
            return;
        }

        supprimReseau(indiceTableau);
    })
    .fail(function (error) {
        console.error(error.responseText);

        alert(
            "Erreur pendant la suppression du réseau."
        );
    })
    .always(function () {
        $("#divModalSaving").hide();
    });
}

/**
 * Retire le réseau du tableau JavaScript.
 */
function supprimReseau(indiceTableau) {
    aOfReseaux.splice(indiceTableau, 1);

    clearFormReseau();
    rebuildDataTableReseaux();
}

/**
 * Place le réseau sélectionné dans le formulaire.
 */
function editReseau(indiceTableau) {
    if (!aOfReseaux[indiceTableau]) {
        alert("Le réseau sélectionné est introuvable.");
        return;
    }

    indiceEditionReseau = indiceTableau;

    const reseau = aOfReseaux[indiceTableau];

    $("#id_reseau").val(reseau["id_reseau"]);
    $("#nom_blockchain").val(reseau["nom_blockchain"]);

    $("#form_title_reseau").text("Modifier le réseau");

    $("#btn_ajouter_reseau").addClass("hide");
    $("#btn_modifier_reseau").removeClass("hide");
    $("#btn_annuler_reseau").removeClass("hide");

    $("#nom_blockchain").trigger("focus");
}

/**
 * Vide le formulaire et quitte le mode modification.
 */
function clearFormReseau() {
    indiceEditionReseau = null;

    $("#id_reseau").val("");
    $("#nom_blockchain").val("");

    $("#form_title_reseau").text("Ajouter un réseau");

    $("#btn_ajouter_reseau").removeClass("hide");
    $("#btn_modifier_reseau").addClass("hide");
    $("#btn_annuler_reseau").addClass("hide");

    $("#nom_blockchain").trigger("focus");
}

$(document).ready(function () {
    loadGestionReseaux();
});
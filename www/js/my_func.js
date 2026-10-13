const configuration = {
    order: [[0, "asc"]],
    pageLength: -1,
    pagingType: "simple_numbers",
    searching: true,
    lengthMenu: [
        [5, 10, -1],
        ["5", "10", "Tous"]
    ],
    language: {
        info: "Cryptomonnaies _START_ à _END_ sur _TOTAL_",
        emptyTable: "Aucune cryptomonnaie dans le portfolio",
        lengthMenu: "_MENU_ cryptomonnaies par page",
        search: "Rechercher :",
        zeroRecords: "Aucun résultat trouvé",
        paginate: {
            previous: "Précédent",
            next: "Suivant"
        },
        infoFiltered: "(filtré sur _MAX_ cryptomonnaies)",
        infoEmpty: "Cryptomonnaies 0 à 0 sur 0"
    },
    columnDefs: [
        {
            targets: [5],
            orderable: false
        },
        {
            targets: [2, 3, 4, 5],
            searchable: false
        }
    ]
};

let tableCryptos;
let iIndiceEdited = null;

function echapperHTML(texte) {
    return $("<div>").text(texte || "").html();
}

function formaterPrix(nombre) {
    return Number(nombre).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }) + " $";
}

function constructTable() {
    let sHTML = "";

    sHTML += "<thead><tr>";
    sHTML += "<th>Crypto</th>";
    sHTML += "<th>Réseau</th>";
    sHTML += "<th>Quantité</th>";
    sHTML += "<th>Prix d'achat</th>";
    sHTML += "<th>Valeur</th>";
    sHTML += "<th>Actions</th>";
    sHTML += "</tr></thead><tbody>";

    for (let i = 0; i < aOfCryptos.length; i++) {
        let crypto = aOfCryptos[i];

        // Les données HTML commencent à l'indice 1.
        if (!crypto) continue;

        let valeur = Number(crypto["quantite"]) * Number(crypto["prix"]);

        sHTML += "<tr>";
        sHTML += "<td>";
        sHTML += "<strong>" + echapperHTML(crypto["nom"]) + "</strong>";
        sHTML += "<br><small>" + echapperHTML(crypto["symbole"]) + "</small>";
        sHTML += "</td>";
        sHTML += "<td>" + echapperHTML(crypto["reseau"]) + "</td>";
        sHTML += "<td>" + crypto["quantite"] + "</td>";
        sHTML += "<td>" + formaterPrix(crypto["prix"]) + "</td>";
        sHTML += "<td>" + formaterPrix(valeur) + "</td>";
        sHTML += "<td>";

        sHTML +=
            '<button type="button" class="btn btn-edit btn-table" ' +
            'onclick="editCrypto(' + i + ')">Modifier</button>';

        sHTML +=
            '<button type="button" class="btn btn-delete btn-table" ' +
            'onclick="supprimerCrypto(' + i + ')">Supprimer</button>';

        sHTML += "</td></tr>";
    }

    sHTML += "</tbody>";
    $("#table_cryptos").html(sHTML);
}

function rebuildTableau() {
    if ($.fn.DataTable.isDataTable("#table_cryptos")) {
        tableCryptos.clear();
        tableCryptos.destroy();
    }

    constructTable();
    tableCryptos = $("#table_cryptos").DataTable(configuration);
    calculerPortfolio();
}

function calculerPortfolio() {
    let valeurTotale = 0;

    for (let i = 0; i < aOfCryptos.length; i++) {
        if (!aOfCryptos[i]) continue;

        valeurTotale +=
            Number(aOfCryptos[i]["quantite"]) *
            Number(aOfCryptos[i]["prix"]);
    }

    $("#valeur_portfolio").text(formaterPrix(valeurTotale));
}

function afficherFormulaire() {
    iIndiceEdited = null;

    $("#form_crypto")[0].reset();
    $("#action_page").val("save_crypto");
    $("#id_crypto").val("");
    $("#section_formulaire").removeClass("hide");
    $("#titre_formulaire").text("Ajouter une cryptomonnaie");
    $("#btn_ajouter").removeClass("hide");
    $("#btn_modifier").addClass("hide");

    document.getElementById("section_formulaire").scrollIntoView({
        behavior: "smooth",
        block: "start"
    });
}

function annulerModification() {
    iIndiceEdited = null;

    $("#form_crypto")[0].reset();
    $("#action_page").val("save_crypto");
    $("#id_crypto").val("");
    $("#section_formulaire").addClass("hide");
    $("#btn_ajouter").removeClass("hide");
    $("#btn_modifier").addClass("hide");
}

function editCrypto(iIndiceToEdit) {
    iIndiceEdited = iIndiceToEdit;

    const crypto = aOfCryptos[iIndiceToEdit];

    if (!crypto) {
        return;
    }

    /*
     * Remplissage des champs principaux.
     */
    $("#nom_crypto").val(crypto["nom"]);
    $("#symbole_crypto").val(crypto["symbole"]);
    $("#reseau").val(String(crypto["id_reseau"]));
    $("#quantite").val(crypto["quantite"]);
    $("#prix_achat").val(crypto["prix"]);
    $("#date_achat").val(crypto["dateAchat"]);
    $("#notes").val(crypto["notes"] || "");

    /*
     * Sélection du niveau de risque.
     */
    $('input[name="id_niveau_risque"]').prop("checked", false);

    $(
        'input[name="id_niveau_risque"][value="' +
        crypto["id_niveau_risque"] +
        '"]'
    ).prop("checked", true);

    /*
     * Sélection des stratégies.
     *
     * La base transmet par exemple "1,3,5".
     * split(",") transforme cette chaîne en ["1", "3", "5"].
     */
    $('input[name="strategies[]"]').prop("checked", false);

    const idsStrategies = String(
        crypto["id_strategies"] || ""
    )
        .split(",")
        .filter(Boolean);

    for (let i = 0; i < idsStrategies.length; i++) {
        $(
            'input[name="strategies[]"][value="' +
            idsStrategies[i] +
            '"]'
        ).prop("checked", true);
    }

    /*
     * Sélection des notifications.
     */
    $('input[name="notifications[]"]').prop("checked", false);

    const idsNotifications = String(
        crypto["id_notifications"] || ""
    )
        .split(",")
        .filter(Boolean);

    for (let i = 0; i < idsNotifications.length; i++) {
        $(
            'input[name="notifications[]"][value="' +
            idsNotifications[i] +
            '"]'
        ).prop("checked", true);
    }

    /*
     * Préparation de la forme pour la modification.
     */
    $("#titre_formulaire").text("Modifier la cryptomonnaie");
    $("#action_page").val("update_crypto");
    $("#id_crypto").val(crypto["id_cryptomonnaie"]);

    $("#btn_ajouter").addClass("hide");
    $("#btn_modifier").removeClass("hide");
    $("#section_formulaire").removeClass("hide");

    /*
     * Déplacement vers la forme.
     */
    document
        .getElementById("section_formulaire")
        .scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
}

function supprimerCrypto(iIndiceToDelete) {
    let crypto = aOfCryptos[iIndiceToDelete];

    let confirmation = confirm(
        "Voulez-vous vraiment supprimer " +
        crypto["nom"] +
        " du portfolio ?"
    );

    if (!confirmation) return;

    const form = document.createElement("form");
    form.method = "post";
    form.action = "route.php";
    form.innerHTML =
        '<input type="hidden" name="page" value="supprime_crypto">' +
        '<input type="hidden" name="id_crypto" value="' +
        Number(crypto["id_cryptomonnaie"]) + '">';
    document.body.appendChild(form);
    form.submit();
}

function loadCryptos() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_crypto",
        bJSON: 1
    };

    $.ajax({
        type: "POST",
        url: "route.php",
        async: true,
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        aOfCryptos = result;

        constructTable();
        tableCryptos = $("#table_cryptos").DataTable(configuration);
        calculerPortfolio();

        $("#divModalSaving").hide();
    })
    .fail(function (error) {
        $("#divModalSaving").hide();
        console.error(error.responseText);
        alert("Erreur pendant le chargement des cryptomonnaies.");
    });
}

function loadReseaux() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_reseaux",
        bJSON: 1
    };

    $.ajax({
        type: "POST",
        url: "route.php",
        async: true,
        data: datas,
        dataType: "json",
        cache: false
    })
    .done(function (result) {
        for (var i = 0; i < result.length; i++) {
            var reseau = result[i];
            var option = $("<option>")
                .val(reseau["id_reseau"])
                .text(reseau["nom_blockchain"]);
            $("#reseau").append(option);
        }
        $("#divModalSaving").hide();
    })
    .fail(function (error) {
        $("#divModalSaving").hide();
        console.error(error.responseText);
        alert("Erreur pendant le chargement des réseaux.");
    });
}

$(document).ready(function() {
    loadReseaux();
    loadCryptos();
});


let tableReseaux;

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
        const tbody = $("#table_reseaux tbody");

        // Évite les doublons en cas de rechargement.
        tbody.empty();

        for (let i = 0; i < result.length; i++) {
            const reseau = result[i];

            const ligne = $("<tr>");

            $("<td>")
                .text(reseau["id_reseau"])
                .appendTo(ligne);

            $("<td>")
                .text(reseau["nom_blockchain"])
                .appendTo(ligne);

            tbody.append(ligne);
        }

        tableReseaux = $("#table_reseaux").DataTable({
            order: [[1, "asc"]],    
            pageLength: 10,
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
        });

        $("#divModalSaving").hide();
    })
    .fail(function (error) {
        $("#divModalSaving").hide();

        console.error(error.responseText);

        alert(
            "Erreur pendant le chargement des réseaux."
        );
    });
}

$(document).ready(function () {
    loadGestionReseaux();
});
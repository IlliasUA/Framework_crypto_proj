let tableRisques;

/**
 * Charge les niveaux de risque depuis la base de données.
 */
function loadGestionRisques() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_niveaux_risque",
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
        const tbody = $("#table_risques tbody");

        // Évite les doublons en cas de rechargement.
        tbody.empty();

        for (let i = 0; i < result.length; i++) {
            const risque = result[i];

            const ligne = $("<tr>");

            $("<td>")
                .text(risque["id_niveau_risque"])
                .appendTo(ligne);

            $("<td>")
                .text(risque["risque"])
                .appendTo(ligne);

            tbody.append(ligne);
        }

        tableRisques =
            $("#table_risques").DataTable({
                order: [[0, "asc"]],
                pageLength: 10,
                language: {
                    search: "Rechercher :",
                    lengthMenu:
                        "_MENU_ niveaux par page",
                    info:
                        "Niveaux _START_ à _END_ sur _TOTAL_",
                    infoEmpty: "Aucun niveau de risque",
                    emptyTable:
                        "Aucun niveau de risque disponible",
                    zeroRecords:
                        "Aucun niveau de risque trouvé",
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
            "Erreur pendant le chargement des niveaux de risque."
        );
    });
}

$(document).ready(function () {
    loadGestionRisques();
});
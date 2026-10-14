let tableStrategies;

/**
 * Charge les stratégies depuis la base de données.
 */
function loadGestionStrategies() {
    $("#divModalSaving").show();

    const datas = {
        page: "liste_strategies",
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
        const tbody = $("#table_strategies tbody");

        // Évite les doublons en cas de rechargement.
        tbody.empty();

        for (let i = 0; i < result.length; i++) {
            const strategie = result[i];

            const ligne = $("<tr>");

            $("<td>")
                .text(strategie["id_strategies"])
                .appendTo(ligne);

            $("<td>")
                .text(strategie["nom_strategies"])
                .appendTo(ligne);

            tbody.append(ligne);
        }

        tableStrategies =
            $("#table_strategies").DataTable({
                order: [[1, "asc"]],
                pageLength: 10,
                language: {
                    search: "Rechercher :",
                    lengthMenu:
                        "_MENU_ stratégies par page",
                    info:
                        "Stratégies _START_ à _END_ sur _TOTAL_",
                    infoEmpty: "Aucune stratégie",
                    emptyTable:
                        "Aucune stratégie disponible",
                    zeroRecords:
                        "Aucune stratégie trouvée",
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
            "Erreur pendant le chargement des stratégies."
        );
    });
}

$(document).ready(function () {
    loadGestionStrategies();
});
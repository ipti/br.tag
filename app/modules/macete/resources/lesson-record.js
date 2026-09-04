(function ($) {
    // O campo "Turma" do resumo reflete a turma escolhida aqui no próprio
    // registro (classroom_fk do registro), não a turma do plano MACETE
    // selecionado — o plano pode nem ter turma vinculada.
    function updateClassroomSummary() {
        var data = $(".js-macete-record-classroom").select2("data");
        $(".js-macete-plan-summary [data-summary-field='classroom']").text(data ? data.text : "");
    }

    // syncAbilities só deve ser true quando o professor troca o plano à
    // mão: no carregamento inicial da edição de um registro já existente,
    // as habilidades marcadas são as do próprio registro (podem já ter
    // sido ajustadas pelo professor) e não devem ser substituídas pelas
    // do plano só porque a tela abriu com um plano pré-selecionado.
    function updatePlanSummary(planId, syncAbilities) {
        if (!planId) {
            $(".js-macete-plan-summary [data-summary-field]").not("[data-summary-field='classroom']").text("");
            return;
        }

        $.ajax({
            type: "GET",
            url: "?r=macete/lessonsplan/getPlan&id=" + encodeURIComponent(planId),
        }).done(function (response) {
            var data = JSON.parse(response);
            $(".js-macete-plan-summary [data-summary-field='theme']").text(data.theme || "");
            $(".js-macete-plan-summary [data-summary-field='stage']").text(data.stage || "");
            $(".js-macete-plan-summary [data-summary-field='discipline']").text(data.discipline || "");
            $(".js-macete-plan-summary [data-summary-field='abilities']").text(data.abilities || "");

            if (syncAbilities) {
                $(".js-macete-abilities-selected").empty();
                (data.abilityList || []).forEach(function (ability) {
                    window.Macete.addAbility(ability);
                });
            }
        });
    }

    $(document).on("change", ".js-macete-plan-select", function () {
        updatePlanSummary($(this).val(), true);
    });

    $(document).on("change", ".js-macete-record-classroom", updateClassroomSummary);

    $(document).ready(function () {
        if (typeof $(".js-macete-date").mask === "function") {
            $(".js-macete-date").mask("99/99/9999", { placeholder: "DD/MM/AAAA" });
        }

        var selectedPlan = $(".js-macete-plan-select").val();
        if (selectedPlan) {
            updatePlanSummary(selectedPlan, false);
        }

        updateClassroomSummary();
    });
})(jQuery);


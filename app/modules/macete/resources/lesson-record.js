(function ($) {
    function updatePlanSummary(planId) {
        if (!planId) {
            $(".js-macete-plan-summary [data-summary-field]").text("");
            return;
        }

        $.ajax({
            type: "GET",
            url: "?r=macete/lessonPlan/getPlan&id=" + encodeURIComponent(planId),
        }).done(function (response) {
            var data = JSON.parse(response);
            $(".js-macete-plan-summary [data-summary-field='theme']").text(data.theme || "");
            $(".js-macete-plan-summary [data-summary-field='stage']").text(data.stage || "");
            $(".js-macete-plan-summary [data-summary-field='discipline']").text(data.discipline || "");
            $(".js-macete-plan-summary [data-summary-field='classroom']").text(data.classroom || "");
            $(".js-macete-plan-summary [data-summary-field='abilities']").text(data.abilities || "");
        });
    }

    $(document).on("change", ".js-macete-plan-select", function () {
        updatePlanSummary($(this).val());
    });

    $(document).ready(function () {
        if (typeof $(".js-macete-date").mask === "function") {
            $(".js-macete-date").mask("99/99/9999", { placeholder: "DD/MM/AAAA" });
        }

        var selectedPlan = $(".js-macete-plan-select").val();
        if (selectedPlan) {
            updatePlanSummary(selectedPlan);
        }
    });
})(jQuery);


(function ($) {
    function parseResponse(response) {
        if ($.isArray(response)) {
            return response;
        }

        try {
            return JSON.parse(response);
        } catch (error) {
            return [];
        }
    }

    function normalizeIds(value) {
        if ($.isArray(value)) {
            return $.grep(value, function (item) {
                return item !== null && item !== "";
            });
        }

        if (value === null || value === undefined || value === "") {
            return [];
        }

        return [value];
    }

    function refreshStageFields(stageIds) {
        var selected = {};
        $.each(stageIds, function (_, stageId) {
            selected[String(stageId)] = true;
        });

        $(".js-macete-stage-field").each(function () {
            var field = $(this);
            var active = selected[String(field.data("stage-id"))] === true;
            field.toggleClass("hide", !active);
            field.find(":input").prop("disabled", !active);
        });

        $(".js-macete-stage-empty").toggleClass("hide", stageIds.length > 0);
    }

    function updateDisciplines(stageIds) {
        stageIds = normalizeIds(stageIds);
        var disciplineSelect = $("select.js-macete-discipline");
        if (!disciplineSelect.length) {
            return;
        }

        disciplineSelect.val("");
        if (!stageIds.length) {
            disciplineSelect.html("<option value=\"\">Selecione o componente</option>");
            if (typeof disciplineSelect.select2 === "function") {
                disciplineSelect.select2("val", "");
            } else {
                disciplineSelect.val("");
            }
            return;
        }

        $.ajax({
            type: "POST",
            url: "?r=macete/lessonPlan/getDisciplines",
            dataType: "json",
            cache: false,
            data: { stage: stageIds },
        }).done(function (response) {
            var data = parseResponse(response);
            var initialValue = disciplineSelect.attr("data-initial-value") || "";
            var selectedValue = "";
            var options = "<option value=\"\">Selecione o componente</option>";

            $.each(data, function () {
                var selected = initialValue !== "" && String(initialValue) === String(this.id);
                selectedValue = selected ? String(this.id) : selectedValue;
                options += "<option value=\"" + window.Macete.escapeHtml(this.id) + "\"" + (selected ? " selected" : "") + ">" + window.Macete.escapeHtml(this.name) + "</option>";
            });

            disciplineSelect.html(options);
            if (typeof disciplineSelect.select2 === "function") {
                disciplineSelect.select2("val", selectedValue);
            } else {
                disciplineSelect.val(selectedValue);
            }
            disciplineSelect.trigger("change.select2");
            disciplineSelect.attr("data-initial-value", "");
        });
    }

    function updateSidebar() {
        var disciplineSelect = $("select.js-macete-discipline");
        var stageSelect = $("select.js-macete-stage");
        var statusSelect = $("select.js-macete-status");
        var unitInput = $("input.js-macete-unit");
        var abilitiesCount = $(".js-macete-abilities-selected .ability-panel-option").length;

        var disciplineText = disciplineSelect.find("option:selected").text().trim();
        $(".js-macete-summary-discipline").text(disciplineText || "—");

        var stageOptions = stageSelect.find("option:selected");
        var stageText = $.map(stageOptions, function (el) {
            return $(el).text().trim();
        }).join(", ");
        $(".js-macete-summary-stage").text(stageText || "—");

        var unitText = unitInput.val() ? unitInput.val().trim() : "";
        $(".js-macete-summary-unit").text(unitText || "—");

        var statusOption = statusSelect.find("option:selected");
        var statusText = statusOption.text().trim();
        $(".js-macete-summary-status").text(statusText || "—");

        $(".js-macete-summary-abilities").text(
            abilitiesCount + (abilitiesCount === 1 ? " habilidade" : " habilidades")
        );
    }

    $(document).ready(function () {
        var stage = $("select.js-macete-stage");
        if (!stage.length) {
            return;
        }

        function handleStageChange() {
            var stageIds = normalizeIds(stage.val());
            refreshStageFields(stageIds);
            updateDisciplines(stageIds);
            updateSidebar();
        }

        // Binding direto ao elemento é necessário para funcionar com select2 v3.
        // O listener delegado via $(document) não captura corretamente o change
        // disparado pelo select2 após a inicialização.
        stage.on("change", handleStageChange);

        $("select.js-macete-discipline, select.js-macete-status").on("change", updateSidebar);
        $("input.js-macete-unit").on("input", updateSidebar);

        $(document).on("click", ".js-macete-remove-ability", function () {
            setTimeout(updateSidebar, 0);
        });

        // Observa adições de habilidades via mutação no container
        var abilitiesContainer = $(".js-macete-abilities-selected")[0];
        if (abilitiesContainer && window.MutationObserver) {
            new MutationObserver(updateSidebar).observe(abilitiesContainer, { childList: true });
        }

        handleStageChange();
        updateSidebar();
    });
})(jQuery);

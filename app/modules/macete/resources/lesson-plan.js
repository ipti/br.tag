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

    function initSelect(select) {
        if (typeof select.select2 === "function" && !select.data("select2")) {
            select.select2({ width: "resolve" });
        }
    }

    function getStageIds() {
        var ids = [];
        $("select.js-macete-stage-component-stage").each(function () {
            var value = $(this).val();
            if (value) {
                ids.push(String(value));
            }
        });
        return ids;
    }

    function refreshStageFields() {
        var selected = {};
        $.each(getStageIds(), function (_, stageId) {
            selected[stageId] = true;
        });

        $(".js-macete-stage-field").each(function () {
            var field = $(this);
            var active = selected[String(field.data("stage-id"))] === true;
            field.toggleClass("hide", !active);
            field.find(":input").prop("disabled", !active);
        });

        $(".js-macete-stage-empty").toggleClass("hide", getStageIds().length > 0);

        if (window.Macete && typeof window.Macete.initRichText === "function") {
            window.Macete.initRichText();
        }
    }

    function loadDisciplines(row, selectedValue, selectedStageId) {
        var stage = row.find("select.js-macete-stage-component-stage").first();
        var discipline = row.find("select.js-macete-stage-component-discipline").first();
        var stageId = selectedStageId || stage.val();

        if (!selectedValue && row.data("macete-loaded-stage-id") === String(stageId || "")) {
            return;
        }
        row.data("macete-loaded-stage-id", String(stageId || ""));

        if (discipline.data("select2")) {
            discipline.select2("destroy");
        }
        discipline.html('<option value="">Selecione a etapa primeiro</option>').val("");
        if (!stageId) {
            return;
        }

        var requestToken = (row.data("macete-discipline-request-token") || 0) + 1;
        row.data("macete-discipline-request-token", requestToken);

        $.ajax({
            type: "POST",
            url: "?r=macete/lessonPlan/getDisciplines",
            dataType: "json",
            cache: false,
            data: { stage: [stageId] },
        }).done(function (response) {
            if (row.data("macete-discipline-request-token") !== requestToken) {
                return;
            }
            var options = '<option value="">Selecione o componente</option>';
            $.each(parseResponse(response), function () {
                var selected = String(selectedValue || "") === String(this.id) ? " selected" : "";
                options += '<option value="' + window.Macete.escapeHtml(this.id) + '"' + selected + '>'
                    + window.Macete.escapeHtml(this.name) + '</option>';
            });
            discipline.html(options);
            initSelect(discipline);
            discipline.select2("val", selectedValue || "");
            updateSidebar();
        });
    }

    function updateSidebar() {
        var statusSelect = $("select.js-macete-status");
        var unitInput = $("input.js-macete-unit");
        var abilitiesCount = $(".js-macete-abilities-selected .ability-panel-option").length;
        var associations = [];

        $(".js-macete-stage-component-row").each(function () {
            var row = $(this);
            var stage = row.find("select.js-macete-stage-component-stage option:selected").text().trim();
            var discipline = row.find("select.js-macete-stage-component-discipline option:selected").text().trim();
            if (stage && stage !== "Selecione a etapa") {
                associations.push(stage + (discipline && discipline !== "Selecione o componente" ? " — " + discipline : ""));
            }
        });

        $(".js-macete-summary-discipline").text(associations.join(", ") || "—");
        $(".js-macete-summary-stage").text(associations.length + (associations.length === 1 ? " etapa" : " etapas"));
        $(".js-macete-summary-unit").text(unitInput.val() ? unitInput.val().trim() : "—");
        $(".js-macete-summary-status").text(statusSelect.find("option:selected").text().trim() || "—");
        $(".js-macete-summary-abilities").text(abilitiesCount + (abilitiesCount === 1 ? " habilidade" : " habilidades"));
    }

    function addStageComponent() {
        var template = $("#macete-stage-component-template").html();
        var index = $(".js-macete-stage-component-row").length;
        var row = $(template.replace(/__index__/g, index));
        $(".js-macete-stage-components").append(row);
        initSelect(row.find("select.js-macete-stage-component-stage"));
        initSelect(row.find("select.js-macete-stage-component-discipline"));
        bindStageComponentRow(row);
        updateSidebar();
    }

    function bindStageComponentRow(row) {
        row.find("select.js-macete-stage-component-stage").on("change", function () {
            loadDisciplines(row, null, $(this).val());
            refreshStageFields();
            updateSidebar();
        });
        row.find("select.js-macete-stage-component-discipline").on("change", updateSidebar);
    }

    function addMaterialRow(type) {
        var container = $(".js-macete-material-rows[data-material-type=\"" + type + "\"]");
        if (!container.length) {
            return;
        }

        var index = container.find(".js-macete-material-row").length;
        var template = $("#macete-material-template").html();
        var row = $(template.replace(/__type__/g, type).replace(/__index__/g, index));
        container.append(row);

        if (window.Macete && typeof window.Macete.initRichText === "function") {
            window.Macete.initRichText(row);
        }
    }

    $(document).ready(function () {
        $(".js-macete-stage-component-row").each(function () {
            bindStageComponentRow($(this));
        });
        if (!$(".js-macete-stage-component-row").length) {
            addStageComponent();
        }

        $(document).on("click", ".js-macete-add-stage-component", addStageComponent);
        $(document).on("click", ".js-macete-remove-stage-component", function () {
            $(this).closest(".js-macete-stage-component-row").remove();
            refreshStageFields();
            updateSidebar();
        });

        $(document).on("click", ".js-macete-add-material", function () {
            addMaterialRow($(this).data("material-type"));
        });
        $(document).on("click", ".js-macete-remove-material", function () {
            $(this).closest(".js-macete-material-row").remove();
        });
        $("select.js-macete-status").on("change", updateSidebar);
        $("input.js-macete-unit").on("input", updateSidebar);
        $(document).on("click", ".js-macete-remove-ability", function () { setTimeout(updateSidebar, 0); });

        var abilitiesContainer = $(".js-macete-abilities-selected")[0];
        if (abilitiesContainer && window.MutationObserver) {
            new MutationObserver(updateSidebar).observe(abilitiesContainer, { childList: true });
        }

        refreshStageFields();
        updateSidebar();
    });
})(jQuery);

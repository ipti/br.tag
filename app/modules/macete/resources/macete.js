(function ($) {
    window.Macete = window.Macete || {};

    window.Macete.escapeHtml = function (value) {
        return $("<div>").text(value || "").html();
    };

    window.Macete.initTabs = function () {
        $(document).on("click", ".js-macete-tab-link", function (event) {
            event.preventDefault();

            var target = $(this).attr("href");
            $(".js-macete-tab-link").closest(".t-tabs__item").removeClass("active");
            $(this).closest(".t-tabs__item").addClass("active");

            $(".js-macete-tab-panel").addClass("hide");
            $(target).removeClass("hide");
        });
    };

    window.Macete.initAbilitySelector = function () {
        var selector = $(".js-macete-ability-search");
        if (!selector.length || typeof selector.select2 !== "function") {
            return;
        }

        selector.select2({
            placeholder: "Informe o código ou descrição da habilidade",
            minimumInputLength: 3,
            ajax: {
                url: "?r=macete/ability/search",
                dataType: "json",
                quietMillis: 300,
                cache: true,
                data: function (term) {
                    return { q: term };
                },
                results: function (data) {
                    return { results: $.isArray(data) ? data : [] };
                },
            },
            formatSelection: function (state) {
                return "(" + state.code + ") " + state.description;
            },
            formatResult: function (state) {
                return "<div><b>(" + window.Macete.escapeHtml(state.code) + ")</b> " + window.Macete.escapeHtml(state.description) + "</div>";
            },
            escapeMarkup: function (markup) {
                return markup;
            },
        });
    };

    window.Macete.addAbility = function (ability) {
        if (!ability || !ability.id) {
            return;
        }

        var exists = $(".js-macete-abilities-selected .ability-panel-option-id[value='" + ability.id + "']").length > 0;
        if (exists) {
            return;
        }

        var html = ""
            + "<div class=\"ability-panel-option\">"
            + "<input type=\"hidden\" class=\"ability-panel-option-id\" name=\"abilities[]\" value=\"" + window.Macete.escapeHtml(ability.id) + "\">"
            + "<i class=\"fa fa-check-square\"></i>"
            + "<span>(<b>" + window.Macete.escapeHtml(ability.code) + "</b>) " + window.Macete.escapeHtml(ability.description) + "</span>"
            + "<i class=\"fa fa-remove remove-abilitie js-macete-remove-ability\"></i>"
            + "</div>";

        $(".js-macete-abilities-selected").append(html);
    };

    $(document).on("change", ".js-macete-ability-search", function () {
        var ability = $(this).select2("data");
        window.Macete.addAbility(ability);
        $(this).select2("val", "");
    });

    $(document).on("click", ".js-macete-remove-ability", function () {
        $(this).closest(".ability-panel-option").remove();
    });

    $(document).ready(function () {
        window.Macete.initTabs();
        window.Macete.initAbilitySelector();
    });
})(jQuery);

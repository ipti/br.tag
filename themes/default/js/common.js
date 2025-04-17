$(".modal").modal({ backdrop: "static", show: false, keyboard: false });

$(function () {
    // Sidebar menu collapsibles
    $("#menu .collapse")
        .on("show", function (e) {
            e.stopPropagation();
            $(this).parents(".hasSubmenu:first").addClass("active");
        })
        .on("hidden", function (e) {
            e.stopPropagation();
            $(this).parents(".hasSubmenu:first").removeClass("active");
        });

    // multi-level top menu
    $(".submenu")
        .hover(
            function () {
                $(this)
                    .children("ul")
                    .removeClass("submenu-hide")
                    .addClass("submenu-show");
            },
            function () {
                $(this)
                    .children("ul")
                    .removeClass(".submenu-show")
                    .addClass("submenu-hide");
            }
        )
        .find("a:first")
        .append(" &raquo; ");

    // tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // popovers
    $('[data-toggle="popover"]').popover();

    // print
    $('[data-toggle="print"]').click(function (e) {
        e.preventDefault();
        window.print();
    });

    $(".checkboxs tbody").on("click", "tr.selectable", function (e) {
        var c = $(this).find(":checkbox");
        var s = $(e.srcElement);

        if (e.srcElement.nodeName == "INPUT") {
            if (c.is(":checked")) $(this).addClass("selected");
            else $(this).removeClass("selected");
        } else if (
            e.srcElement.nodeName != "TD" &&
            e.srcElement.nodeName != "TR" &&
            e.srcElement.nodeName != "DIV"
        ) {
            return true;
        } else {
            if (c.is(":checked")) {
                c.prop("checked", false).parent().removeClass("checked");
                $(this).removeClass("selected");
            } else {
                c.prop("checked", true).parent().addClass("checked");
                $(this).addClass("selected");
            }
        }
        if (
            $(".checkboxs tr.selectable :checked").size() ==
            $(".checkboxs tr.selectable :checkbox").size()
        )
            $(".checkboxs thead :checkbox")
                .prop("checked", true)
                .parent()
                .addClass("checked");
        else
            $(".checkboxs thead :checkbox")
                .prop("checked", false)
                .parent()
                .removeClass("checked");

        if ($(".checkboxs tr.selectable :checked").size() >= 1)
            $(".checkboxs_actions").show();
        else $(".checkboxs_actions").hide();
    });

    if (
        $(".checkboxs tbody :checked").size() ==
            $(".checkboxs tbody :checkbox").size() &&
        $(".checkboxs tbody :checked").length
    )
        $(".checkboxs thead :checkbox")
            .prop("checked", true)
            .parent()
            .addClass("checked");

    if ($(".checkboxs tbody :checked").length) $(".checkboxs_actions").show();

    $(".radioboxs tbody tr.selectable").click(function (e) {
        var c = $(this).find(":radio");
        if (e.srcElement.nodeName == "INPUT") {
            if (c.is(":checked")) $(this).addClass("selected");
            else $(this).removeClass("selected");
        } else if (
            e.srcElement.nodeName != "TD" &&
            e.srcElement.nodeName != "TR"
        ) {
            return true;
        } else {
            if (c.is(":checked")) {
                c.attr("checked", false);
                $(this).removeClass("selected");
            } else {
                c.attr("checked", true);
                $(".radioboxs tbody tr.selectable").removeClass("selected");
                $(this).addClass("selected");
            }
        }
    });

    // sortable tables
    if ($(".js-table-sortable").length) {
        $(".js-table-sortable").sortable({
            placeholder: "ui-state-highlight",
            items: "tbody tr",
            handle: ".js-sortable-handle",
            forcePlaceholderSize: true,
            helper: function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            start: function (event, ui) {
                if (typeof mainYScroller != "undefined"){
                    mainYScroller.disable();
                }

                ui.placeholder.html(
                    '<td colspan="' +
                        $(this).find("tbody tr:first td").size() +
                        '">&nbsp;</td>'
                );
            },
            stop: function () {
                if (typeof mainYScroller != "undefined") mainYScroller.enable();
            },
        });
    }
});

$(document).ready(function () {
    $(".select-search-off").select2({
        width: "resolve",
        minimumResultsForSearch: -1,
    });
    $(".select-search-on").select2({ width: "resolve" });
    $(".select-schools, .select-ComplementaryAT, .select-schools").select2({
        width: "resolve",
        maximumSelectionSize: 6,
    });
    $(".select-disciplines").select2({
        width: "resolve",
        maximumSelectionSize: 13,
    });
    $(".select-school").select2({ dropdownCssClass: "school-dropdown" });
    $("button[type=submit]").on("click", function () {});
});
$(document).bind("ajaxSend", function () {
    $("body").css("cursor", "wait");
    $("a").css("cursor", "wait");
});
$(document).bind("ajaxComplete", function () {
    $("body").css("cursor", "auto");
    $("a").css("cursor", "pointer");
});

$(document).ajaxError(function (event, jqxhr, ajaxSettings, thrownError) {
    if (jqxhr.status === 401) {
        try {
            const response = JSON.parse(jqxhr.responseText);
            if (response.redirect) {
                return window.location.href = response.redirect;
            }
        } catch (e) {
            // JSON inválido, ignora
        }
    }

    const safeSubstring = (str, start, end) =>
        typeof str === 'string' ? str.substring(start, end) : '';

    const safeToString = (input) => {
        if (input instanceof Error) return input.message;
        if (typeof input === 'string') return input;
        try {
            return JSON.stringify(input);
        } catch (e) {
            return Object.prototype.toString.call(input);
        }
    };

    const requestId = jqxhr?.getResponseHeader?.('X-Request-ID');
    const urlPath = ajaxSettings?.url || 'URL desconhecida';

    try {
        urlPath = new URL(urlPath, window.location.origin).pathname;
    } catch (e) {
        // Se não for uma URL válida, mantém como está
    }

    const errorMessage = `[AJAX ERROR] ${urlPath} - ${safeToString(thrownError || jqxhr.statusText || 'Erro AJAX')}`;

    if(safeToString(thrownError || jqxhr.statusText || 'Erro AJAX') == 'abort') return;

    Raven.captureMessage(errorMessage, {
        tags: {
            url: urlPath,
            method: ajaxSettings?.type || 'GET',
            status: jqxhr?.status || 'unknown',
        },
        extra: {
            method: ajaxSettings?.type,
            url: urlPath,
            data: ajaxSettings?.data,
            status: jqxhr?.status,
            statusText: jqxhr?.statusText,
            readyState: jqxhr?.readyState,
            headers: jqxhr?.getAllResponseHeaders?.() || 'N/A',
            responseSnippet: safeSubstring(jqxhr?.responseText, 0, 200),
            fullResponse: safeSubstring(jqxhr?.responseText, 0, 1000),
            requestId,
            durationMs: ajaxSettings?._startTime ? (Date.now() - ajaxSettings._startTime) : null,
            timestamp: new Date().toISOString(),
        }
    });
});


$(function () {
    $("[id2='school']").change(function () {
        $(".school").submit();
    });
});

var isOpen = screen.width <= 425 ? false : true;
$(document).on("click", "#button-menu", function () {
    if (isOpen) {
        $("#content").css("margin", "0");
    } else {
        $("#content").css("margin", "0 0 0 191px");
    }
    isOpen = !isOpen;
});

//Ao clicar ENTER não fará nada.
$("*").keypress(function (e) {
    if (e.keyCode === $.ui.keyCode.ENTER) {
        e.preventDefault();
    }
});

const template = (strings, ...values) =>
    String.raw({ raw: strings }, ...values);

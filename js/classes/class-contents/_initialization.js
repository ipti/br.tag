$('#classesSearch').on('click', function () {
    if ($("#classroom").val() !== "" && $("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {
        $(".alert-required-fields").hide();
        var fundamentalMaior = Number($("#classroom option:selected").attr("fundamentalmaior"));
        jQuery.ajax({
            type: 'POST',
            url: "?r=classes/getClassContents",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                fundamentalMaior: fundamentalMaior,
                month: $("#month").val(),
                discipline: $("#disciplines").val()
            },
            beforeSend: function () {
                $(".loading-class-contents").css("display", "inline-block");
                $("#widget-class-contents").css("opacity", 0.3).css("pointer-events", "none");
                $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
                if (data.valid) {
                    createTable(data);
                    $("#print, #save").show();
                } else {
                    $('#class-contents > thead').html('<tr><th class="center">' + data.error + '</th></tr>');
                    $('#class-contents > tbody').html('');
                    $('#class-contents').show();
                    $("#save").hide();
                }
                $('#month_text').html($('#month').find('option:selected').text());
                $('#discipline_text').html($('#disciplines').is(":visible") ? $('#disciplines').find('option:selected').text() : "Todas as Disciplinas");
            },
            complete: function () {
                $(".loading-class-contents").hide();
                $("#widget-class-contents").css("opacity", 1).css("pointer-events", "auto").show();
                $("#classroom, #month, #disciplines, #classesSearch").removeAttr("disabled");
            }
        });
    } else {
        $(".alert-required-fields").show();
        $("#widget-class-contents").hide();
        $("#print, #save").hide();
    }
});

$("#classroom").on("change", function () {
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        if ($("#classroom > option:selected").attr("fundamentalmaior") === "1") {
            $.ajax({
                type: "POST",
                url: "?r=classes/getDisciplines",
                cache: false,
                data: {
                    classroom: $("#classroom").val(),
                },
                success: function (response) {
                    if (response == "") {
                        $("#disciplines").html("<option value='-1'></option>").trigger("change.select2").show();
                    } else {
                        $("#disciplines").html(decodeHtml(response)).trigger("change.select2").show();
                    }
                    $(".disciplines-container").show();
                },
            });
        } else {
            $(".disciplines-container").hide();
        }
    } else {
        $(".disciplines-container").hide();
    }
});

$(document).ready(function () {
    $('#class-contents').hide();
});

$(document).on("click", "#print", function () {
    window.print();
});

$("#save").on('click', function () {
    $(".alert-save").hide();
    var classContents = [];
    $(".day-row").each(function () {
        classContents.push({
            day: $(this).attr("day"),
            contents: $(this).find("select.course-classes-select").val()
        });
    });

    $.ajax({
        type: "POST",
        url: "?r=classes/saveClassContents",
        cache: false,
        data: {
            classroom: $("#class-contents").attr("classroom"),
            month: $("#class-contents").attr("month"),
            discipline: $("#class-contents").attr("discipline"),
            fundamentalMaior: $("#class-contents").attr("fundamentalmaior"),
            classContents: classContents
        },
        beforeSend: function () {
            $(".loading-class-contents").css("display", "inline-block");
            $("#widget-class-contents").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
        },
        success: function (response) {
            $(".alert-save").show();
        },
        complete: function (response) {
            $(".loading-class-contents").hide();
            $("#widget-class-contents").css("opacity", 1).css("pointer-events", "auto");
            $("#classroom, #month, #disciplines, #classesSearch").removeAttr("disabled");
        },
    });
});

$('.heading-buttons').css('width', $('#content').width());


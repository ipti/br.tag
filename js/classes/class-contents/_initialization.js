function loadClassContents() {
    var fundamentalMaior = Number($("#classroom option:selected").attr("fundamentalmaior"));
    var monthSplit = $("#month").val().split("-");
    $.ajax({
        type: 'POST',
        url: "?r=classes/getClassContents",
        cache: false,
        data: {
            classroom: $("#classroom").val(),
            month: monthSplit[1],
            year: monthSplit[0],
            fundamentalMaior: fundamentalMaior,
            discipline: $("#disciplines").val()
        },
        beforeSend: function () {
            $(".loading-class-contents").css("display", "flex");
            $("#widget-class-contents").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch, #classesSearchMobile").attr("disabled", "disabled");
        },
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.valid) {
                createTable(data);
                $("#print").addClass("show").removeClass("hide");
                $("#save").addClass("show--desktop").removeClass("hide");
                $("#save-button-mobile").addClass("show--tablet").removeClass("hide");
                $('#error-badge').html('')
            } else {
                $('#error-badge').html('<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span>' + data.error + '</div>')
                $('#class-contents > thead').html('');
                $('#class-contents > tbody').html('');
                $('#class-contents').show();
                $("#save, #save-button-mobile").addClass("hide");
            }
            $('#month_text').html($('#month').find('option:selected').text());
            $('#discipline_text').html($('#disciplines').is(":visible") ? $('#disciplines').find('option:selected').text() : "Todas as Disciplinas");
        },
        complete: function () {
            $(".loading-class-contents").hide();
            $("#widget-class-contents").css("opacity", 1).css("pointer-events", "auto").show();
            $("#classroom, #month, #disciplines, #classesSearch, #classesSearchMobile").removeAttr("disabled");
        }
    });
}

$("#classroom").on("change", function () {
    $("#widget-class-contents, .alert-incomplete-data").hide();
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=classes/getMonthsAndDisciplines",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                fundamentalMaior: $("#classroom > option:selected").attr("fundamentalMaior")
            },
            beforeSend: function () {
                $(".loading-frequency").css("display", "inline-block");
                $("#classroom, #month, #disciplines").attr("disabled", "disabled");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                    $("#month").children().remove();
                    $("#month").append(new Option("Selecione o Mês/Ano", ""));
                    $.each(data.months, function (index, value) {
                        $("#month").append(new Option(value.name, value.id));
                    });
                    $("#month option:first").attr("selected", "selected").trigger("change.select2");

                    if ($("#classroom > option:selected").attr("fundamentalMaior") === "1") {
                        $("#disciplines").children().remove();
                        $("#disciplines").append(new Option("Selecione a Disciplina", ""));
                        $.each(data.disciplines, function (index, value) {
                            $("#disciplines").append(new Option(value.name, value.id));
                        });
                        $("#disciplines option:first").attr("selected", "selected").trigger("change.select2");
                        $(".disciplines-container").show();
                    } else {
                        $(".disciplines-container").hide();
                    }
                    $(".month-container").show();
                } else {
                    $(".alert-incomplete-data").html(data.error).show();
                    $(".disciplines-container, .month-container").hide();
                }
            },
            complete: function (response) {
                $(".loading-frequency").hide();
                $("#classroom, #month, #disciplines").removeAttr("disabled");
            },
        });
    } else {
        $(".disciplines-container, .month-container").hide();
    }

});

$("#month, #disciplines").on("change", function () {
    if ($("#classroom").val() !== "" && $("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {
        loadClassContents();
        $("#disciplinesValue").text($("#disciplines option:selected").text());
        $("#monthValue").text($("#month option:selected").text());
        $("#classroomValue").text($("#classroom option:selected").text());
    } else {
        $("#widget-class-contents").hide();
        $("#print, #save, #save-button-mobile").addClass("hide");
    }
});

$(document).ready(function () {
    $('#class-contents').hide();
});

$(document).on("click", "#print", function () {
    window.print();
});

$("#save, #save-button-mobile").on('click', function () {
    $(".alert-save").hide();
    let classContents = [];
    $(".day-row").each(function () {
        let students = [];
        $(this).find(".student-diary-of-the-day").each(function () {
            students.push({
                id: $(this).attr("studentid"),
                diary: $(this).val()
            })
        });
        classContents.push({
            day: $(this).attr("day"),
            diary: $(this).find(".classroom-diary-of-the-day").val(),
            contents: $(this).find("select.course-classes-select").val(),
            students: students
        });
    });
    $.ajax({
        type: "POST",
        url: "?r=classes/saveClassContents",
        cache: false,
        data: {
            classroom: $("#class-contents").attr("classroom"),
            month: $("#class-contents").attr("month"),
            year: $("#class-contents").attr("year"),
            discipline: $("#class-contents").attr("discipline"),
            fundamentalMaior: $("#class-contents").attr("fundamentalmaior"),
            classContents: classContents
        },
        beforeSend: function () {
            $(".loading-class-contents").css("display", "flex");
            $("#widget-class-contents").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch, #classesSearchMobile").attr("disabled", "disabled");
        },
        success: function (response) {
            $(".alert-save").show();
        },
        complete: function (response) {
            $(".loading-class-contents").hide();
            $("#widget-class-contents").css("opacity", 1).css("pointer-events", "auto");
            $("#classroom, #month, #disciplines, #classesSearch, #classesSearchMobile").removeAttr("disabled");
        },
    });
});

$('.heading-buttons').css('width', $('#content').width());

$(document).on("click", ".classroom-diary-button", function () {
    let button = this;
    $(".classroom-diary-day").val($(button).closest("tr").attr("day"));
    $(".js-classroom-diary").val($(button).parent().find(".classroom-diary-of-the-day").val());
    $(".js-std-classroom-diaries").each(function () {
        let value = $(button).parent().find(".student-diary-of-the-day[studentid=" + $(this).find(".js-student-classroom-diary").attr("studentid") + "]").val();
        $(this).find(".js-student-classroom-diary").val(value);
        value !== ""
            ? $(this).find(".accordion-title").find(".fa").removeClass("fa-file-o").addClass("fa-file-text-o")
            : $(this).find(".accordion-title").find(".fa").removeClass("fa-file-text-o").addClass("fa-file-o");
    });
    $("#js-classroomdiary").modal("show");
});

$(document).on("click", ".js-add-classroom-diary", function () {
    let tr = $("#class-contents tbody").find("tr[day=" + $(".classroom-diary-day").val() + "]");

    tr.find(".classroom-diary-of-the-day").val($(".js-classroom-diary").val());
    $(".js-student-classroom-diary").each(function () {
        tr.find(".student-diary-of-the-day[studentid=" + $(this).attr("studentid") + "]").val($(this).val())
    });
});

$(document).on("keypress", ".js-classroom-diary, .js-student-classroom-diary", function (event) {
    if (event.which === 13) {
        event.preventDefault();
        this.value = this.value + "\n";
    }
});

$(document).on("input", ".js-student-classroom-diary", function () {
    $(this).val() === ""
        ? $(this).closest(".accordion-group").find(".accordion-title").find(".fa").removeClass("fa-file-text-o").addClass("fa-file-o")
        : $(this).closest(".accordion-group").find(".accordion-title").find(".fa").removeClass("fa-file-o").addClass("fa-file-text-o");
});

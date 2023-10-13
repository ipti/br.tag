function loadClassContents() {
    if ($("#classroom").val() !== "" && $("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {


        jQuery.ajax({
            type: 'POST',
            url: "?r=classes/getClassContents",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                month: $("#month").val(),
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
    } else {
        $("#widget-class-contents").hide();
        $("#print, #save, #save-button-mobile").addClass("hide");
    }
}


$('#classesSearch, #classesSearchMobile').on('click', loadClassContents);

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

$("#month").on("change", loadClassContents);

$("#disciplines").on("change", function () {
    loadClassContents();
    let disciplinesValue = $("#disciplines option:selected").text();
    $("#disciplinesValue").text(disciplinesValue);
    let monthValue = $("#month option:selected").text();
    $("#monthValue").text(monthValue);
    let classroomValue = $("#classroom option:selected").text();
    $("#classroomValue").text(classroomValue);
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
        console.log($(this).find("select.course-classes-select").val())
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
// here

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

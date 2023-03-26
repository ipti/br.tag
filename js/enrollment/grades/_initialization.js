$('#classroom').change(function () {
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=enrollment/getDisciplines",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $("#discipline").attr("disabled", "disabled");
            },
            success: function (response) {
                if (response === "") {
                    $("#discipline").html("<option value='-1'></option>").trigger("change").show();
                } else {
                    $("#discipline").html(decodeHtml(response)).trigger("change").show();
                }
                $(".js-grades-loading").hide();
                $("#discipline").removeAttr("disabled");
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, #save").hide();
    }
});

$('#discipline').change(function () {
    if ($(this).val() !== "") {
        $(".js-grades-alert").hide();
        $.ajax({
            type: "POST",
            url: "?r=enrollment/getGrades",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                discipline: $("#discipline").val(),
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $(".js-grades-container, #save").css("opacity", "0.4").css("pointer-events", "none");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                    var html = "<table class='grades-table table table-bordered table-striped'><thead><tr><th colspan='" + (Object.keys(data.modalityColumns).length + 1) + "' class='table-title'>Notas</th></tr><tr><th></th>";
                    $.each(data.unityColumns, function () {
                        html += "<th colspan='" + this.colspan + "'>" + this.name + "</th>";
                    });
                    html += "</tr><tr class='modality-row'><th></th>";
                    $.each(data.modalityColumns, function () {
                        html += "<th>" + this + "</th>";
                    });
                    html += "</tr></thead><tbody>";
                    $.each(data.students, function () {
                        html += "<tr><td class='grade-student-name'><input type='hidden' class='enrollment-id' value='" + this.enrollmentId + "'>" + $.trim(this.studentName) + "</td>";
                        $.each(this.grades, function () {
                            html += "<td class='grade-td'><input type='text' class='grade' modalityid='" + this.modalityId + "' value='" + this.value + "'></td>";
                        });
                        html += "</tr>";
                    });
                    html += "</tbody></table>";
                    $(".js-grades-container").html(html);
                } else {
                    $(".js-grades-alert").addClass("alert-error").removeClass("alert-success").text(data.message).show();
                }
                $(".js-grades-loading").hide();
                $(".js-grades-container, #save").css("opacity", "1").css("pointer-events", "auto").show();
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, #save").hide();
    }
});

$("#save").on("click", function (e) {
    e.preventDefault();
    var students = [];
    $('.grades-table tbody tr').each(function () {
        var grades = [];
        $(this).find(".grade").each(function () {
            grades.push({
                modalityId: $(this).attr("modalityid"),
                value: $(this).val()
            });
        });
        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades
        });
    });
    $.ajax({
        type: "POST",
        url: "?r=enrollment/saveGrades",
        cache: false,
        data: {
            students: students
        },
        beforeSend: function () {
            $(".js-save-grades-loading-gif").css("display", "inline-block");
            $(".js-grades-container, #save").css("opacity", "0.4").css("pointer-events", "none");
        },
        success: function (data) {
            data = JSON.parse(data);
            $(".js-grades-alert").removeClass("alert-error").addClass("alert-success").text("Notas registradas com sucesso!").show();
            $(".js-save-grades-loading-gif").hide();
            $(".js-grades-container, #save").css("opacity", "1").css("pointer-events", "auto").show();
        },
    });
});

$(document).on("keyup", "input.grade", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^(10|\d)(?:(\.|\,)\d{0,2}){0,1}$/;
        if (val.match(grade) === null) {
            val = "";
        } else {
            if (val > 10)
                val = 10;
        }
    }
    this.value = val;
});
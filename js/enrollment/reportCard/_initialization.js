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
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
});

$('#discipline').change(function (e, triggerEvent) {
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
                $(".js-grades-container, .grades-buttons").css("opacity", "0.4").css("pointer-events", "none");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                    var tableColspan = Object.keys(data.modalityColumns).length + (!data.isUnityConcept ? 3 : 2);
                    var html = "<table class='grades-table table table-bordered table-striped' concept='" + (data.isUnityConcept ? "1" : "0") + "'><thead><tr><th colspan='" + tableColspan + "' class='table-title'>Notas</th></tr><tr><th></th>";
                    $.each(data.unityColumns, function () {
                        html += "<th colspan='" + this.colspan + "'>" + this.name + "</th>";
                    });
                    html += !data.isUnityConcept ? '<th></th>' : '';
                    html += "</tr><tr class='modality-row'><th></th>";
                    $.each(data.modalityColumns, function () {
                        html += "<th>" + this + "</th>";
                    });
                    html += !data.isUnityConcept ? '<th>Média Anual</th>' : '';
                    html += '<th>Resultado</th>';
                    html += "</tr></thead><tbody>";
                    $.each(data.students, function () {
                        html += "<tr><td class='grade-student-name'><input type='hidden' class='enrollment-id' value='" + this.enrollmentId + "'>" + $.trim(this.studentName) + "</td>";
                        $.each(this.grades, function () {
                            if (this.value == "") {
                                valueGrade = "";
                            } else {
                                valueGrade = parseFloat(this.value).toFixed(1);
                            }
                            html += "<td class='grade-td'>";
                            if (!data.isUnityConcept) {
                                html += "<input type='text' class='grade' modalityid='" + this.modalityId + "' value='" + valueGrade + "'>";
                            } else {
                                html += "<select class='grade-concept' modalityid='" + this.modalityId + "'><option value=''></option>";
                                var concept = this.concept;
                                $.each(data.conceptOptions, function (index, value) {
                                    html += "<option value='" + index + "' " + (index === concept ? "selected" : "") + ">" + value + "</option>";
                                });
                            }
                            html += "</td>";
                        });
                        html += !data.isUnityConcept ? "<td class='final-media'>" + this.finalMedia + "</td>" : "";
                        html += "<td class='final-media'>" + this.situation + "</td>";
                        html += "</tr>";
                    });
                    html += "</tbody></table>";
                    $(".js-grades-container").html(html);
                    $(".grade-concept").select2();
                    if (triggerEvent === "saveGrades") {
                        $(".js-grades-alert").removeClass("alert-error").addClass("alert-success").text("Notas registradas com sucesso!").show();
                    }
                    $(".js-grades-container, .grades-buttons").show();
                } else {
                    $(".js-grades-alert").addClass("alert-error").removeClass("alert-success").text(data.message).show();
                }
                $(".js-grades-loading").hide();
                $(".js-grades-container, .grades-buttons").css("opacity", "1").css("pointer-events", "auto");
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
});

$("#save").on("click", function (e) {
    e.preventDefault();
    $(".js-grades-alert").hide();

    var students = [];
    $('.grades-table tbody tr').each(function () {
        var grades = [];
        if ($(".grades-table").attr("concept") === "1") {
            $(this).find(".grade-concept").each(function () {
                grades.push({
                    modalityId: $(this).attr("modalityid"),
                    concept: $(this).val()
                });
            });
        } else {
            $(this).find(".grade").each(function () {
                grades.push({
                    modalityId: $(this).attr("modalityid"),
                    value: $(this).val()
                });
            });
        }
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
            classroom: $("#classroom").val(),
            discipline: $("#discipline").val(),
            students: students,
            isConcept: $(".grades-table").attr("concept")
        },
        beforeSend: function () {
            $(".js-grades-loading").css("display", "inline-block");
            $(".js-grades-container, .grades-buttons").css("opacity", "0.4").css("pointer-events", "none");
        },
        success: function (data) {
            $("#discipline").trigger("change", ["saveGrades"]);
        },
    });
});

$(document).on("keyup", "input.grade", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^(10|\d)(?:(\.|\,)\d{0,1}){0,1}$/;
        if (val.match(grade) === null) {
            val = "";
        } else {
            if (val > 10)
                val = 10;
        }
    }
    this.value = val;
});
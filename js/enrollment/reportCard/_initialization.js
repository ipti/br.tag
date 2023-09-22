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
            url: "?r=enrollment/getReportCardGrades",
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
                    
                    const gradesAndFaults =  data.unities.map(element => {
                        return ` <th>Nota</th><th>Faltas</th>`;
                    });

                    const unities =  data.unities.map(element => {
                        return `<th colspan='2' style='width:20%;'>${element.name}</th>`;
                    });

                    var html = `
                    <table class='grades-table table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th colspan=10' class='table-title'>Lançamento de Notas</th>
                            </tr>
                            <tr>
                                <th rowspan='2' style='width:2%;'>Ordem</th>
                                <th rowspan='2' style='width:10%;'></th>
                                ${ unities.join("\n") }
                                <th rowspan='2' style='width:10%;vertical-align:middle;'>Média Final</th>
                            </tr>
                            <tr>
                            ${ gradesAndFaults.join("\n") }
                            </tr>
                        </thead>
                    <tbody>`;

                    $.each(data.students, function (index ) {
                        let order = this.daily_order || index + 1;
                        html += `<tr>
                            <td class='grade-student-order'>${order}</td>
                            <td class='grade-student-name'><input type='hidden' class='enrollment-id' value='${this.enrollmentId}'> ${ $.trim(this.studentName) } </td>
                        `;

                        $.each(this.grades, function () {
                            if (this.value == "" || this.value == null) {
                                valueGrade = "";
                            } else {
                                valueGrade = parseFloat(this.value).toFixed(1);
                            }
                            
                            if(this.faults == null) {
                                faults = ""
                            } else {
                                faults = this.faults
                            }
                            html += `
                                <td class='grade-td'>
                                    <input type='text' class='grade' value='${valueGrade}'>
                                </td>
                                <td class='grade-td'>
                                    <input type='text' class='faults' style='width:50px;text-align:center;' value='${ faults }'>
                                </td>
                            `;
                        });

                        html += `
                            <td style='font-weight: bold;font-size: 16px;' class='final-media'> ${this.finalMedia }</td>
                        </tr>`;
                    });
                    html += "</tbody></table>";
                    $(".js-grades-container").html(html);
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
        $(this).find(".grade").each(function () {
            grades.push({
                value: $(this).val(),
                faults: $(this).parent().next().children().val()
            });
        });
        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades
        });
    });

    $.ajax({
        type: "POST",
        url: "?r=enrollment/saveGradesReportCard",
        cache: false,
        data: {
            classroom: $("#classroom").val(),
            discipline: $("#discipline").val(),
            students: students
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

$(document).on("keyup", "input.faults", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    }
    this.value = val;
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
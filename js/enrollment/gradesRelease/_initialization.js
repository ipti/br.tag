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
                $(".js-grades-container, .grades-buttons").css("opacity", "0.4").css("overflow", "auto").css("pointer-events", "none");
            },
            success: function (data) {
                data = JSON.parse(data);

                if (data.valid) {
                    let unitiesLength = data.unities.length;
                    let totalGivenClasses = 0;
                    let html = `
                    <h3>Aulas Dadas</h3>
                    <div class="row">
                    `;
                    for (let i = 0; i < unitiesLength; i++) {
                        let order = i + 1;
                        let givenClasses = null;

                        $.each(data.students, function (index) {
                            if(this.grades[i]?.givenClasses != null){
                                givenClasses = data.students[index].grades[i].givenClasses;
                                totalGivenClasses +=  parseInt(givenClasses, 10);
                                return false;
                            }
                        });
                        if(givenClasses == null) {
                            givenClasses = ""
                        }
                        html += `
                        <div class="column is-one-tenth clearleft">
                            <div class="t-field-text">
                                <label class='t-field-text__label'>${order}° Trimestre</label>
                                <input type='text' class='givenClasses${i} t-field-text__input' value='${ givenClasses }'>
                            </div>
                        </div>`;
                    };
                    html +=
                    `</div>`;

                    html += `
                    <table class='grades-table table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th colspan=14' class='table-title'>Lançamento de Notas</th>
                            </tr>
                            <tr>
                                <th rowspan='2' style='width:2%;'>Ordem</th>
                                <th rowspan='2' style='width:10%;'>Nome</th>
                        `;
                    $.each(data.unities, function (index) {
                        html += `<th colspan='2' style='width:20%;'>` + this.name + `</th>`
                    });
                    html += `
                                <th rowspan='2' style='width:10%;vertical-align:middle;'>Recuperação Final</th>
                                <th rowspan='2' style='width:10%;vertical-align:middle;'>Média Final</th>
                                <th rowspan='2' style='width:2%;'>Frequência</th>
                                <th rowspan='2'>Situação</th>
                            </tr>
                            <tr>
                    `;
                    $.each(data.unities, function (index) {
                        html += `<th>Nota</th><th>Faltas</th>\n`;
                    });
                    html += `
                            </tr>
                        </thead>
                    <tbody>`;

                    $.each(data.students, function (index ) {
                        let order = this.daily_order || index + 1;
                        let totalFaults = 0;
                        let frequency = 0;
                        html += `<tr>
                            <td class='grade-student-order final-media'>
                            ${order}
                            </td>
                            <td class='grade-student-name final-media'><input type='hidden' class='enrollment-id' value='${this.enrollmentId}'>
                            ${ $.trim(this.studentName) }
                            </td>
                        `;
                        $.each(this.grades, function (index, value) {
                            let valueGrade;
                            if (this.value == "" || this.value == null) {
                                valueGrade = "";
                            } else {
                                valueGrade = parseFloat(this.value).toFixed(1);
                            }

                            let faults;

                            if(this.faults == null) {
                                faults = ""
                            } else {
                                faults = this.faults;
                                totalFaults +=  parseInt(this.faults, 10);
                                console.log(totalFaults);
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
                        if(totalGivenClasses != 0) {
                            frequency = ((totalGivenClasses - totalFaults)/totalGivenClasses)*100;
                            frequency = parseInt(frequency);
                        }

                        html += `
                            <td class='grade-td rec-final-td'>
                                <input type='text' class='rec-final' value='${this.recFinal}'>
                            </td>
                            <td style='font-weight: bold;font-size: 16px;' class='final-media'> ${this.finalMedia }</td>
                            <td class="final-media">${frequency}%</td>
                            <td class="situation">${this.situation}</td>
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
                $(".js-grades-container, .grades-buttons").css("overflow", "auto").css("opacity", "1").css("pointer-events", "auto");
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
});

$("#save").on("click", function (e) {
    e.preventDefault();
    $(".js-grades-alert").hide();

    let students = [];
    $('.grades-table tbody tr').each(function () {
        let grades = [];
        $(this).find(".grade").each(function (index) {
            grades.push({
                value: $(this).val(),
                faults: $(this).parent().next().children().val(),
                givenClasses: $('.givenClasses' + index).val()
            });
        });
        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades,
            recFinal:  $(this).find(".rec-final").val()
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
            $(".js-grades-container, .grades-buttons").css("overflow", "auto").css("opacity", "0.4").css("pointer-events", "none");
        },
        success: function (data) {
            $("#discipline").trigger("change", ["saveGrades"]);
        },
    });
});

$(document).on("keyup", "input.faults", function (e) {
    let val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    }
    this.value = val;
})

$(document).on("keyup", "input.grade, input.rec-final", function (e) {
    let val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        let grade = /^(10|\d)([.,]\d?)?$/;
        if (val?.match(grade) === null) {
            val = "";
        } else if (val > 10) {
            val = 10;
        }
    }
    this.value = val;
});


$("#close-grades-diary").on("click", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "?r=enrollment/calculateFinalMedia",
        cache: false,
        data: {
            classroomId: $("#classroom").val(),
            disciplineId: $("#discipline").val(),
        },
        beforeSend: function () {
            $(".js-grades-loading").css("display", "inline-block");
            $(".js-grades-container, .grades-buttons")
                .css("opacity", "0.4")
                .css("overflow", "auto")
                .css("pointer-events", "none");
        },
        success: function (data) {
            $("#discipline").trigger("change", ["saveGrades"]);
        },
    });
});

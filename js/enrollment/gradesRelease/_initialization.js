let frequency = 0;
let totalFaults = 0;

function getFrequency(){
    return frequency;
}

function setFrequency(value){
    frequency = value;
}

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
            url: "?r=grades/getGradesRelease",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                discipline: $("#discipline").val(),
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $(".js-grades-container, .grades-buttons").css("opacity", "0.4").css("overflow", "auto").css("pointer-events", "none");
                $("#grades-save-button").removeClass("hide");
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
                    <table class='grades-table table table-bordered' concept="${data.rule}">
                        <thead>
                            <tr>
                                <th colspan=14' class='table-title'>Lançamento de Notas</th>
                            </tr>
                            <tr>
                                <th rowspan='2' style='width:2%;vertical-align:middle;'>Ordem</th>
                                <th rowspan='2' style='min-width:250px;vertical-align:middle;'>Nome</th>
                        `;
                    $.each(data.unities, function (index) {
                        html += `<th colspan='2' style='width:20%;'>` + this.name + `</th>`
                    });

                    if(data.rule == "N") {
                        if(data.hasRecovery == 1) {
                            html += `
                                <th rowspan='2' style='width:10%;vertical-align:middle;'>Recuperação Final</th>
                            `;
                        }
                        html += `
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>1º Recuperação Semestral</th>
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>2º Recuperação Semestral</th>
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Média Semestral</th>
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Recuperação Final</th>
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Média Final</th>
                        `;
                    } else {
                        html += `
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Conceito Final</th>
                        `;
                    }
                    html += `

                                <th rowspan='2' style='width:2%;vertical-align:middle;'>Frequência</th>
                                <th rowspan='2' style='width:10%;vertical-align:middle;'>Situação</th>
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
                        html += `<tr>
                            <td class='grade-student-order final-media'>
                            ${order}
                            </td>
                            <td class='grade-student-name'><input type='hidden' class='enrollment-id' value='${this.enrollmentId}'>
                            ${ $.trim(this.studentName) }
                            </td>
                        `;
                        $.each(this.grades, function (index, value) {
                            let valueGrade;

                            if(data.rule == "C") {
                                valueGrade = this.concept;
                            } else {
                                if (this.value == "" || this.value == null) {
                                    valueGrade = "";
                                } else {
                                    valueGrade = parseFloat(this.value).toFixed(1);
                                }
                            }

                            let faults;

                            if(this.faults == null) {
                                faults = ""
                            } else {
                                faults = this.faults;
                                totalFaults +=  parseInt(this.faults, 10);
                            }
                            html += buildInputOrSelect(data.rule, valueGrade, data.concepts);

                            html += `
                                <td class='grade-td'>
                                    <input type='text' class='faults' style='width:50px;text-align:center;margin-bottom: 0px' value='${ faults }'>
                                </td>
                            `;
                        });


                        if(totalGivenClasses != 0) {
                            frequency = ((totalGivenClasses - totalFaults)/totalGivenClasses)*100;
                            frequency = parseInt(frequency);
                            setFrequency(frequency);
                        }

                        if(data.rule == "N") {
                            html += `
                                <td class='grade-td one-semianual-td'>
                                    <input type='text' class='one-semianual' value='${this.recSemianual1}' style="width:50px; text-align:center; margin-bottom: 0px">
                                </td>
                                <td class='grade-td two-semianual-td'>
                                    <input type='text' class='two-semianual' value='${this.recSemianual2}' style="width:50px; text-align:center; margin-bottom: 0px">
                                </td>
                                <td style='font-weight: bold;font-size: 16px;' class='semianual-media'> ${this.semianualMedia}</td>
                                <td class='grade-td rec-final-td'>
                                    <input type='text' class='rec-final' value='${this.recFinal}'>
                                </td>
                                `
                            }
                            html += `
                                <td style='font-weight: bold;font-size: 16px;' class='final-media'> ${this.finalMedia }</td>
                            `;
                        } else {
                            html += buildInputOrSelect(data.rule, this.finalConcept, data.concepts, true);
                        }

                        let valorF = getFrequency();

                        html += `
                            <td class="final-media">${valorF}%</td>
                            <td class="grade-td situation">${ this.situation }</td>
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
        if ($(".grades-table").attr("concept") === "C") {
            $(this).find(".grade-concept").each(function (index) {
                grades.push({
                    value: $(this).val(),
                    faults: $(this).parent().next().children().val(),
                    givenClasses: $('.givenClasses' + index).val()
                });
            });
        } else {
            $(this).find(".grade").each(function (index) {
                grades.push({
                    value: $(this).val(),
                    faults: $(this).parent().next().children().val(),
                    givenClasses: $('.givenClasses' + index).val()
                });
            });
        }

        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades,
            recFinal:  $(this).find(".rec-final").val(),
            recSemianual1:  $(this).find(".one-semianual").val(),
            recSemianual2:  $(this).find(".two-semianual").val(),
            finalConcept:  $(this).find(".final-concept").val()
        });
    });

    $.ajax({
        type: "POST",
        url: "?r=grades/saveGradesRelease",
        cache: false,
        data: {
            classroom: $("#classroom").val(),
            discipline: $("#discipline").val(),
            students: students,
            rule: $(".grades-table").attr("concept")
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
        let grade = /^(\d+([.,]\d?)?)?$/;
        if (val?.match(grade) === null) {
            val = "";
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

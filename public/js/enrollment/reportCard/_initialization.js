$('#classroom').change(function () {
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=grades/getDisciplines",
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
            url: "?r=grades/getReportCardGrades",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                discipline: $("#discipline").val(),
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $(".js-grades-container").css("opacity", "0.4").css("overflow", "auto").css("pointer-events", "none");
                $("#grades-save-button").removeClass("hide");
            },
            success: function (data) {
                data = JSON.parse(data);

                if (data.valid) {
                    let html = `
                    <h3>Aulas Dadas</h3>
                    <div class="row">
                    `;
                    for (let i = 0; i < 3; i++) {
                        let order = i + 1;
                        let givenClasses = null;

                        $.each(data.students, function (index) {
                            if(this.grades[i]?.givenClasses != null){
                                givenClasses = data.students[index].grades[i].givenClasses;
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
                    <table class='grades-table table table-bordered table-striped'  concept="${data.rule}">
                        <thead>
                            <tr>
                                <th colspan=14' class='table-title'>Lançamento de Notas</th>
                            </tr>
                            <tr>
                                <th rowspan='2' style='width:2%;'>Ordem</th>
                                <th rowspan='2' style='width:2%;'>Ficha individual</th>
                                <th rowspan='2' style='width:10%;'>Nome</th>
                                <th colspan='2' style='width:20%;'>1° Trimestre</th>
                                <th colspan='2' style='width:20%;'>2° Trimestre</th>
                                <th colspan='2' style='width:20%;'>3° Trimestre</th>
                    `;
                    if(data.rule == "N") {
                        html += `<th rowspan='2' style='width:10%;vertical-align:middle;'>Média Final</th>`;
                    } else {
                        html += `
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Conceito Final</th>
                            <th rowspan='2' style='width:10%;vertical-align:middle;'>Situação</th>
                        `;
                    }

                    html += `
                            </tr>
                            <tr>
                            <th>Nota</th><th>Faltas</th>\n
                            <th>Nota</th><th>Faltas</th>\n
                            <th>Nota</th><th>Faltas</th>\n
                            </tr>
                        </thead>
                    <tbody>`;


                    $.each(data.students, function (index ) {
                        let order = this.daily_order || index + 1;
                        html += `<tr>
                            <td class='grade-student-order final-media'>
                            ${order}
                            </td>
                            <td class="final-media">
                            <a class='t-link-button--info' rel='noopener' target='_blank' href='?r=forms/IndividualRecord&enrollment_id=${this.enrollmentId}'>
                                <span class="t-icon-printer"></span>
                            </a>
                            </td>
                            <td class='grade-student-name final-media'><input type='hidden' class='enrollment-id' value='${this.enrollmentId}'>
                            ${ $.trim(this.studentName) }
                            </td>
                        `;
                        for (let index = 0; index < 3; index++) {
                            const element = this.grades[index];

                            let valueGrade;
                            if(data.rule == "C") {
                                valueGrade = element.concept;
                            } else {
                                if (element?.value == "" || element?.value == null) {
                                    valueGrade = "";
                                } else {
                                    valueGrade = parseFloat(element.value).toFixed(1);
                                }
                            }

                            let faults;

                            if (element?.faults == null) {
                                faults = "";
                            } else {
                                faults = element?.faults;
                            }

                            html += buildInputOrSelect(data.rule, valueGrade, data.concepts);

                            html += `
                                <td class='grade-td'>
                                    <input type='text' class='faults' style='width:50px;text-align:center;' value='${faults}'>
                                </td>
                            `;
                        }

                        if(data.rule == "N") {
                            html += `<td style='font-weight: bold;font-size: 16px;' class='final-media'> ${this.finalMedia }</td>`;
                        } else {
                            html += buildInputOrSelect(data.rule, this.finalConcept, data.concepts, true);
                            html += `<td class="grade-td situation">${ this.situation }</td>`;
                        }

                        html += `</tr>`;
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
            finalConcept: $(this).find(".final-concept").val()
        });
    });


    $.ajax({
        type: "POST",
        url: "?r=grades/saveGradesReportCard",
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

$(document).on("keyup", "input.grade", function (e) {
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

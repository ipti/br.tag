
var lastValidValue = "";

function initializeGradesMask() {
    $(document).on("focus", "input.grade", function (e) {
        lastValidValue = this.value
    });

    $("input.grade").on("input", function (e) {
        e.preventDefault();
        var val = this.value;
        if (!$.isNumeric(val)) {
            val = val === "" ? "" : lastValidValue;
        } else {
            grade = /^(10|\d)(?:(\.|\,)\d{0,1}){0,1}$/;
            if (val.match(grade) === null) {
                val = lastValidValue;
            }
        }
        lastValidValue = val;
        this.value = val;
    });
}

function loadDisciplinesFromClassroom(classroomId, disciplineId) {
    $("#classroom").select2("val", classroomId);
    if (classroomId !== "") {
        $.ajax({
            type: "POST",
            url: "?r=enrollment/getDisciplines",
            cache: false,
            data: {
                classroom: classroomId,
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $("#discipline").attr("disabled", "disabled");
            },
            success: function (response) {
                if (response === "") {
                    $("#discipline")
                        .html(
                            "<option value='-1'>Não há matriz curricular</option>"
                        )
                        .show();
                    $("#classroom").select2("val", "-1");
                } else {
                    $("#discipline").html(decodeHtml(response)).show();
                    $("#discipline").select2("val", "-1");
                }
                $(".js-grades-loading").hide();
                $("#discipline").removeAttr("disabled");


                if (disciplineId) {
                    loadStudentsFromDiscipline(disciplineId);
                }
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

function loadStudentsFromDiscipline(disciplineId) {
    $("#discipline").select2("val", disciplineId);
    if (disciplineId !== "") {
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
                $(".js-grades-container, .grades-buttons")
                    .css("opacity", "0.4")
                    .css("pointer-events", "none");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                    const html = GradeTableBuilder(data).build();
                    $(".js-grades-container").html(html);

                    // $(".grade-concept").select2();
                } else {
                    $(".js-grades-alert")
                        .addClass("alert-error")
                        .removeClass("alert-success")
                        .text(data.message)
                        .show();
                }
                $(".js-grades-loading").hide();
                $(".js-grades-container, .grades-buttons")
                    .css("opacity", "1")
                    .css("pointer-events", "auto");
                $(".js-grades-container, .grades-buttons").show();
            },
            complete: function () {
                initializeGradesMask();
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

function GradeTableBuilder(data) {

    function buildStundentsRows(students, isUnityConcept, conceptOptions) {
        return students
            .map(
                (student) => template`
                    <tr>
                        <td class="grade-student-name">
                            <input
                                type="hidden"
                                class="enrollment-id"
                                value="${student.enrollmentId}"
                            />
                            ${student.studentName}
                        </td>
                        ${student.grades
                            .map(
                                (grade) => template`
                                    <td class="grade-td">
                                        ${buildInputOrSelect(
                                            isUnityConcept,
                                            grade,
                                            conceptOptions
                                        )}
                                    </td>`
                            )
                            .join("\n")
                        }
                        ${
                            isUnityConcept ? "" : `<td class="final-media"> ${secureParseFloat(student.finalMedia)} </td>`
                        }
                        <td class="final-media">
                            ${student.situation}
                        </td>
                    </tr>
                    <tr></tr>`
            )
            .join("\n");
    }

    function buildInputOrSelect(isUnityConcept, grade, conceptOptions) {
        // debugger
        if (isUnityConcept) {
            return template`
                <select class="grade-concept" modalityid="${grade.modalityId}">
                    <option value=""></option>
                    ${Object.values(conceptOptions)
                        .map(
                            (conceptOption, index) => template`
                        <option value="${index + 1}" ${
                                (index + 1) == grade.concept ? "selected" : ""
                            }>
                            ${conceptOption}
                        </option>`
                        )
                        .join("")}
                </select>`;
        }

        return template`<input type="text" class="grade" modalityid="${
            grade.modalityId
        }" value="${secureParseFloat(grade.value)}"/>`;
    }

    function secureParseFloat(val) {
        return val != "" ? parseFloat(val).toFixed(1) : "";
    }

    function build(){
        const numModalities = Object.keys(data.modalityColumns).length;
        const spaceByColumnGrade = !data.isUnityConcept ? 3 : 2;
        const tableColspan = numModalities + spaceByColumnGrade;
        const concept = data.isUnityConcept ? "1" : "0";
        return template`
            <table class="grades-table table table-bordered table-striped" concept="${concept}">
                <thead>
                    <tr>
                        <th colspan="${tableColspan}" class="table-title">
                            Notas
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        ${data.unityColumns
                            .map(
                                (element) =>
                                    `<th colspan='${element.colspan}'> ${element.name}</th>`
                            )
                            .join("\n")}
                        ${!data.isUnityConcept ? "<th></th>" : ""}
                    </tr>
                    <tr class="modality-row">
                        <th></th>
                        ${data.modalityColumns
                            .map((element) => `<th>${element}</th>`)
                            .join("\n")}
                        ${!data.isUnityConcept ? "<th>Média Anual</th>" : ""}
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    ${buildStundentsRows(data.students, data.isUnityConcept, data.conceptOptions)}
                </tbody>
            </table>`;
    }

    return {
        build
    }
}

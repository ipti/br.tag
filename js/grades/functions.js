var lastValidValue = "";

function initializeGradesMask() {
    $(document).on("focus", "input.grade", function (e) {
        lastValidValue = this.value;
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
            url: "?r=grades/getDisciplines",
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
            url: "?r=grades/getGrades",
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
                const html = GradeTableBuilder(data).build();
                $(".js-grades-container").html(html);

                // $(".grade-concept").select2();
            },
            error: function (xhr, status, error) {
                debugger
                $(".js-grades-container").html("<div></div>");
                $(".js-grades-alert")
                    .addClass("alert-error")
                    .removeClass("alert-success")
                    .text(decodeURIComponent(escape(error)))
                    .show();
            },
            complete: function () {
                $(".js-grades-loading").hide();
                $(".js-grades-container, .grades-buttons")
                    .css("opacity", "1")
                    .css("pointer-events", "auto");
                $(".js-grades-container, .grades-buttons").show();
                initializeGradesMask();
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

function GradeTableBuilder(data) {
    // TODO: alterar Builder para construir por unidade e assim ter o calculo por unidade nessa tabela
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
                        ${buildUnities(
                            student.unities,
                            isUnityConcept,
                            conceptOptions
                        )}
                        ${
                            isUnityConcept
                                ? ""
                                : `<td class="final-media"> ${secureParseFloat(
                                      student.finalMedia
                                  )} </td>`
                        }
                        <td class="final-media">
                            ${student.situation ?? ""}
                        </td>
                    </tr>
                    <tr></tr>`
            )
            .join("\n");
    }

    function buildUnities(unities, isUnityConcept, conceptOptions) {
        const unitesGrade = unities
            .map((unity) => {
                const unityRow = unity.grades.map((grade) => {
                    return template`
                    <td class="grade-td">
                        ${buildInputOrSelect(
                            isUnityConcept,
                            grade,
                            conceptOptions
                        )}
                    </td>`;
                });

                if (unity.grades.length > 1) {
                    const unityMedia = template`
                <td>${unity.unityMedia ?? ""}</td>
            `;

                    unityRow.push(unityMedia);
                }

                return unityRow.join("\n");
            })
            .join("\n");

        return unitesGrade;
    }

    function buildInputOrSelect(isUnityConcept, grade, conceptOptions) {
        if (isUnityConcept) {
            return template`
                <select class="grade-concept" gradeid="${
                    grade.id
                }" modalityid="${grade.modalityId}">
                    <option value=""></option>
                    ${Object.values(conceptOptions)
                        .map(
                            (conceptOption, index) => template`
                        <option value="${index + 1}" ${
                                index + 1 == grade.concept ? "selected" : ""
                            }>
                            ${conceptOption}
                        </option>`
                        )
                        .join("")}
                </select>`;
        }

        return template`<input type="text" gradeid="${
            grade.id
        }" class="grade" modalityid="${
            grade.modalityId
        }" value="${secureParseFloat(grade.value)}"/>`;
    }

    function secureParseFloat(val) {
        const result = parseFloat(val).toFixed(1);
        if (isNaN(result) || result == null) {
            return "";
        }
        return result;
    }

    function build() {
        const spaceByColumnGrade = !data.isUnityConcept ? 3 : 2;
        const concept = data.isUnityConcept ? "1" : "0";
        const modalityColumns = data.unityColumns.reduce((acc, e) => {
            if (e.modalities.length > 1) {
                return [...acc, ...e.modalities, `Média da Unidade (${e.calculationName})`];
            }
            return [...acc, ...e.modalities];
        }, []);
        const numModalities = modalityColumns.length;
        const tableColspan = numModalities + spaceByColumnGrade;

        return template`
            <table class="grades-table" concept="${concept}">
            <colgroup>
            </colgroup>
            <colgroup>
            ${data.unityColumns
                .map(
                    (element, index) =>
                        `<col class="${index%2==0 ? "odd": "even"}" span='${
                            element.colspan > 1
                                ? parseInt(element.colspan) + 1
                                : element.colspan
                        }'/>`
                )
                .join("\n")}
            </colgroup>
            <colgroup>
            <col span="2"/>
            </colgroup>
                <thead>
                    <tr>
                        <th colspan="${tableColspan}" class="table-title">
                            Notas
                        </th>
                    </tr>
                    <tr>
                        <th style="min-width: 250px"></th>
                        ${data.unityColumns
                            .map(
                                (element, index) => `<th colspan='${
                                    element.colspan > 1
                                        ? parseInt(element.colspan) + 1
                                        : element.colspan
                                }'>
                                   ${element.name}
                                </th>`
                            )
                            .join("\n")}
                        ${!data.isUnityConcept ? `<th colspan='2'></th>` : ""}
                    </tr>
                    <tr class="modality-row">
                        <th>Aluno(a)</th>
                        ${modalityColumns
                            .map(
                                (element) =>
                                    `<th style="max-width: 50px;  font-size: 80%">${element}</th>`
                            )
                            .join("\n")}
                        ${!data.isUnityConcept ? `<th style="font-size: 80%">Média Anual</th>` : ""}
                        <th style="font-size: 80%">Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    ${buildStundentsRows(
                        data.students,
                        data.isUnityConcept,
                        data.conceptOptions
                    )}
                </tbody>
            </table>`;
    }

    return {
        build,
    };
}

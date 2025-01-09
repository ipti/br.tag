let lastValidValue = "";

function initializeGradesMask() {
    $(document).on("focus", "input.grade", function (e) {
        lastValidValue = this.value;
    });

    $("input.grade").on("input", function (e) {
        e.preventDefault();
        const gradePattern = /^(100|\d{1,2}(\.\d)?)$|^\d(\.(\d)?)?$/;
        let val = this.value;
        if (!$.isNumeric(val)) {
            val = val === "" ? "" : lastValidValue;
        } else if (val.match(gradePattern) === null) {
            val = lastValidValue;
        }

        lastValidValue = val;
        this.value = val;
    });
}

function loadDisciplinesFromClassroom(classroomId) {
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
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

function loadUnitiesFromClassroom(classroomId) {
    const isMulti = $("#classroom option:selected").attr("data-isMulti");
    const stage = $("#stage").val();

    if(isMulti==="1" && stage === ""){
        return
    }
    if(classroomId !== "")
    {
        $.ajax({
            type: "POST",
            url: "?r=grades/getUnities",
            cache: false,
            data: {
                classroom: classroomId,
                stage: stage,
            },
            beforeSend: function () {
            $("#unities").attr("disabled", "disabled")
            },
            success: function (response) {
                const data = JSON.parse(DOMPurify.sanitize(response));
                console.log(data)
                const unitiesSelect = $("#unities");
                unitiesSelect.empty();

                const defaultOption = $('<option>', {
                    value: "",
                    text: "Selecione"
                });
                unitiesSelect.append(defaultOption);

                $.each(data, function (key, value) {
                    const option = $('<option>', {
                        value: key,
                        text: value
                    });
                    unitiesSelect.append(option);
                });

                unitiesSelect.prop("disabled", false);
            }
        })
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

function loadStudentsFromDiscipline(disciplineId, unityId) {
    $("#discipline").select2("val", disciplineId);

    const classroom =  $("#classroom").val();
    const discipline =  disciplineId || $("#discipline").val();
    const unity = unityId || $("#unities").val();
    const isMulti = $("#classroom option:selected").attr("data-isMulti");
    const stage = $("#stage").val()

    let isClassroomStage = "0"
    if(isMulti==="1" && stage === ""){
        return
    }
    if(isMulti==="1" && stage !== ""){
        isClassroomStage = $("#stage option:selected").attr("data-classroom-stage");
    }

    if (discipline && unity && discipline !== "" && unity !== "") {
        $(".js-grades-alert").hide();
        $.ajax({
            type: "POST",
            url: "?r=grades/getGrades",
            cache: false,
            data: {
                classroom: classroom,
                discipline: discipline,
                unity: unity,
                stage: stage,
                isClassroomStage: isClassroomStage,
            },
            beforeSend: function () {
                $(".js-grades-loading").css("display", "inline-block");
                $(".js-grades-container, .grades-buttons")
                    .css("opacity", "0.4")
                    .css("overflow", "auto")
                    .css("pointer-events", "none");
            },
            success: function (data) {
                const parsedData = JSON.parse(data);
                const resultGrades = parsedData[0];
                const isCoordinator = parsedData[1];
                const html = GradeTableBuilder(resultGrades).build();
                let hrefPrintGrades = `/?r=forms/AtaSchoolPerformance&id=${classroom}`
                $('.js-print-grades').find('a').attr('href',hrefPrintGrades);
                $('.js-print-grades').show();
                $('.js-unity-title').text($('select#unities').find('option:selected').text());

                $(".js-grades-container").html(html);
                if(isCoordinator) {
                    $('select.grade-concept, input.grade, input.grade-partial-recovery').prop('disabled', true);
                }
                if (data.isUnityConcept) {
                    $("#close-grades-diary").hide();
                } else {
                    $("#close-grades-diary").css("display", "flex");
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseText;
                const gradesNotFoundIMG = $("<img class='column'>");
                gradesNotFoundIMG.attr('src', '/themes/default/img/grades/gradesNotFound.svg');
                gradesNotFoundIMG.attr('alt', 'Não foram encontrados dados correspondentes aos critérios definidos');
                const gradesNotFoundContainer = $("<div class='row flex-direction--column justify-content--center t-padding-large--top'></div>");
                gradesNotFoundContainer.append(gradesNotFoundIMG)
                gradesNotFoundContainer.append("<span class='column text-align--center' style='color:#a6b6c8' >Não foram encontrados dados correspondentes <br> aos critérios definidos.</span>");
                gradesNotFoundContainer.append("<a class='column t-button-secondary t-margin-small--top  t-margin-none--right js-refresh' >Recarregar Página</a>");
                gradesNotFoundContainer.find(".js-refresh").on("click", (e) => {
                    location.reload();
                })
                $(".js-grades-container").html(gradesNotFoundContainer);
                $(".js-grades-alert")
                    .addClass("alert-error")
                    .removeClass("alert-success")
                    .text(response || "Não foi possível listar o diário de notas dos alunos dessa turma, verifique se exsitem alunos ativos nessa turma")
                    .show();
            },
            complete: function () {
                $(".js-grades-loading").hide();
                $(".js-grades-container, .grades-buttons")
                    .css("opacity", "1")
                    .css("overflow", "auto")
                    .css("pointer-events", "auto");
                $(".js-grades-container").show();
                $(".grades-buttons").css("display", "flex");

                initializeGradesMask();
            },
        });
    } else {
        $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    }
}

$('.js-refresh').on("click", function (e) {
    location.reload();
})



function GradeTableBuilder(data) {
    function buildStundentsRows(students, isUnityConcept, conceptOptions, partialRecoveries, showSemAvarageColumn) {
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
                            ${buildEnrollmentStatusLabel(student.enrollmentStatus)}
                            ${student.studentName}
                        </td>
                        ${buildUnities(
                            student.unities,
                            isUnityConcept,
                            conceptOptions
                        )}
                        ${
                            isUnityConcept
                            ? ''
                            : buildSemesterAvarage(student, showSemAvarageColumn)


                        }
                        ${partialRecoveries !== null ? buildPartialRecovery(
                            student.partialRecoveries[0],
                        ) : ''}
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
    function buildSemesterAvarage(student, showSemAvarageColumn){



        return showSemAvarageColumn
                ? `<td class="grade-td">
                     ${student.semAvarage}
                    </td>`
                : ''
    }

    function buildEnrollmentStatusLabel(status){
        return `<label class="t-badge-info t-margin-none--left ${status == 'MATRICULADO' || status == '' ? 'hide' : ''}">
                    ${status}
                </label>`;
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
    function buildPartialRecovery(studentPartialRecoveries){
        const grade = studentPartialRecoveries.grade.grade === null ? "" : studentPartialRecoveries.grade.grade
        return template`
            <td class="grade-td">
                <input class="grade-partial-recovery" gradeid="${studentPartialRecoveries.grade.id}" type="text" style="width:50px;text-align: center;margin-bottom:0px;" value="${grade}" />
            </td>
            <td class="grade-td">
                ${studentPartialRecoveries.recPartialResult != null ?studentPartialRecoveries.recPartialResult : ''}
            </td>`;

    }
    function buildInputOrSelect(isUnityConcept, grade, conceptOptions) {
        if (isUnityConcept) {
            const optionsValues = Object.values(conceptOptions);
            const optionsKeys = Object.keys(conceptOptions);
            return template`
                <select class="grade-concept" gradeid="${
                    grade.id
                }" modalityid="${grade.modalityId}">
                    <option value=""></option>
                    ${optionsValues
                        .map(
                            (conceptOption, index) => template`
                        <option value="${optionsKeys[index]}" ${
                                optionsKeys[index] == grade.concept
                                    ? "selected"
                                    : ""
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
        const partialRecoveryColumns = data.partialRecoveryColumns
        const semester = data.semester
        const modalityColumns = data.unityColumns.reduce((acc, e) => {
            if (e.modalities.length > 1) {
                return [
                    ...acc,
                    ...e.modalities,
                    `Média da Unidade (${e.calculationName})`,
                ];
            }
            return [...acc, ...e.modalities];
        }, []);
        const numModalities = modalityColumns.length;
        const tableColspan = numModalities + spaceByColumnGrade;
        let semesterAvarage = '';
        if (data.showSemAvarageColumn) {
            let semesterText = ""
            if(data.type === "RF") {
                semesterText = "Média dos Semestres"
            } else {
                semesterText = `Média ${semester}° Semestre`
            }
            semesterAvarage = `
                <th style="min-width: 50px; font-weight: bold;">
                    ${semesterText}
                </th>
            `;
        }
        return template`
            <table class="grades-table tag-table-secondary remove-vertical-borders remove-border-radius" concept="${concept}">
            <colgroup>
            </colgroup>
            <colgroup>
            ${data.unityColumns
                .map(
                    (element, index) =>
                        `<col class="${
                            index % 2 == 0 ? "odd" : "even"
                        }" span='${
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
                    <tr class="modality-row">
                        <th style="width:266px">Aluno(a)</th>
                        ${modalityColumns
                            .map(
                                (element) =>
                                    `<th style="min-width: 50px;%">${element}</th>`
                            )
                            .join("\n")}
                            ${semesterAvarage}
                            ${partialRecoveryColumns !== null ?
                                template`<th style="min-width: 50px; font-weight: bold;">
                                ${partialRecoveryColumns.name}
                                </th>
                                <th style="min-width: 50px; font-weight: bold;">
                                    Média pós recuperação
                                </th>`
                                 :
                                ''}

                        ${
                            !data.isUnityConcept
                                ? `<th style="font-weight: bold;">Média Anual</th>`
                                : ""
                        }



                        <th style="font-weight: bold;">Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    ${buildStundentsRows(
                        data.students,
                        data.isUnityConcept,
                        data.concepts,
                        data.partialRecoveryColumns,
                        data.showSemAvarageColumn
                    )}
                </tbody>
            </table>`;
    }

    return {
        build,
    };
}

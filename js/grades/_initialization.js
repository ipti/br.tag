const urlParams = new URLSearchParams(window.location.search);
$(function () {
    const classroomId = urlParams.get("classroom_id");
    if (classroomId) {
        const disciplineId = urlParams.get("discipline_id");
        const unityId = urlParams.get("unity_id");
        loadDisciplinesFromClassroom(classroomId, disciplineId, unityId);
    }
});

$("#classroom").change(function (e) {
    const disciplineId = urlParams.get("discipline_id");
    const unityId = urlParams.get("unity_id");
    $(".js-unity-title").html('')
    $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    loadDisciplinesFromClassroom(e.target.value, disciplineId, unityId);
    loadUnitiesFromClassroom(e.target.value)
});

$("#discipline, #unities").change(function (e,triggerEvent ) {
    const unityId = $("#unities").val();
    const disciplineId =  $("#discipline").val();
    loadStudentsFromDiscipline(disciplineId, unityId);
    if (triggerEvent === "saveGrades") {
        $(".js-grades-alert")
            .removeClass("alert-error")
            .addClass("alert-success")
            .text("Notas registradas com sucesso!")
            .show();
    }
});

$("#save").on("click", function (e) {
    e.preventDefault();
    $(".js-grades-alert").hide();

    let students = [];
    $(".grades-table tbody tr").each(function () {
        let grades = [];
        let partialRecoveriesGrades = [];
        if ($(".grades-table").attr("concept") === "1") {
            $(this)
                .find(".grade-concept")
                .each(function () {
                    grades.push({
                        id: $(this).attr("gradeid"),
                        modalityId: $(this).attr("modalityid"),
                        concept: $(this).val(),
                    });
                });
        } else {
            $(this)
                .find(".grade")
                .each(function () {
                    grades.push({
                        id: $(this).attr("gradeid"),
                        modalityId: $(this).attr("modalityid"),
                        value: $(this).val(),
                    });
                });

                $(this)
                .find(".grade-partial-reovery")
                .each(function () {
                    partialRecoveriesGrades.push({
                        id: $(this).attr("gradeid"),
                        value:$(this).val(),
                    })
                })

        }
        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades,
            partialRecoveriesGrades: partialRecoveriesGrades,
        });
    });
    $.ajax({
        type: "POST",
        url: "?r=grades/saveGrades",
        cache: false,
        data: {
            classroom: $("#classroom").val(),
            discipline: $("#discipline").val(),
            students: students,
            isConcept: $(".grades-table").attr("concept"),
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
        complete: () => $(".js-grades-loading").css("display", "none")
    });
});

$("#close-grades-diary").on("click", function (e) {
    e.preventDefault();

    const classromId = $("#classroom").val();
    const disciplineId = $("#discipline").val();

    if(!classromId){
        alertValidationError("Para calcular a média final, primeiro selecione uma turma");
        return;
    }

    if(!disciplineId){
        alertValidationError("Para calcular a média final, selecione um componente curricular");
        return;
    }

    $.ajax({
        type: "POST",
        url: "?r=grades/calculateFinalMedia",
        cache: false,
        data: {
            classroom: classromId,
            discipline: disciplineId,
        },
        beforeSend: function () {
            $(".js-grades-loading").css("display", "inline-block");
            $(".js-grades-container, .grades-buttons")
                .css("opacity", "0.4")
                .css("overflow", "auto")
                .css("pointer-events", "none");
        },
        success: (data) => $("#discipline").trigger("change", ["saveGrades"]),
        complete: () => $(".js-grades-loading").css("display", "none")
    });
});


function alertValidationError(message){
    $(".js-grades-alert")
            .removeClass("alert-success")
            .addClass("alert-error")
            .text(message)
            .show();
}

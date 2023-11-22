const urlParams = new URLSearchParams(window.location.search);
$(function () {
    const classroomId = urlParams.get("classroom_id");
    if (classroomId) {
        const disciplineId = urlParams.get("discipline_id");
        loadDisciplinesFromClassroom(classroomId, disciplineId);
    }
});

$("#classroom").change(function (e) {
    const disciplineId = urlParams.get("discipline_id");
    loadDisciplinesFromClassroom(e.target.value, disciplineId);
});

$("#discipline").change(function (e,triggerEvent ) {
    loadStudentsFromDiscipline(e.target.value);
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

    var students = [];
    $(".grades-table tbody tr").each(function () {
        var grades = [];
        if ($(".grades-table").attr("concept") === "1") {
            $(this)
                .find(".grade-concept")
                .each(function () {
                    grades.push({
                        modalityId: $(this).attr("modalityid"),
                        concept: $(this).val(),
                    });
                });
        } else {
            $(this)
                .find(".grade")
                .each(function () {
                    grades.push({
                        modalityId: $(this).attr("modalityid"),
                        value: $(this).val(),
                    });
                });
        }
        students.push({
            enrollmentId: $(this).find(".enrollment-id").val(),
            grades: grades,
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
            isConcept: $(".grades-table").attr("concept"),
        },
        beforeSend: function () {
            $(".js-grades-loading").css("display", "inline-block");
            $(".js-grades-container, .grades-buttons")
                .css("opacity", "0.4")
                .css("pointer-events", "none");
        },
        success: function (data) {
            $("#discipline").trigger("change", ["saveGrades"]);
        },
    });
});

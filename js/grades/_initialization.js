const urlParams = new URLSearchParams(window.location.search);
$(function () {
    const classroomId = urlParams.get("classroom_id");
    const disciplineId = urlParams.get("discipline_id");
    const unityId = urlParams.get("unity_id");
    if (classroomId && disciplineId && unityId) {
        loadDisciplinesFromClassroom(classroomId, disciplineId, unityId);
    }
});

$("#classroom").change(function (e) {
    $(".js-grades-alert-multi").hide()
    const disciplineId = urlParams.get("discipline_id");
    const unityId = urlParams.get("unity_id");

    $(".js-unity-title").html('')
    $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    $("#unities").select2("val", "-1");

    const isMulti = $("#classroom option:selected").attr("data-isMulti");
    const classroomId = e.target.value;
    if (isMulti === '1') {
        $('.js-stage-select').removeClass("js-hide-stage");

        $.ajax({
            type:"POST",
            url:"/?r=grades/getClassroomStages" ,
            data:{
                classroomId: classroomId
            },

        }).success( function(response) {
            if(response === ""){
                $("#stage")
                .html(
                    "<option value='-1'>Não há etapas nas matriculas dos alunos</option>"
                )
                .show();
            } else {
                $("#stage").html('')
                $("#stage").append("<option value=''>Selecione</option>");
                $("#stage").append(decodeHtml(DOMPurify.sanitize(response))).show();
                $("#stage").select2("val", "");
            }
        })
    } else {
        $('.js-stage-select').addClass("js-hide-stage");
        $("#stage").html('')
        loadUnitiesFromClassroom(e.target.value)
    }
    loadDisciplinesFromClassroom(e.target.value, disciplineId, unityId);

});
$("#stage").on("change", function(e) {
    $(".js-unity-title").html('');
    $(".js-grades-container, .js-grades-alert, .grades-buttons").hide();
    $("#unities").html()
    $("#unities").select2("val", "");
    loadUnitiesFromClassroom($("#classroom").val())

    const isMulti = $("#classroom option:selected").attr("data-isMulti");
    const isClassroomStage = $("#stage option:selected").attr("data-classroom-stage");
    const stage = $("#stage").val();
    let alert = ""
     if(isMulti==="1" && stage !== ""){
        alert = isClassroomStage == "1" ?
        "<h4><b>Turma Multiseriada</b></h4>Foi selecionada a etapa vinculada à TURMA<br>contudo, também existe a possibilidade de utilizar as etapas vinculadas diretamente aos ALUNOS."
        :
        "<h4><b>Turma Multiseriada</b></h4>Foi selecionada uma etapa vinculada aos ALUNOS<br>contudo, também existe a possibilidade de utilizar a etapas vinculadas diretamente a TURMA."
        $(".js-grades-alert-multi").html(alert).show()
    }
})
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
                .find(".grade-partial-recovery")
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
            stage: $('#stage').val(),
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

    const isMulti = $("#classroom option:selected").attr("data-isMulti");
    const stage = $("#stage").val();
    let isClassroomStage = "0"
    if (isMulti==="1" && stage !== "") {
        isClassroomStage = $("#stage option:selected").attr("data-classroom-stage");
    }

    $.ajax({
        type: "POST",
        url: "?r=grades/calculateFinalMedia",
        cache: false,
        data: {
            classroom: classromId,
            stage: $('#stage').val(),
            discipline: disciplineId,
            isClassroomStage: isClassroomStage,
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

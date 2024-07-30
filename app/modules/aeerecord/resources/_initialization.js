let url = new URL(window.location.href);
let recordId = url.searchParams.get('id');
let studentHasRecord = false;

$(document).ready(function() {
    $.ajax({
        type: 'POST',
        url: "?r=aeerecord/default/getInstructorClassrooms",
        cache: false
    }).success(function(response) {
        let classrooms = JSON.parse(response);
        let classroomSelect = $('#classroomSelect');
        Object.entries(classrooms).forEach(function([id, value]) {
            classroomSelect.append($('<option>', {
                value: value.id,
                text: value.name
            }));
        });
    })

    if(recordId) {
        $.ajax({
            type: 'POST',
            url: "?r=aeerecord/default/getAeeRecord",
            cache: false,
            data: {
                recordId: recordId
            }
        }).success(function(response) {
            let aeeRecord = JSON.parse(response);

            $("#js-classroom-name").append(aeeRecord[0].classroomName);
            $("#js-student-name").append(aeeRecord[0].studentName);
        })
    }
});

$(document).on("change", "#classroomSelect", function () {
    var classroomId = $('#classroomSelect').val();

    $.ajax({
        type: 'POST',
        url: "?r=aeerecord/default/getClassroomStudents",
        cache: false,
        data: {
            classroomId: classroomId
        }
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let students = JSON.parse(data);
        let studentsSelect = $('#studentSelect');
        $('#studentContainer').removeClass("hide");

        studentsSelect.empty();
        studentsSelect.append($('<option>',{value: "",text: "Selecione o aluno"}));

        Object.entries(students).forEach(function([id, value]) {
            studentsSelect.append($('<option>', {
                value: value.id,
                text: value.name
            }));
        });
    })
});

$(document).on("change", "#studentSelect", function () {
    var studentId = $('#studentSelect').val();

    $.ajax({
        type: 'POST',
        url: "?r=aeerecord/default/checkStudentAeeRecord",
        cache: false,
        data: {
            studentId: studentId
        }
    }).success(function(response) {
        let aeeRecord = JSON.parse(response);
        if(aeeRecord != "") {
            $('#info-alert').removeClass('hide').addClass('alert-error').html(
                `O aluno informado já possui ficha AEE cadastrada. <br>
                Para acessar a ficha do aluno, clique <a href="?r=aeerecord/default/update&id=${aeeRecord}"> <strong>>> aqui <<</strong></a>`
            );
            studentHasRecord = true;
        } else {
            studentHasRecord = false;
        }
    })
});


$(document).on("click", "#saveAeeRecord", function () {
    let classroomId = $('#classroomSelect').val();
    let studentId = $('#studentSelect').val();
    let learningNeeds = $('#learningNeeds').val();
    let characterization = $('#characterization').val();

    if((classroomId == "" || studentId == "") && recordId == null) {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else if(recordId == null && studentHasRecord == true){
        $('#info-alert').removeClass('hide').addClass('alert-error').html("O estudante selecionado já possui ficha AEE cadastrada");
    } else if(recordId == null && studentHasRecord == false) {
        $.ajax({
            type: 'POST',
            url: "?r=aeerecord/default/create",
            cache: false,
            data: {
                classroomId: classroomId,
                studentId: studentId,
                learningNeeds: learningNeeds,
                characterization: characterization
            }
        }).success(function(response) {
            window.location.href = "?r=aeerecord/default";
        })
    } else {
        $.ajax({
            type: 'POST',
            url: `?r=aeerecord/default/update&id=${recordId}`,
            cache: false,
            data: {
                recordId: recordId,
                learningNeeds: learningNeeds,
                characterization: characterization
            }
        }).success(function(response) {
            window.location.href = "?r=aeerecord/default";
        })
    }
});

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

        studentsSelect.empty();

        Object.entries(students).forEach(function([id, value]) {
            studentsSelect.append($('<option>', {
                value: value.id,
                text: value.name
            }));
        });
    })
});

function create(element, id) {
    $.ajax({
        type: 'POST',
        url: `/?r=sedsp/default/CreateRA&id=${id}`,
        success: function (data) {
            $(element).html(data);
        }
    });
}
function generate(element, url) {
    $.ajax({
        type: 'POST',
        url: url,
        success: function (data) {
            $(element).html(data);
        },
        error: function (error) {
            const approved = confirm('Aluno nao encontrado na SED, deseja envia-lo?');
            if (approved) {
                create(element, error.responseJSON.id);
            }
        }
    });
}

function validateFormStudent() {
    var raInput = $("#ra");
    var warningDiv = $("#ra-warning");
    if (raInput.val().length < 12) {
        warningDiv.show();
        return false;
    } else {
        warningDiv.hide();
        return true;
    }
}

function validateFormClass() {
    var classInput = $("#class");
    var warningDiv = $("#class-warning");
    if (classInput.val().length < 9) {
        warningDiv.show();
        return false;
    } else {
        warningDiv.hide();
        return true;
    }
}

function validateRA() {
    var raInput = $("#ra");
    var warningDiv = $("#ra-warning");
    if (raInput.val().length < 12) {
        warningDiv.show();
    } else {
        warningDiv.hide();
    }
    if (raInput.val().length > raInput.attr("maxLength")) {
        raInput.val(raInput.val().slice(0, raInput.attr("maxLength")));
    }
}

function validateClass() {
    var classInput = $("#class");
    var warningDiv = $("#class-warning");
    if (classInput.val().length < 9) {
        warningDiv.show();
    } else {
        warningDiv.hide();
    }
    if (classInput.val().length > classInput.attr("maxLength")) {
        classInput.val(classInput.val().slice(0, classInput.attr("maxLength")));
    }
}

$(document).ready(function () {
    $('#schoolName').on('input', function () {
        $(this).val($(this).val().toUpperCase());
    });
    $('#schoolMun').on('input', function () {
        $(this).val($(this).val().toUpperCase());
    });
    $('.generate').click(function (event) {
        event.preventDefault();

        const element = this;
        const url = $(this).attr('href');

        generate(element, url);

        return false;
    });
    $("#addStudentRA").submit(function (event) {
        if (!validateFormStudent()) {
            event.preventDefault();
        }
    });

    $("#addClassroom").submit(function (event) {
        if (!validateFormClass()) {
            event.preventDefault();
        }
    });
});  
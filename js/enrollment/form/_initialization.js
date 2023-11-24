

$('.heading-buttons').css('width', $('#content').width());

$(document).on("change", "#StudentEnrollment_public_transport", function () {
    if ($(this).is(":checked")) {
        $("#transport_responsable, #transport_type").show();
    } else {
        $("#StudentEnrollment_transport_responsable_government").val("").trigger("change.select2");
        $("#transport_type input[type=checkbox]:checked").prop("checked", false);
        $("#transport_responsable, #transport_type").hide();
    }
});
$("#StudentEnrollment_public_transport").trigger("change");

$(document).on("click", "#delete-enrollment", function () {
    enrollment_id = $(this).attr('enrollment');

    console.log(enrollment_id)

    $.ajax({
        url: `${window.location.host}?r=enrollment/checkenrollmentdelete&enrollmentId=${enrollment_id}`,
        success: function (data) {
            response = JSON.parse(data)
            block = response.block;
            message = response.message;

            if(block) {
                alert(message);
            }else {
                if(confirm(message)) {
                    $.ajax({
                        url: `${window.location.host}?r=enrollment/delete&id=${enrollment_id}`,
                        success: function (response) {
                            alert("Matrícula excluída com sucesso!")
                            window.location.reload();
                        }
                    });
                }
            }
        }
    });
});

$('#statusDropdown').change(function () {
    if ($(this).val() == '4') {
        $('#reasonDropdown').show();
        checkDropdownValue();
    } else {
        $('#reasonDropdown').hide();
        $('#secondDropdown').hide();
    }
});
$("#statusDropdown").trigger("change");

$(document).on('change', '#secondDropdown select', function () {
    checkSecondDropdownValue();
});

function checkDropdownValue() {
    var selectedValue = $('#reasonDropdown select').val();
    var secondDropdown = $('#secondDropdown');

    secondDropdown.hide();
    if (selectedValue === '1') {
        secondDropdown.show();
    }
}

function checkSecondDropdownValue() {
    var selectedValue = $('#secondDropdown select').val();
}
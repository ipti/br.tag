$(document).ready(function () {

    ////////////////////////////////////////////////
    // Dialogs                                     //
    ////////////////////////////////////////////////
    //Cria o Dialogo de TeachingData
    myTeachingDataDialog = $("#teachingdata-dialog-form").dialog({
        autoOpen: false,
        height: 530,
        width: 402,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function () {
                    let id = '#Role';
                    if ((role.val().length !== 0 && instructors.val().length !== 0)
                            || (role.val().length === 0 && instructors.val().length === 0)) {
                        addTeachingData();
                        removeError(id);
                        $(this).dialog("close");
                    } else {
                        addError(id, "Selecione um cargo");
                    }
                }},
            {
                text: btnCancel,
                click: function () {
                    let id = '#Role';
                    removeError(id);
                    $(this).dialog("close");
                }}

        ],
    });
});

//////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#newDiscipline").click(function () {
    $("#teachingdata-dialog-form select").val('').trigger('change');
    $("#teachingdata-dialog-form").dialog('open');
});

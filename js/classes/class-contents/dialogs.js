$(document).ready(function () {

    ////////////////////////////////////////////////
    // Dialogs                                     //
    ////////////////////////////////////////////////
    //Cria o Dialogo de TeachingData
    myAddContentForm = $("#add-content-form").dialog({
        autoOpen: false,
        height: 290,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function () {
                    var id = '#add-content-name';
                    var name = $('#add-content-name').val().toUpperCase();
                    if (name !== "") {
                        addContent();
                        $(this).dialog("close");
                        removeError(id);
                        $('#add-content-form input').each(function () {
                            $(this).val("");
                        });
                    } else {
                        addError(id, "Preencha o nome.");
                    }
                }},
            {
                text: btnCancel,
                click: function () {
                    var id = '#add-content-name';
                    removeError(id);
                    $('#add-content-form input').each(function () {
                        $(this).val("");
                    });
                    $(this).dialog("close");
                }}

        ],
    });
});

//////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#add-content").click(function () {
    $("#add-content-form").dialog('open');
});    
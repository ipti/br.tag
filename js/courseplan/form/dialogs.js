$(document).ready(function () {

    ////////////////////////////////////////////////
    // Dialogs                                    //
    ////////////////////////////////////////////////
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
                    var description = $('#add-content-description').val().toUpperCase();
                    var type = 1;
                    if (name !== "") {
                        addResource(name, description, type);
                        $(this).dialog("close");
                        removeError(id);
                        $('#add-content-form input').each(function () {
                            $(this).val("");
                        });
                    } else {
                        addError(id, "Preencha o nome.")
                    }
                }},
            {
                text: btnCancel,
                click: function () {
                    var id = '#add-content-name';
                    removeError(id);
                    $(this).dialog("close");
                    $('#add-content-form input').each(function () {
                        $(this).val("");
                    });
                }}

        ],
    });
    myAddContentForm = $("#add-type-form").dialog({
        autoOpen: false,
        height: 290,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function () {
                    var id = '#add-type-name';
                    var name = $('#add-type-name').val().toUpperCase();
                    var description = $('#add-type-description').val().toUpperCase();
                    var type = 3;
                    if (name !== "") {
                        addResource(name, description, type);
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
                    var id = '#add-type-name';
                    removeError(id);
                    $(this).dialog("close");
                    $('#add-content-form input').each(function () {
                        $(this).val("");
                    });
                }}

        ],
    });
    myAddContentForm = $("#add-resource-form").dialog({
        autoOpen: false,
        height: 290,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function () {
                    var id = '#add-resource-name';
                    var name = $('#add-resource-name').val().toUpperCase();
                    var description = $('#add-resource-description').val().toUpperCase();
                    var type = 2;
                    if (name !== "") {
                        addResource(name, description, type);
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
                    var id = '#add-resource-name';
                    removeError(id);
                    $('#add-content-form input').each(function () {
                        $(this).val("");
                    });
                    $(this).dialog("close");
                }}

        ],
    });
});

////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#add-content").on('click', function () {
    $("#add-content-form").dialog('open');
});
$("#add-resource").on('click', function () {
    $("#add-resource-form").dialog('open');
});
$("#add-type").on('click', function () {
    $("#add-type-form").dialog('open');
});    
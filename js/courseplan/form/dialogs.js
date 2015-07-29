$(document).ready(function() {

    ////////////////////////////////////////////////
    // Dialogs                                     //
    ////////////////////////////////////////////////
    //Cria o Dialogo de TeachingData
    myAddContentForm = $("#add-content-form").dialog({
        autoOpen: false,
        height: 200,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){   
                    addContent();
                    $(this).dialog("close");
            }},
            {
                text: btnCancel,
                click: function() {
                    $(this).dialog("close");
            }}

        ],
    });
});

//////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#add-content").click(function(){
    $("#add-content-form").dialog('open');
});    
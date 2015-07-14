$(document).ready(function() {

    ////////////////////////////////////////////////
    // Dialogs                                     //
    ////////////////////////////////////////////////
    //Cria o Dialogo de TeachingData
    myAddObjectiveForm = $("#add-objective-form").dialog({
        autoOpen: false,
        height: 200,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){   
                    addObjective();
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
$("#add-objective").click(function(){
    $("#add-objective-form").dialog('open');
});    
$(document).ready(function() {

    ////////////////////////////////////////////////
    // Dialogs                                    //
    ////////////////////////////////////////////////
    myAddContentForm = $("#add-content-form").dialog({
        autoOpen: false,
        height: 280,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){   
                    var name = $('#add-content-name').val().toUpperCase();
                    var description = $('#add-content-description').val().toUpperCase();   
                    var type = 1;
                    addResource(name,description,type);
                    $(this).dialog("close");
            }},
            {
                text: btnCancel,
                click: function() {
                    $(this).dialog("close");
            }}

        ],
    });
    myAddContentForm = $("#add-type-form").dialog({
        autoOpen: false,
        height: 280,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){
                    var name = $('#add-type-name').val().toUpperCase();
                    var description = $('#add-type-description').val().toUpperCase();   
                    var type = 3;
                    addResource(name,description,type);
                    $(this).dialog("close");
            }},
            {
                text: btnCancel,
                click: function() {
                    $(this).dialog("close");
            }}

        ],
    });
    myAddContentForm = $("#add-resource-form").dialog({
        autoOpen: false,
        height: 280,
        width: 240,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){   
                    var name = $('#add-resource-name').val().toUpperCase();
                    var description = $('#add-resource-description').val().toUpperCase();   
                    var type = 3;
                    addResource(name,description,type);
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

////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#add-content").on('click',function(){
    $("#add-content-form").dialog('open');
});    
$("#add-resource").on('click',function(){
    $("#add-resource-form").dialog('open');
});    
$("#add-type").on('click',function(){
    $("#add-type-form").dialog('open');
});    
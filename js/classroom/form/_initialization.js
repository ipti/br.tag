$(document).ready(function() {
    ////////////////////////////////////////////////
    // Ajax Initialization                        //
    ////////////////////////////////////////////////
    // $.ajax({
    //     'type':'POST',
    //     'url':getAssistanceURL,
    //     'cache':false,
    //     'data':$(form+'school_inep_fk').parents("form").serialize(),
    //     'success':function(result){
    //         result   = jQuery.parseJSON(result);
    //         var html = result.html;
    //         var val  = result.val;
    //         $(form+"assistance_type").html(html);
    //         $(form+"assistance_type").val(val).trigger('change');
    //     }});
    $( function() {
        $( "#js-t-sortable" ).sortable();
      } );
    if ($("#Classroom_complementary_activity").is(":checked")) {
        $("#complementary_activity").show();
    } else {
        $("#complementary_activity").hide();
    }
    if ($("#Classroom_pedagogical_mediation_type").val() === "1" || $("#Classroom_pedagogical_mediation_type").val() === "2") {
        $("#diff_location_container").show();
    } else {
        $("#diff_location_container").hide();
    }
});

//Ao clicar ENTER no formulário adicionar aula
$('#create-dialog-form, #teachingdata-dialog-form, #update-dialog-form').keypress(function(e) {
    if (e.keyCode === $.ui.keyCode.ENTER) {
        e.preventDefault();
    }
});

$('.heading-buttons').css('width', $('#content').width());
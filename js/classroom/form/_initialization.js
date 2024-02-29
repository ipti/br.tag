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

$(".update-classroom-from-sedsp").click(function() {
    $("#importClassroomFromSEDSP").modal("show");
});

$(".import-classroom-button").click(function() {
    $("#importClassroomFromSEDSP").find("form").submit();
});

$('#copy-gov-id').click(function() {
    let govId = $('#Classroom_gov_id').val();
    navigator.clipboard.writeText(govId);
    $('#copy-message').text('Copiado!').fadeIn().delay(1000).fadeOut();
});

$('#js-alphabetic-order').click(function() {
    let orderArray = $('#js-t-sortable').sortable("toArray");
    $.ajax({
        url: `?r=classroom/updateDailyOrder`,
        type: "POST",
        data: {
            list: orderArray
        },
        beforeSend: function () {
            $("#js-t-sortable").sortable("destroy");
            $("#daily").css("opacity", 0.5);
        },
    }).success(function (response) {
        const result = JSON.parse(response);
        const list = []
        result.forEach(element => {
            const li = document.createElement('li');
            li.id = element.id;
            li.className = 'ui-state-default';

            const span1 = document.createElement('span');
            span1.className = 't-icon-slip';

            const span2 = document.createElement('span');
            span2.textContent = element.daily_order + ' ' + element.name;

            li.appendChild(span1);
            li.appendChild(span2);

            list.push(li);
        });

        $("#js-t-sortable").html(list);
        $("#daily").css("opacity", 1);
        $("#js-t-sortable").sortable();
    })
});

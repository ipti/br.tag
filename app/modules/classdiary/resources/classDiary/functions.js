const urlParams = new URLSearchParams(window.location.search);
const date = urlParams.get("date")
$('.js-add-course-classes-accordion').on("change", function (){
    let optionSelected = $(this).val();
     let PlanName = $(this).find('option[value="' + optionSelected + '"]').text();


    $.ajax({
        type:'POST',
        data: {
            plan_name: PlanName,
            id: optionSelected
        },
        url:`?r=classdiary/default/RenderAccordion`
    }).success(function (response){
        $('.js-course-classes-accordion').append(DOMPurify.sanitize(response))
        $(function () {
            if($(".js-course-classes-accordion").data('uiAccordion')){
                $(".js-course-classes-accordion").accordion('destroy');
            }
            $( ".js-course-classes-accordion").accordion({
                active: false,
                collapsible: true,
                icons: false,
            });
        });

        // Remover a opção selecionada
        // $(this).find('option[value="' + optionSelected + '"]').remove();
    })
})
function renderFrequencyElement(w) {
    const classroom_fk = urlParams.get("classroom_fk")
    const stage_fk = urlParams.get("stage_fk")
    const discipline_fk = urlParams.get("discipline_fk")
    const url =`RenderFrequencyElementMobile`;
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/${url}&classroom_fk=${classroom_fk}&stage_fk=${stage_fk}&discipline_fk=${discipline_fk}&date=${date}`,
        type: "GET",

    }).success(function (response) {
        $(".js-frequency-element").html(DOMPurify.sanitize(response))
    });
}
function updateClassesContents()
{
    const classroom_fk = urlParams.get("classroom_fk")
    const discipline_fk = urlParams.get("discipline_fk")
    const stage_fk = urlParams.get("stage_fk")
    $.ajax({
        type:'GET',
        url:  `${window.location.host}?r=classdiary/default/GetClassesContents&classroom_fk=${classroom_fk}&stage_fk=${stage_fk}&date=${date}&discipline_fk=${discipline_fk}`
    }).success((response) => {
        if(response.valid==true){
            let options = "";
            $.each(response["courseClasses"], function () {
                options += '<option value="' + this.id + '" disciplineid="' + this.edid + '" disciplinename="' + this.edname + '">' + this.cpname + "|" + this.order + "|" + this.content + "|" + this.edname + '</option>';
            });
            $("#coursePlan").html(options);
            $("#coursePlan").select2("val", response["classContents"]);
            $('#coursePlan').select2({
                formatSelection: function (state) {
                    let textArray = state.text.split("|");
                    return '<div class="text-align--left" style="margin-left: 0"><b>Aula:</b> "' + textArray[1] + '" <b>Plano de Aula:</b> ' + textArray[0] + ' <br><b>Conteúdo:</b> ' + textArray[2] + "</div>";
                },
                formatResult: function (data, container) {
                    let textArray = data.text.split("|");
                    console.log(data.text)
                    if (textArray.length === 1) {
                        return "<div class='course-classes-optgroup'><b>" + textArray[0] + "</b></div>";
                    } else {
                        return "<div class='course-classes-option'><div><b>Plano de Aula:</b> <span>" + textArray[0] + "</span></div><div><b>Aula " + textArray[1] + "</b> - " + textArray[2] + "</div></div>";
                    }
                },
            });
            $(".js-hide-is-not-valid").show()
        }
    });
}

function validadeNewClassContent() {
    let content = $(".js-class-content-textarea").val();
    let methodology = $(".js-class-content-methodology").val();
    let coursePlanId = $("select.js-course-plan-id").val();
    let abilities = $(".ability-panel-option-id").length;

    let mensege = "";

    if (!content) {
        mensege  +="O campo de conteúdo é obrigatório <br>";
    }

    if (!methodology) {
        mensege += "O campo de metodologia é obrigatório <br>";
    }

    if (!coursePlanId) {
        mensege += `O campo de plano de aula é obrigatório <br>`;
    }

    if (abilities === 0) {
        mensege += "Selecione pelo menos uma habilidade <br>";
    }

    if (mensege) {
        $('.js-validate').html(mensege).removeClass("hide");
        return false;
    }
    $('.js-validate').html('').addClass("hide");

    return true;
}

$(".js-save-course-plan").on("click", function () {
    const classroom_fk = urlParams.get("classroom_fk")
    const stage_fk = urlParams.get("stage_fk")
    const discipline_fk = urlParams.get("discipline_fk")
    const classContent = $('#coursePlan').val();

    let content = null;
    let methodology = null;
    let coursePlanId = null;
    let abilities = [];

    const hasNewClassContent = $('.js-new-class-content').text().trim() === "Cancelar";
    if(hasNewClassContent) {
        if (!validadeNewClassContent()) {
            return;
        }
             content = $(".js-class-content-textarea").val();
             methodology = $(".js-class-content-methodology").val();
             coursePlanId = $("select.js-course-plan-id").val();
             abilities = [];
            $(".ability-panel-option-id").each(function () {
                abilities.push($(this).val());
            });

    }

    $.ajax({
        type: 'POST',
        url: `${window.location.origin}?r=classdiary/default/SaveClassContents`,
        data: {
            stage_fk: stage_fk,
            date: date,
            discipline_fk: discipline_fk,
            classroom_fk: classroom_fk,
            classContent: classContent,
            hasNewClassContent: hasNewClassContent,
            content: content,
            methodology: methodology,
            coursePlanId: coursePlanId,
            abilities: abilities
        }
    }).done(function(response) {
        updateClassesContents();
        $(".js-class-content-textarea, .js-class-content-methodology").val("");
        $(".js-course-plan-id").val(null).trigger("change");
        $(".courseplan-abilities-selected").html("");
        $(".js-add-new-class-content-form").addClass("hide");
        $('.js-new-class-content').text("Nova Aula");
    })
});

$(document).on("change", ".js-frequency-checkbox", function () {
    $.ajax({
        type: "POST",
        url: `${window.location.host}?r=classdiary/default/saveFresquency`,
        cache: false,
        data: {
            classroom_id: $(this).attr("data-classroom_id"),
            date: date,
            schedule: $(this).attr("data-schedule"),
            studentId: $(this).attr("data-studentId"),
            fault: $(this).is(":checked") ? 1 : 0,
            stage_fk: $(this).attr("data-stage_fk")
        },
        beforeSend: function () {
            $(".js-table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $(".js-date, .js-change-date").attr("disabled", "disabled");
        },
        complete: function (response) {
            $(".js-table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $(".js-date, .js-change-date").removeAttr("disabled");
        },
    })
});

$(document).on("change", "select.js-add-abilities", function () {
    const selectedText = $(this).find("option:selected").text();
    const value = $(this).val();

    let exists = $(".courseplan-abilities-selected .ability-panel-option span").filter(function () {
        return $(this).text().trim() === selectedText;
    }).length > 0;

    if(!exists) {
        let abilityPaneOption = $(`<div class='ability-panel-option'>
                                        <i class="fa fa-check-square"></i>
                                        <span>${selectedText}</span>
                                        <i class="fa fa-remove remove-abilitie js-remove-abilitie"></i>
                                </div>`);
        let hiddenInput = $(`<input type="hidden" class="ability-panel-option-id" value=${value}>`);
        $(".courseplan-abilities-selected").append(abilityPaneOption);
        $(".courseplan-abilities-selected").append(hiddenInput);
    }

    $(this).select2("val", "");

})

$(document).on("click", ".js-remove-abilitie", function () {
    $(this).parent().next().remove();
    $(this).parent().remove();

});

$(document).on("click", ".js-new-class-content", function () {
    if ($(this).text().trim() === "Nova Aula") {
        $(".js-add-new-class-content-form").removeClass("hide");
        $(this).text("Cancelar");
        return;
    }

    $(".js-add-new-class-content-form").addClass("hide");
    $(this).text("Nova Aula");
});



$(".js-change-date").on("click", function () {
    renderFrequencyElement(widthWindow)
    updateClassesContents();
});

let widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
renderFrequencyElement(widthWindow)
updateClassesContents();

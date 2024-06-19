/**
 * Pega as informações do CEP e atualiza os campos necessários.
 *
 * @param {structure} array(UF, City)
 * @returns {void}
 * */

function updateCep(data) {
    data = JSON.parse(data);
    if (data.UF == null) {
        $(formDocumentsAndAddress + 'cep').val('').trigger('focusout');
    }
    $(formDocumentsAndAddress + 'edcenso_uf_fk')
        .val(data.UF)
        .select2('val', data.UF)
        .select2('readonly', data.UF != null)

    $('#InstructorDocumentsAndAddress_edcenso_city_fk option:not(:contains("Selecione uma cidade"))').remove();
    $.ajax({
        type: "POST",
        url: "?r=instructor/getCity",
        data: {
            edcenso_uf_fk: data.UF,
            current_city: $("#InstructorDocumentsAndAddress_edcenso_city_fk").val()
        },
        success: function (response) {
            const optionsList = JSON.parse(response);
            const options = optionsList.join("");
            $("#InstructorDocumentsAndAddress_edcenso_city_fk").html(options)
                .val(data.City)
                .select2('val', data.City)
                .select2('readonly', data.City != null);
        }
    });
}

$("#InstructorIdentification_edcenso_uf_fk").on("change", function () {
    loadCitiesTo(this, "#InstructorIdentification_edcenso_city_fk");
});

$("#InstructorDocumentsAndAddress_edcenso_uf_fk").on("change", function () {
    $('#InstructorDocumentsAndAddress_edcenso_city_fk option:not(:contains("Selecione uma cidade"))').remove();
    loadCitiesTo(this,"#InstructorDocumentsAndAddress_edcenso_city_fk");
});

$("#IES").on("change", function () {
    loadIES("#IES", "#InstructorVariableData_high_education_institution_code_1_fk");
});

$(function () {
    const currentIES = $("#InstructorVariableData_high_education_institution_code_1_fk").val();
    loadIES("#IES", "#InstructorVariableData_high_education_institution_code_1_fk", currentIES);
});


function loadCitiesTo(emmiter , citySelector){
    $.ajax({
        type: "POST",
        url: "?r=instructor/getCity",
        data: {
            edcenso_uf_fk: $(emmiter).val(),
            current_city: $(citySelector).val()
        },
        success: function (response) {
            const optionsList = JSON.parse(response);
            const options = optionsList.join("");
            $(citySelector).html(options);
            $(citySelector).select2();
        }
    });
}


function loadIES(iesUfDropDown, iesDropDownPath, currentIES) {
    $.ajax({
        type: "POST",
        url: "?r=instructor/getinstitution",
        data: {
            edcenso_uf_fk: $(iesUfDropDown).val(),
        },
        success: function (response) {
            var options = response.map((item) => {
                return $(`<option value=${item.id} >${item.name}</option>`)
            },

            );
            options.push("<option value=9999999>OUTRO</option>")
            $(iesDropDownPath).html(options);
            $(iesDropDownPath).select2("val", currentIES);
            //  $("#s2id_InstructorVariableData_high_education_institution_code_1_fk").prop("disabled", false);
        }
    });
}

function renderClasroomsCards(){
    let params = new URLSearchParams(document.location.search);
    let instructorId = params.get("id");
    if(instructorId) {
        $.ajax({
            url: "?r=instructor/getclassrooms",
            type: "POST",
            data: {
                instructorId: instructorId
            }
        }).success(function (response) {
             const result = JSON.parse(response);
            const classrooms = $(".js-add-classrooms-cards");
             var cardsClassrooms = result.reduce((acc, element) =>
                acc += `
                <div class="column clearfix no-grow">
                     <a href="${window.location.host}?r=classroom/update&id=${element["id"]}" class="t-cards">
                        <div class="t-cards-content">
                            <div class="t-tag-primary">${element["discipline_name"]}</div>
                            <div class="t-cards-title">${element["name"]}</div>
                            <div class="t-cards-text clear-margin--left">${element["stage_name"]}</div>
                        </div>
                    </a>
                </div>`, "");
            classrooms.html(DOMPurify.sanitize(cardsClassrooms));
        })
    }
 }

 renderClasroomsCards();

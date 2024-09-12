let foodsRelation = [];

$(document).ready(function() {
    let foodSelect = $('#foodSelect');
    let foodNotice = $('#foodNotice');

    const $params = new URLSearchParams(window.location.search);
    const $id = $params.get('id');

    if($id != null) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/farmerregister/getFarmerFoods",
            cache: false,
            data: {
                id: $id,
            }
        }).success(function(response) {
            let data = DOMPurify.sanitize(response)
            let farmerFoods = JSON.parse(data);
            foodsRelation = farmerFoods;
            renderFoodsTable(farmerFoods);
        });
        $('#farmerName').removeAttr('disabled');
        $('#farmerPhone').removeAttr('disabled');
        $('#farmerGroupType').removeAttr('disabled');
    }

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerregister/getFoodNotice",
        cache: false
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let food_notices = JSON.parse(data);

        Object.entries(food_notices).forEach(function([id, value]) {
            foodNotice.append($('<option>', {
                value: id,
                text: value.name
            }));
        });
    });

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerregister/getFoodAlias",
        cache: false
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let foods_description = JSON.parse(data);

        Object.entries(foods_description).forEach(function([id, value]) {
            let description = value.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            value = id + ',' + value.measurementUnit;
            foodSelect.append($('<option>', {
                value: value,
                text: description
            }));
        });
    })
});

$(document).on("focusout", "#farmerCpf", function () {
    const $params = new URLSearchParams(window.location.search);
    let id = $params.get('id');
    let farmerCpf = $(this).val().replace(/\D/g, '');

    if(id == null && farmerCpf != '') {
        if(farmerCpf.length < 11) {
            $('#info-alert').removeClass('hide').html("Informe o CPF completo");
        } else {
            $.ajax({
                type: 'POST',
                url: "?r=foods/farmerregister/getFarmerRegister",
                cache: false,
                data: {
                    farmerCpf: farmerCpf,
                }
            }).success(function(response) {
                console.log(response);
                let data = DOMPurify.sanitize(response);
                let farmerRegister = JSON.parse(data);
                if("error" in farmerRegister) {
                    if(farmerRegister.error == "Existente ativo") {
                        $('#info-alert').removeClass('hide').addClass('alert-error').html("O CPF do agricultor informado já possui cadastro no TAG");
                    } else {
                        $('#info-alert').removeClass('hide').addClass('alert-error').html(
                            `O CPF do agricultor informado já possui cadastro no TAG e está inativo. <br>
                            Para ativar o agricultor, clique <a href="?r=foods/farmerregister/activateFarmers"> <strong>>> aqui <<</strong></a>`
                        );
                    }
                } else {
                    $('#farmerName').removeAttr('disabled');
                    $('#farmerPhone').removeAttr('disabled');
                    $('#farmerGroupType').removeAttr('disabled');

                    if(Object.keys(farmerRegister).length != 0) {
                        $('#info-alert').addClass('hide')
                        let groupTypeSelect = $('#farmerGroupType');
                        $("#farmerName").val(farmerRegister['name']);
                        $("#farmerPhone").val(farmerRegister['phone']);
                        groupTypeSelect.val(farmerRegister['groupType']);
                        groupTypeSelect.trigger("change");
                    } else {
                        $('#info-alert').removeClass('hide').html("O cpf informado não possui cadastro, informe os dados básicos");
                    }
                }
            });
        }
    }
});

$(document).on("change", "#foodSelect", function () {
    let measurementUnit = this.value.split(',')[1];
    let measurementUnitSelect = $('#measurementUnit');
    measurementUnitSelect.empty();
    switch (measurementUnit) {
        case "g":
            measurementUnitSelect.append($('<option value="g" selected>g</option><option value="Kg">Kg</option>'));
            break;
        case "u":
            measurementUnitSelect.append($('<option value="unidade" selected>Unidade</option><option value="g">g</option><option value="Kg">Kg</option>'));
            break;
        case "l":
            measurementUnitSelect.append($('<option value="l" selected>L</option>'));
            break;
    }
    measurementUnitSelect.val('');
    measurementUnitSelect.trigger("change");
});

$(document).on("click", "#js-add-food", function () {
    let food = $('#foodSelect').find('option:selected').text();
    let foodId = $('#foodSelect').val().split(',')[0];
    let amount = $('#amount').val();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let notice = $('#foodNotice').find('option:selected').text();
    let noticeId = $('#foodNotice').find('option:selected').val();

    if(foodId == "alimento" || amount == "") {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else if(amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
        let existingIndex = $.map(foodsRelation, function(obj, index) {
            return obj.id === foodId ? index : null;
        })[0];

        if(existingIndex !== undefined) {
            foodsRelation[existingIndex].amount = amountCalculation(foodsRelation[existingIndex].amount, amount, measurementUnit, foodsRelation[existingIndex].measurementUnit);
        } else {
            foodsRelation.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit, notice: notice, noticeId: noticeId});
        }
        renderFoodsTable(foodsRelation);
    } else {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Quantidade informada não é válida, utilize números positivos e se decimal, separe por '.'");
    }
});

$(document).on("click", "#remove-food-button", function () {
    let id = $(this).attr('data-foodId');
    foodsRelation.splice(id, 1);
    renderFoodsTable(foodsRelation);
});

$(document).on("click", "#save-farmer", function () {
    let name = $("#farmerName").val();
    let cpf = $("#farmerCpf").val().replace(/\D/g, '');
    let phone = $("#farmerPhone").val().replace(/\D/g, '');
    let groupType = $('#farmerGroupType').find('option:selected').text();
    $(this).prop('disabled', true);

    let params = new URLSearchParams(window.location.search);
    let id = params.get('id');

    if(name == "" || cpf == "" || phone == "") {
        $(this).prop('disabled', false);
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else if(id != null) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/farmerRegister/updateFarmerRegister",
            cache: false,
            data: {
                farmerId: id,
                name: name,
                cpf: cpf,
                phone: phone,
                groupType: groupType,
                foodsRelation: foodsRelation
            }
        }).success(function(response) {
            window.location.href = "?r=foods/farmerregister/index";
        });
    } else {
        $.ajax({
            type: 'POST',
            url: "?r=foods/farmerRegister/createFarmerRegister",
            cache: false,
            data: {
                name: name,
                cpf: cpf,
                phone: phone,
                groupType: groupType,
                foodsRelation: foodsRelation
            }
        }).success(function(response) {
            window.location.href = "?r=foods/farmerregister/index";
        })
    }
})

$('#farmerCpf').mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$('#farmerPhone').mask("(00) 00000-0000", {
    placeholder: "(__) _____-____",
});

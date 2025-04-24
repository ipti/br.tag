let foodsRelation = [];
let foodNoticeItems = []

$(document).ready(function() {
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

            $.ajax({
                type: 'POST',
                url: "?r=foods/farmerregister/getFarmerDeliveries",
                cache: false,
                data: {
                    farmer: $id,
                }
            }).success(function(response) {
                let data = DOMPurify.sanitize(response)
                let farmerDeliveries = JSON.parse(data);
                renderAcceptedFoodsTable(farmerDeliveries.acceptedFoods);
                renderDeliveredFoodsTable(farmerDeliveries.deliveredFoods);
                renderFoodRelationTable(farmerDeliveries.deliveredFoods, foodsRelation);
            });
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
    let foodId = this.value.split(',')[0];
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

    if(foodId != "alimento") {
        foodId = parseInt(foodId);
        const itemEncontrado = foodNoticeItems.find(item => item.foodId === foodId);
        $('#food-alert').removeClass('hide').html(`<span class="t-info_positive"> A quantidade máxima do alimento selecionado no edital é: ${itemEncontrado.yearAmount}${itemEncontrado.measurementUnit}`);
    }
});

$(document).on("change", "#foodNotice", function () {
    let $notice = $(this).val();

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerRegister/getFoodNoticeItems",
        cache: false,
        data: {
            notice: $notice,
        }
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        foodNoticeItems = JSON.parse(data);

        $('#foodSelect').html('<option value="alimento">Selecione o Alimento</option>').trigger('change');

        Object.entries(foodNoticeItems).forEach(function([id, value]) {
            let foodId = value.foodId + ',' + value.measurementUnit;
            $('#foodSelect').append($('<option>', {
                value: foodId,
                text: value.foodName
            }));
        });
    });
});

$(document).on("click", "#js-add-food", function () {
    let food = $('#foodSelect').find('option:selected').text();
    let foodId = $('#foodSelect').val().split(',')[0];
    let amount = $('#amount').val();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let notice = $('#foodNotice').find('option:selected').text();
    let noticeId = $('#foodNotice').find('option:selected').val();

    if(foodId == "alimento" || amount == "" || noticeId == "selecione") {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else if(amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
        foodId = parseInt(foodId);
        let existingIndex = $.map(foodsRelation, function(obj, index) {
            return obj.id === foodId ? index : null;
        })[0];

        if(existingIndex !== undefined) {
            foodsRelation[existingIndex].amount = amountCalculation(foodsRelation[existingIndex].amount, amount, measurementUnit, foodsRelation[existingIndex].measurementUnit);
        } else {
            foodsRelation.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit, notice: notice, noticeId: noticeId});
        }
        renderFoodsTable(foodsRelation);

        $('#measurementUnit').val(null).trigger('change');
        $('#amount').val("");
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

    if(name == "" || cpf == "" || phone == "" || groupType == "Selecione o Grupo") {
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

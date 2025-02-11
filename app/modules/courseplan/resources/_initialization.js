var table;

$(document).ready(function () {
    initTable();
    if ($(".js-course-plan-id").val() !== "") {
        $("#CoursePlan_modality_fk, #CoursePlan_discipline_fk").attr("disabled", "disabled");
    }
});

// Add event listener for opening and closing details
$('#course-classes tbody').on('click', 'td.details-control', function () {

    var tr = $(this).closest('tr');
    var i = $(this).children('img').first();
    var row = table.row(tr);

    let formatSelectionFunction = (d) => $('#validate-index').length > 0 ? format_validate(d) : format(d);

    if (!row.child.isShown()) {
        row.child(formatSelectionFunction(row.data())).show();
        tr.next().find('select.type-select, select.resource-select, select.ability-search-select').select2();
        $.ajax({
            type: "POST",
            url: "?r=courseplan/courseplan/getAbilities",
            cache: false,
            data: {
                discipline: $("#CoursePlan_discipline_fk").val(),
                stage: $("#CoursePlan_modality_fk").val(),
            },
            success: function (data) {
                const abilities = JSON.parse(data);
                abilities.forEach(function(ability) {
                    const formattedText = "(" + ability.code + ") " + ability.description;
                    tr.next().find('select.ability-search-select').append($('<option>', {
                        value: ability.id,
                        text: formattedText
                    }));
                });
            },
        });
        tr.next().find('select.ability-select').select2({
            formatSelection: function (state) {
                var textArray = state.text.split("|");
                return textArray[0];
            },
            formatResult: function (data) {
                var textArray = data.text.split("|");
                if (textArray.length === 1) {
                    return "<div class='ability-optgroup'><b>" + textArray[0] + "</b></div>";
                } else {
                    return "<div><b class='ability-code'>(" + textArray[0] + ")</b> <span class='ability-description'>" + textArray[1] + "</span></div>";
                }
            },
            escapeMarkup: function (m) {
                return m;
            },
        });
        tr.next().addClass("detailed-row").show();
    } else {
        tr.next().toggle();
    }


    if (!tr.next().is(":visible")) {
        i.removeClass('closed');
    } else {
        i.addClass('closed');
    }
});

$(document).on("click", "#new-course-class", function () {
    addCoursePlanRow();
});

$(document).on("click", ".js-remove-course-class", function () {
    if (!$(this).hasClass("js-unavailable")) {
        removeCoursePlanRow(this);
    }
});

$(document).on("keyup", ".course-class-content", function () {
     var content = $(this).val();
    $(this).parents("tr").prev().children(".dt-justify").html(content);
});

$(document).on("change", "#CoursePlan_modality_fk", function (evt, loadingData) {
    $("#CoursePlan_discipline_fk").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=courseplan/courseplan/getDisciplines",
            cache: false,
            data: {
                stage: $("#CoursePlan_modality_fk").val(),
            },
            beforeSend: function () {
                $(".js-course-plan-loading-disciplines").css("display", "inline-block");
                $("#CoursePlan_discipline_fk").attr("disabled", "disabled");
            },
            success: function (data) {
                data = JSON.parse(data);
                var option = "<option value=''>Selecione o componente curricular/eixo...</option>";
                let isMinorEducation = data[0].isMinorEducation;
                if(isMinorEducation == true) {
                    $.each(data, function () {
                        let minorSelectedValue = loadingData !== undefined && $("#minorEducationDisciplines").attr("initval") !== "" && $("#minorEducationDisciplines").attr("initval") == this.id ? "selected" : "";
                        option += "<option value='" + this.id + "' " + minorSelectedValue + ">" + this.name + "</option>";
                    });
                    $("#minorEducationDisciplines").html(option).trigger("change").show();
                    $("#minorEducationContainer").removeClass("hide");
                    $("#disciplinesContainer").addClass("hide");
                } else {
                    $.each(data, function () {
                        var selectedValue = loadingData !== undefined && $("#CoursePlan_discipline_fk").attr("initval") !== "" && $("#CoursePlan_discipline_fk").attr("initval") == this.id ? "selected" : "";
                        option += "<option value='" + this.id + "' " + selectedValue + ">" + this.name + "</option>";
                    });
                    $("#CoursePlan_discipline_fk").html(option).trigger("change").show();
                    $("#disciplinesContainer").removeClass("hide");
                    $("#minorEducationContainer").addClass("hide");
                }
                $(".js-course-plan-loading-disciplines").hide();
                if ($(".js-course-plan-id").val() === "") {
                    $("#CoursePlan_discipline_fk").removeAttr("disabled");
                }
            },
        });
    } else {
        $("#CoursePlan_discipline_fk").html("<option value=''>Selecione o componente curricular/eixo...</option>").trigger("change").show();
    }
});
$("#CoursePlan_modality_fk").trigger("change", [true]);

$(document).on("change", "#CoursePlan_discipline_fk, #minorEducationDisciplines", function () {
    $(".js-abilities-parents, .js-abilities-panel").html("");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=courseplan/courseplan/getAbilitiesInitialStructure",
            cache: false,
            data: {
                discipline: $(this).val()
            },
            beforeSend: function () {
                $(".js-course-plan-loading-abilities").css("display", "inline-block");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (Object.keys(data.options).length) {
                    $(".js-alert-ability-structure").hide();
                    if (data.options[0].code === null) {
                        $(".js-abilities-parents").html(DOMPurify.sanitize(buildAbilityStructureSelect(data)));
                        // $(".js-abilities-parents select").select2();
                    } else {
                        $(".js-abilities-panel").html(DOMPurify.sanitize(buildAbilityStructurePanel(data)));
                    }
                    $(".ability-structure-select").css('width', '100%');
                } else {
                    $(".js-alert-ability-structure").text("Não foram cadastradas habilidades para essa disciplina.").show();
                }
                $(".js-course-plan-loading-abilities").hide();
            },
        });
    } else {
        $(".js-alert-ability-structure").text("Para adicionar habilidades, é preciso primeiro escolher a etapa e a disciplina do plano.").show();
    }
});

$(document).on("change", ".ability-structure-select", function () {
    var container = $(this).closest(".ability-structure-container");
    container.nextAll().remove();
    $(".js-abilities-panel").children().remove();

    var selectedValue = $(this).val();
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=courseplan/courseplan/getAbilitiesNextStructure",
            cache: false,
            data: {
                id: selectedValue
            },
            beforeSend: function () {
                $(".loading-abilities-select").show();
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.options[0]?.code === null) {
                    $(".js-abilities-parents").append(DOMPurify.sanitize(buildAbilityStructureSelect(data)));
                } else {
                    $(".js-abilities-panel").html(DOMPurify.sanitize(buildAbilityStructurePanel(data)));
                }
                $(".loading-abilities-select").hide();
                $(".ability-structure-select").css('width', '100%');
            },
        });
    }
});


$(document).on("click", ".add-resource", function (evt) {
    evt.preventDefault();
    if ($(this).parent().find("select.resource-select").val() !== "" && $(this).parent().find(".resource-amount").val() !== "" && $(this).parent().find(".resource-amount").val() > 0) {
        addResource(this);
    }
});

$(document).on("click", ".remove-resource", function () {
    removeResource(this);
});

$(document).on("click", ".add-abilities", function (e) {
    e.preventDefault();
    var tr = $(this).closest("tr").prev();
    var row = table.row(tr);
    $(".course-class-index").val(row.data().class);

    $(".js-abilities-selected").find(".ability-panel-option").remove();
    $(".js-abilities-panel").find(".ability-panel-option").removeClass("selected");
    var options = $(this).closest(".courseplan-ability-container").find(".courseplan-abilities-selected").find(".ability-panel-option");
    if (options.length) {
        var selected = options.clone().appendTo(".js-abilities-selected");
        selected.find("i").removeClass("fa-check-square").addClass("fa-minus-square");
        options.each(function () {
            var optionIdInPanel = $(".js-abilities-panel").find(".ability-panel-option-id[value=" + $(this).find(".ability-panel-option-id").val() + "]");
            if (optionIdInPanel.length) {
                optionIdInPanel.closest(".ability-panel-option").addClass("selected");
            }
        });
        $(".js-abilities-selected").show();
    } else {
        $(".js-abilities-selected").hide();
    }

    $("#js-selectAbilities").modal("show");
});

$(document).on("click", ".add-new-resource", function (e) {
    e.preventDefault();
    $('#js-createResource').modal("show");
})

$(document).on("click", ".remove-new-resource", function (e) {
    e.preventDefault();
    removeNewResource(this);
})

$(document).on('click', '.confirm-new-resource', function (e) {
    e.preventDefault();
    addNewResources();
})

$(document).on('click', '.save-new-resources', function (e) {
    e.preventDefault();
    saveNewResources();
})

$(document).on("click", ".ability-panel-option", function () {
    if ($(this).closest(".js-abilities-panel").length) {
        if (!$(this).hasClass("selected")) {
            var selected = $(this).clone().appendTo(".js-abilities-selected");
            selected.find("i").removeClass("fa-plus-square").addClass("fa-minus-square");
            $(this).addClass("selected");
            $(".js-abilities-selected").show();
        }
    } else if ($(this).closest(".js-abilities-selected").length) {
        var deletedOptionId = $(this).find(".ability-panel-option-id").val();
        $(this).remove();
        $(".js-abilities-panel").find(".ability-panel-option-id[value=" + deletedOptionId + "]").closest(".ability-panel-option").removeClass("selected");
        if (!$(".js-abilities-selected").find(".ability-panel-option").length) {
            $(".js-abilities-selected").hide();
        }
    }
});

$(document).on("change", "select.ability-search-select", function () {
    const selectedText = $(this).find("option:selected").text();
    const value = $(this).val();
    const tr = $(this).closest("tr").prev();
    const row = table.row(tr);
    const index = row.data().class;

    if (!value) return;

    let abilityPaneOption = $(`
        <div class='ability-panel-option'>
            <input type="hidden" class="ability-panel-option-id" value=${value} name="course-class[${index}][ability][${value}]">
            <i class="fa fa-check-square"></i>
            <span>${selectedText}</span>
        </div>
    `);

    $(this).closest('.control-group').find('.courseplan-abilities-selected').append(abilityPaneOption);

    $(this).select2("val", "");
});

$(document).on("click", ".js-add-selected-abilities", function () {
    let div = $(".course-class-" + $(".course-class-index").val());
    div.find(".courseplan-abilities-selected").html($(".js-abilities-selected").find(".ability-panel-option").clone());
    div.find(".courseplan-abilities-selected").find(".ability-panel-option i").removeClass("fa-minus-square").addClass("fa-check-square");
    div.find(".ability-panel-option-id").each(function (index) {
        $(this).attr("name", "course-class[" + $(".course-class-index").val() + "][ability][" + index + "]");
    })
});

$("#print").on('click', function () {
    window.print();
});

$("#save").on('click', function () {
    $("#js-submit-div").addClass("hide");
    $("#js-loading-div").removeClass("hide");
    var submit = validateSave();
    if (submit) {
        $("#course-plan-form").submit();
    } else {
        $("#js-submit-div").removeClass("hide");
        $("#js-loading-div").addClass("hide");
    }
});

$(document).on('click', '#save-approval', function (e) {
    e.preventDefault();
    const courseplanId = $('#course-plan-form')[0][0].value;
    const observation = $('.validate-description').val();
    const approval = $('#approved_field')[0].checked;
    $.ajax({
        type: "POST",
        url: "?r=courseplan/courseplan/validatePlan&id=" + courseplanId,
        cache: false,
        data: {
            approval_field: approval,
            observation: observation
        },
        success: function () {
            window.location.href = "?r=courseplan/courseplan/pendingPlans";
        },
    });
})

// AJAX Request after change on stage select
$(document).on('change', '#stage', function () {
    if($('.courseplan_table_div').length > 0)
    {
        updateCoursePlanTable();
    }
    if($('.pending_courseplan_table_div').length > 0)
    {
        updatePendingPlanTable()
    }
    $.ajax({
        url: '?r=courseplan/courseplan/getDisciplines',
        type: "POST",
        data: {
            stage: $(this).val()
        }
    }).success(function (data) {
        data = JSON.parse(data);
        $('.discipline-container').removeClass('hide');
        const disciplineContainer = $('#discipline');
        const htmlElement = data.reduce(
            (acc, discipline) =>
                concatenateHtmlOptions(acc, discipline),
                '<option val="default">Selecione a disciplina</option>');
        disciplineContainer.html(htmlElement);
        disciplineContainer.select2("val", "");
    })
})

// AJAX Request after change on instructor select
$(document).on('change', '#instructor', function () {
    updatePendingPlanTable();
})

$('#discipline').on('change', function () {

    if($('#discipline option:selected').index() != 0)
    {
        // Courseplan table
        if($('.courseplan_table_div').length > 0)
            updateCoursePlanTable();

        // Pending Courseplan table
        if($('.pending_courseplan_table_div').length > 0)
            updatePendingPlanTable();
    }
})

function concatenateHtmlOptions(htmlElement, discipline){
    const optionElement = `<option value="${discipline['id']}">${discipline['name']}</option>`;
    htmlElement += optionElement;
    return htmlElement;
}

function updatePendingPlanTable(){
    const disciplineValidade = $('#discipline option:selected').index() == 0 || $('#discipline').hasClass('hide');
    $.ajax({
        url: '?r=courseplan/courseplan/pendingPlans',
        type: "POST",
        data: {
            instructor: $('#instructor').val(),
            stage: $('#stage').val(),
            discipline: disciplineValidade ? '' : $('#discipline').val(),
        }
    }).success(function (data) {
        $('.pending_courseplan_table_div').html(DOMPurify.sanitize(data));
        initDatatable();
    })
}

function updateCoursePlanTable(){
    const disciplineValidade = $('#discipline option:selected').index() == 0 || $('#discipline').hasClass('hide');
    $.ajax({
        url: '?r=courseplan/courseplan/index',
        type: "POST",
        data: {
            stage: $('#stage').val(),
            discipline: disciplineValidade ? '' : $('#discipline').val(),
        }
    }).success(function (data) {
        $('.courseplan_table_div').html(DOMPurify.sanitize(data));
        initDatatable();
    })
}

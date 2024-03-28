$(document).on(
    "change",
    "#GradeUnity_edcenso_stage_vs_modality_fk",
    function () {
        $(".alert-required-fields, .alert-media-fields").hide();
        loadStructure();
    }
);

$(document).on("keyup", ".unity-name", function (e) {
    const unity = $(this).closest(".unity");
    unity.find(".unity-title").html($(this).val());
});

$(document).on("click", ".js-new-unity", function (e) {
    const unities = $(".unity").length;
    const isUnityConcept = $(".js-rule-type").select2("val") === "C";
    const unityHtml = template`
        <div class='unity column is-three-fifths'>
            <div class='row unity-heading ui-accordion-header'>
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collaps-${unities}">
                    <h2 class="unity-title accordion-heading">Unidade: </h2>
                </a>
                <span class="remove-button js-remove-unity t-button-icon-danger t-icon-trash  js-change-cursor"></span>
            </div>
            <div id="collaps-${unities}"class=" collapse ${
        unities == 0 ? "in" : ""
    }">
                <input type='hidden' class="unity-id">
                <input type="hidden" class="unity-operation" value="create">
                <div class="t-field-text" style="margin-top: 16px">
                    <label class='t-field-text__label--required'>Nome: </label>
                    <input type='text' class='t-field-text__input unity-name' placeholder='1ª Unidade, 2ª Unidade, Recuperação Final, etc.'>
                </div>
                <div class="t-field-select">
                    <label class='t-field-select__label--required'>Modelo: </label>
                    <select class='t-field-select__input js-type-select select-search-on control-input'>
                        ${
                            isUnityConcept
                                ? `<option value='UC'>Unidade por conceito</option>`
                                : `<option value='U'>Unidade</option>
                                   <option value='UR'>Unidade com recuperação</option>`
                        }
                    </select>
                </div>
                <div class="t-field-select js-calculation ${
                    isUnityConcept ? "hide" : "show"
                }" >
                    <label class='t-field-select__label--required'>Forma de cálculo:  </label>
                    <select class='t-field-select__input js-formula-select select-search-on control-input'>
                        ${$(".formulas")[0].innerHTML}
                    </select>
                </div>
                <div class="row">
                    <div class="column">
                        <h4>Modalidades avaliativas: </h4>
                        <p class="subheading">
                        Gerencie todas as formas de avalição que compõe as notas dessa unidade avaliativa
                        </p>
                    </div>
                    <a href="#new-modality" id="new-modality" class="js-new-modality t-button-primary">
                        <img alt="Unidade" src="/themes/default/img/buttonIcon/start.svg">Modalidade
                    </a>
                </div>
                <div class="t-cards js-modality-container"></div>
            </div>
    </div>`;

    $(".js-grades-structure-container").append(unityHtml);
    if ($(".js-rule-type").select2("val") === "C") {
        $(".js-new-modality").last().trigger("click").hide();
        $(".remove-modality").last().hide();
    }
    $(".unity").last().find(".js-type-select, .js-formula-select").select2();
});

$(document).on("change", ".js-type-select", function (e) {
    var unity = $(this).closest(".unity");
    if ($(this).val() === "UR") {
        unity.find(".js-new-modality").trigger("click").show();
        unity.find(".js-calculation").show();
        unity.find(".modality[concept=1]").remove();
        unity
            .find(".modality")
            .last()
            .children("label")
            .html("Recuperação: " + '<span class="red">*</span>');
        unity
            .find(".modality")
            .last()
            .find(".modality-name")
            .attr("modalitytype", "R")
            .css("width", "calc(100% - 140px)");
        unity
            .find(".modality")
            .last()
            .find(".remove-modality, .weight")
            .remove();
    } else if ($(this).val() === "UC") {
        unity.find(".modality").remove();
        unity.find(".js-new-modality").trigger("click").hide();
        unity.find(".js-formula-select").val("1").trigger("change");
        unity.find(".js-calculation").hide();
        unity.find(".remove-modality").hide();
        unity
            .find(".modality-name[modalitytype=R]")
            .closest(".modality")
            .remove();
        unity
            .find(".modality")
            .last()
            .attr("concept", "1")
            .find(".remove-modality")
            .remove();
    } else {
        unity.find(".js-new-modality").show();
        unity.find(".js-calculation").show();
        unity
            .find(".modality-name[modalitytype=R]")
            .closest(".modality")
            .remove();
        unity.find(".modality[concept=1]").remove();
    }
});

$(document).on("change", ".js-rule-type", function (e) {
    initRuleType(e.target.value);
});

$(document).on("change", ".js-formula-select", function (e) {
    var unity = $(this).closest(".unity");
    const selectedValue = $(this).select2("data").text;
    if (selectedValue === "Peso") {
        unity
            .find(".modality-name[modalitytype=C]")
            .css("width", "calc(100% - 240px)");
        unity
            .find(".modality-name[modalitytype=C]")
            .parent()
            .append(
                template`
                    <div class="t-field-text">
                        <label class='t-field-text__label--required'>Peso:</span></label>
                        <input type='text' class='t-field-text__input weight' placeholder='Peso'>
                    </div>
                `
            );
    } else {
        unity.find(".weight").remove();
        unity
            .find(".modality-name[modalitytype=C]")
            .css("width", "calc(100% - 140px)");
    }
});

$(document).on("keyup", ".weight", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        var weight = /[1-9]|10/;
        if (val?.match(weight) === null) {
            val = "";
        }
        if (val > 10) {
            val = 10;
        }
    }
    this.value = val;
});

$(document).on("click", ".js-new-modality", function (e) {
    e.preventDefault();
});

$(document).on("click", ".js-remove-unity", function (e) {
    const unity = $(this).closest(".unity");
    const isNew = unity.find(".unity-id").val() === "";

    if (isNew) {
        unity.remove();
    } else {
        const response = confirm(
            "Ao remover um unidade, você está pagando TODAS as notas vinculadas a ela, em todas as disciplinas. Tem certeza que deseja seguir?"
        );
        if (response) {
            $(this)
                .children(".modality")
                .find(".modality-operation")
                .val("remove");
            unity.find(".unity-operation").val("remove");
            unity.hide();
        }
    }
});

$(document).on("click", ".remove-modality", function (e) {
    const modality = $(this).closest(".modality");
    const isNew = modality.find(".modality-id").val() === "";
    modality.find(".modality-operation").val("remove");
    modality.hide();
    if (isNew) {
        modality.remove();
    }
});

$(document).on("click", ".save", function (e) {
    const valid = checkValidInputs();
    if (valid) {
        saveUnities(false);
    }
});

$(document).on("click", ".save-and-reply", function (e) {
    const valid = checkValidInputs();
    if (valid) {
        $("#js-saveandreply-modal").modal("show");
        $(".reply-option[value=S]").prop("checked", true);
    }
});

$(document).on("click", ".js-save-and-reply-button", function (e) {
    saveUnities(true);
});

$(document).on("change", ".js-has-final-recovery", function (event) {
    const isChecked = $(this).is(":checked");
    const isNew = $(".final-recovery-unity-id").val() === "";
    if (isChecked) {
        $(".js-recovery-form").show();
        if (isNew) {
            $(".final-recovery-unity-operation").val("create");
        } else {
            $(".final-recovery-unity-operation").val("update");
        }
    } else {
        // debugger
        $(".js-recovery-form").hide();
        if (!isNew) {
            $(".final-recovery-unity-operation").val("delete");
        }
    }
});

$(document).on("change", ".js-has-semianual-unity", function (event) {
    const isChecked = $(this).is(":checked");
    const isNew = $(".semianual-unity-id").val() === "";
    if (isChecked) {
        $(".js-recovery-semianual-form").show();
        if (isNew) {
            $(".semianual-unity-operation").val("create");
        } else {
            $(".semianual-unity-operation").val("update");
        }
    } else {
        $(".js-recovery-semianual-form").hide();
        if (!isNew) {
            $(".semianual-unity-operation").val("delete");
        }
    }
});

function initRuleType(ruleType) {
    if (ruleType === "C") {
        $(".numeric-fields").hide();
        $(".js-recovery-form").hide();
        $(".final-recovery-unity-operation").val("delete");
        $(".js-calculation").hide();
        $(".remove-modality").hide();
    } else if (ruleType === "N") {
        $(".numeric-fields").show();
        $(".js-has-final-recovery").trigger("change");
        $(".js-calculation").show();
        $(".js-new-modality").show();
        $(".remove-modality").show();
    }
}

function saveUnities(reply) {
    const unities = [];
    $(".unity").each(function () {
        const modalities = [];
        $(this)
            .find(".modality")
            .each(function () {
                modalities.push({
                    id: $(this).find(".modality-id").val(),
                    name: $(this).find(".modality-name").val(),
                    type: $(this).find(".modality-name").attr("modalitytype"),
                    weight: $(this).find(".weight").length
                        ? $(this).find(".weight").val()
                        : null,
                    operation: $(this).find(".modality-operation").val(),
                });
            });
        unities.push({
            id: $(this).find(".unity-id").val(),
            name: $(this).find(".unity-name").val(),
            type: $(this).find("select.js-type-select").val(),
            formula: $(this).find("select.js-formula-select").val(),
            operation: $(this).find(".unity-operation").val(),
            modalities: modalities,
        });
    });
    $.ajax({
        type: "POST",
        url: "?r=admin/saveUnities",
        cache: false,
        data: {
            stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
            unities: unities,
            approvalMedia: $(".approval-media").val(),
            hasFinalRecovery: $("#has_final_recovery").is(":checked"),
            hasRecoverySemianual: $("#has_semianual_recovery").is(":checked"),
            finalRecovery: {
                id: $(".final-recovery-unity-id").val(),
                name: $(".final-recovery-unity-name").val(),
                type: $(".final-recovery-unity-type").val(),
                grade_calculation_fk: $(".calculation-final-media").select2(
                    "val"
                ),
                operation: $(".final-recovery-unity-operation").val(),
            },
            semianualRecovery: [
                {
                    id: $(".semianual-unity-id").val(),
                    name: $(".one-semianual-unity-name").val(),
                    type: $(".semianual-unity-type").val(),
                    grade_calculation_fk: $(
                        ".semianual-recovery-unity-calculation"
                    ).select2("val"),
                    operation: $(".semianual-unity-operation").val(),
                },
                {
                    id: $(".semianual-unity-id").val(),
                    name: $(".two-semianual-unity-name").val(),
                    type: $(".semianual-unity-type").val(),
                    grade_calculation_fk: $(
                        ".semianual-recovery-unity-calculation"
                    ).select2("val"),
                    operation: $(".semianual-unity-operation").val(),
                },
            ],
            finalRecoverMedia: $(".final-recover-media").val(),
            semiRecoverMedia: $("#semianual-unity-media").val(),
            finalMediaCalculation: $(".calculation-final-media").select2("val"),
            reply: reply ? $(".reply-option:checked").val() : "",
            ruleType: $(".js-rule-type").select2("val"),
        },
        beforeSend: function (e) {
            $(".alert-media-fields")
                .addClass("alert-warning")
                .removeClass("alert-success")
                .text(
                    "Atualizando resultados dos alunos, o processo pode demorar..."
                )
                .show();
            $(".buttons a, .js-grades-structure-container")
                .css("opacity", "0.4")
                .css("pointer-events", "none");
            $("#GradeUnity_edcenso_stage_vs_modality_fk").attr(
                "disabled",
                "disabled"
            );
            $(".save-unity-loading-gif").css("display", "inline-block");
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".alert-required-fields")
                    .addClass("alert-success")
                    .removeClass("alert-error")
                    .text("Estrutura de notas cadastrada com sucesso!")
                    .show();
                loadStructure();
            }
        },
        error: function (request) {
            let message =
                "Um erro inesperado aconteceu no servidor, não foi possível completar operação";
            if (request.status == 400) {
                message = request.responseText;
            }
            $(".alert-required-fields")
                .addClass("alert-error")
                .removeClass("alert-success")
                .text(message)
                .show();
        },
        complete: function () {
            $(".alert-media-fields")
                .removeClass("alert-warning")
                .addClass("alert-success")
                .text("Médias atualizadas com sucesso!");
            $("html, body").animate({ scrollTop: 0 }, "fast");
            $(".buttons a, .js-grades-structure-container")
                .css("opacity", "1")
                .css("pointer-events", "auto");
            $("#GradeUnity_edcenso_stage_vs_modality_fk").removeAttr(
                "disabled"
            );
            $(".save-unity-loading-gif").hide();
        },
    });
}

function checkValidInputs() {
    $(".alert-required-fields, .alert-media-fields").hide();
    let valid = true;
    let message = "";
    if (
        $(".js-rule-type").select2("val") === "N" &&
        ($(".approval-media").val() === "" ||
            ($(".js-has-final-recovery").is(":checked") &&
                $(".final-recover-media").val() === ""))
    ) {
        valid = false;
        message = "Os campos de média são obrigatórios.";
    } else if (
        $(".js-rule-type").select2("val") === "N" &&
        $(".js-has-final-recovery").is(":checked") &&
        $(".approval-media").val() < $(".final-recover-media").val()
    ) {
        valid = false;
        message =
            "A média de recuperação final não pode ser superior à de aprovação.";
    }

    if (valid) {
        if ($(".unity").length) {
            let ucCount = 0;
            let rsCount = 0;
            let rsIndexes = [];
            $(".unity").each(function (index) {
                if ($(this).find(".unity-name").val() === "") {
                    valid = false;
                    message = "Preencha o nome das unidades.";
                    return false;
                }
                $(this)
                    .find(".modality")
                    .each(function () {
                        if ($(this).find(".modality-name").val() === "") {
                            valid = false;
                            message = "Preencha o nome das modalidades.";
                            return false;
                        }
                        if ($(this).find(".weight").val() === "") {
                            valid = false;
                            message = "Preencha o peso das modalidades.";
                            return false;
                        }
                    });
                if ($(this).find("select.js-type-select").val() === "UC") {
                    ucCount++;
                }
                if ($(this).find("select.js-type-select").val() === "UR") {
                    if (
                        !$(this).find(".modality-name[modalitytype=C]").length
                    ) {
                        valid = false;
                        message =
                            'Unidades do modelo "Unidade com recuperação" requer duas ou mais modalidades.';
                        return false;
                    }
                } else {
                    if (!$(this).find(".modality-name").length) {
                        valid = false;
                        message =
                            "Não se pode cadastrar unidades sem modalidade.";
                        return false;
                    }
                }
                if (
                    index === 0 &&
                    ($(this).find("select.js-type-select").val() === "RF" ||
                        $(this).find("select.js-type-select").val() === "RS")
                ) {
                    valid = false;
                    message =
                        "Uma unidade de recuperação semestral ou final não podem ser a primeira.";
                    return false;
                }

                if (
                    rsCount === 2 &&
                    $(this).find("select.js-type-select").val() !== "RF"
                ) {
                    valid = false;
                    message =
                        "Não pode haver unidades após a 2ª recuperação semestral.";
                    return false;
                }
                if ($(this).find("select.js-type-select").val() === "RS") {
                    rsCount++;
                    rsIndexes.push(index);
                }
            });
            if (rsIndexes.length && rsIndexes[1] - rsIndexes[0] === 1) {
                valid = false;
                message = "Não pode haver 02 recuperações semestrais seguidas.";
            }
            if (rsCount !== 0 && rsCount !== 2) {
                valid = false;
                message =
                    "Quando utilizadas, devem haver 02 recuperações semestrais.";
            }
            if (ucCount > 0 && ucCount !== $(".unity").length) {
                valid = false;
                message =
                    "Quando uma unidade por conceito for utilizada, nenhum outro modelo pode ser utilizado.";
            }
        } else {
            valid = false;
            message =
                "Não se pode cadastrar uma estrutura de notas sem unidade.";
        }
    }

    if (!valid) {
        $(".alert-required-fields")
            .addClass("alert-error")
            .removeClass("alert-success")
            .text(message)
            .show();
        $("html, body").animate({ scrollTop: 0 }, "fast");
    }
    return valid;
}

function loadStructure() {
    if ($("#GradeUnity_edcenso_stage_vs_modality_fk").val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=admin/getUnities",
            cache: false,
            data: {
                stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
            },
            beforeSend: function (e) {
                $(".js-grades-structure-loading").css(
                    "display",
                    "inline-block"
                );
                $(".js-grades-structure-container")
                    .css("pointer-events", "none")
                    .css("opacity", "0.4");
            },
            success: function (data) {
                data = JSON.parse(data);
                $(".stagemodalityname").text(data.stageName);
                $(".stagename").text(
                    $("#GradeUnity_edcenso_stage_vs_modality_fk").select2(
                        "data"
                    ).text
                );
                $(".js-grades-structure-container").children(".unity").remove();
                $(".approval-media").val(data.approvalMedia);
                $("#has_final_recovery").prop("checked", data.hasFinalRecovery);
                $("#has_semianual_recovery").prop(
                    "checked",
                    data.hasRecoverySemianual
                );
                $(".calculation-final-media").select2(
                    "val",
                    data.mediaCalculation
                );
                $(".js-rule-type").select2("val", data.ruleType);
                $(".final-recover-media").val(data.finalRecoverMedia);
                $("#semianual-unity-media").val(data.semiRecoverMedia);
                $(".final-recovery-unity-operation").val(
                    data.final_recovery !== null ? "update" : "create"
                );
                $(".final-recovery-unity-id").val(data.final_recovery.id);
                $(".final-recovery-unity-name").val(data.final_recovery.name);
                $(".final-recovery-unity-calculation").select2(
                    "val",
                    data.final_recovery.grade_calculation_fk
                );
                // $(".semianual-recovery-unity-calculation").select2(
                //     "val",
                //     data.recovery_semianual.grade_calculation_fk
                // );

                const dataUnities = data.unities.map((unity) => [
                    unity.id,
                    unity.name,
                ]);
                const selectUnitiesFirst = $("#semianual-modality-first");
                const selectUnitiesSecond = $("#semianual-modality-second");

                console.log(data);

                dataUnities.forEach(function ([id, name]) {
                    const optionFirst = $("<option>", {
                        value: id,
                        text: name,
                    });
                    const optionSecond = $("<option>", {
                        value: id,
                        text: name,
                    });
                    selectUnitiesFirst.append(optionFirst);
                    selectUnitiesSecond.append(optionSecond);
                });

                $(".final-recover-media").val(data.finalRecoverMedia);
                $(".semiRecoverMedia").val(data.semiRecoverMedia);

                if (data.hasFinalRecovery) {
                    $(".js-recovery-form").show();
                } else {
                    $(".js-recovery-form").hide();
                }

                if (data.hasRecoverySemianual) {
                    $(".js-recovery-semianual-form").show();
                } else {
                    $(".js-recovery-semianual-form").hide();
                }

                $(
                    ".js-grades-structure-container, .js-grades-rules-container"
                ).show();

                if (Object.keys(data.unities).length) {
                    $.each(data.unities, function () {
                        loadDataOnUnity(this, data.ruleType);
                    });
                }

                $(".grades-buttons").css("display", "flex");
                $(
                    ".js-grades-structure-container, .js-grades-rules-container"
                ).show();
                $(".js-grades-structure-loading").hide();
                $(".js-grades-structure-container")
                    .css("pointer-events", "auto")
                    .css("opacity", "1");

                initRuleType(data.ruleType);
            },
        });
    } else {
        $(
            ".js-grades-structure-container, .grades-buttons,  .js-grades-rules-container"
        ).hide();
    }
    $("#accordion").accordion();
}

$(document).on(
    "keyup",
    ".approval-media, .final-recover-media",
    validateGradeFormat
);

function validateGradeFormat(event) {
    let val = this.value;
    if (!$.isNumeric(val)) {
        event.preventDefault();
        val = "";
    } else {
        const gradePattern = /^(100|\d{1,2}(\.\d)?)$|^\d(\.(\d)?)?$/;
        const isMatch = val?.match(gradePattern);
        if (isMatch === null) {
            val = "";
        } else if (!isMatch && val > 10) {
            val = 10;
        } else if (!isMatch && val == 0) {
            val = "";
        }
    }
    this.value = val;
}

function loadDataOnUnity(unityData, ruleType) {
    $("select.js-type-select").html(
        `<option value='UC' selected>Unidade por conceito</option>`
    );

    if (ruleType === "N") {
        $("select.js-type-select").html(` <option value='U'>Unidade</option>
            <option value='UR'>Unidade com recuperação</option>`);
    }

    $(".js-new-unity").trigger("click");
    const unity = $(".unity").last();
    unity.find(".unity-name").val(unityData.name);
    unity.find(".unity-title").html(unityData.name);
    unity.find(".unity-id").val(unityData.id);
    unity.find("select.js-type-select").select2("val", unityData.type);
    unity
        .find("select.js-formula-select")
        .val(unityData.grade_calculation_fk)
        .trigger("change");
    unity.find(".modality").remove();

    $.each(unityData.modalities, function () {
        loadDataOnUnityModalities(unity, this);
    });
}

function loadDataOnUnityModalities(unityDOM, modalityData) {
    unityDOM.find(".js-new-modality").trigger("click");
    let modality = unity.find(".modality").last();
    modality.find(".modality-id").val(modalityData.id);
    modality.find(".modality-name").val(modalityData.name);
    modality.find(".modality-operation").val("update");
    modality.find(".weight").val(modalityData.weight);
}

function mostrarCamposSemestreOne() {
    const camposSemestreOne = document.getElementById(
        "campos-primeiro-semestre"
    );
    if (camposSemestreOne.style.display === "none") {
        camposSemestreOne.style.display = "block";
    } else {
        camposSemestreOne.style.display = "none";
    }
}

function mostrarCamposSemestreTwo() {
    const camposSemestreTwo = document.getElementById(
        "campos-segundo-semestre"
    );
    if (camposSemestreTwo.style.display === "none") {
        camposSemestreTwo.style.display = "block";
    } else {
        camposSemestreTwo.style.display = "none";
    }
}

class UnityComponent extends HTMLElement {
    static get observedAttributes() {
        return ["index", "name", "type", "formula"];
    }
    constructor() {
        super();
        this.shadow = this.attachShadow({ mode: "open" });
        this.render();
    }

    render() {
        this.shadow.innerHTML = "";
        this.shadow.appendChild(this.build());
        this.shadow.appendChild(this.style());
    }

    // attribute change
    attributeChangedCallback(property, oldValue, newValue) {
        if (oldValue === newValue) return;
        this[property] = newValue;
        this.render();
    }

    style() {
        const style = document.createElement("style");
        style.textContent = `
            @import '../themes/default/css/select2.css';
            @import '../sass/css/main.css';
        `;
        return style;
    }

    build() {
        const componentRoot = document.createElement("div");
        const isUnityConcept = this.getAttribute("type") === "UC";

        const typeItems = this._buildTypeItems();
        const formulasItems = this._buildFormulasItems();

        const unityHtml = template`
        <div class='unity column is-three-fifths'>
            <div class='row unity-heading ui-accordion-header'>
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collaps-${this.getAttribute(
                    "index"
                )}">
                    <h2 class="unity-title accordion-heading">Unidade: </h2>
                </a>
                <span class="remove-button js-remove-unity t-button-icon-danger t-icon-trash  js-change-cursor"></span>
            </div>
            <div id="collaps-${this.getAttribute("index")}"class=" collapse ${
            this.getAttribute("index") == 0 ? "in" : ""
        }">
                <input type='hidden' class="unity-id">
                <input type="hidden" class="unity-operation" value="create">
                <div class="t-field-text" style="margin-top: 16px">
                    <label class='t-field-text__label--required'>Nome: </label>
                    <input type='text' class='t-field-text__input unity-name' value="${this.getAttribute(
                        "name"
                    )}"
                            placeholder='1ª Unidade, 2ª Unidade, Recuperação Final, etc.'>
                </div>
                <div class="t-field-select">
                    <label class='t-field-select__label--required'>Modelo: </label>
                    <select class='t-field-select__input js-type-select select-search-on control-input'>
                        ${typeItems}
                    </select>
                </div>
                <div class="t-field-select js-calculation ${
                    isUnityConcept ? "hide" : "show"
                }" >
                    <label class='t-field-select__label--required'>Forma de cálculo:  </label>
                    <select class='t-field-select__input js-formula-select select-search-on control-input'>
                        ${formulasItems}
                    </select>
                </div>
                <div class="row">
                    <div class="column">
                        <h4>Modalidades avaliativas: </h4>
                        <p class="subheading">
                            Gerencie todas as formas de avalição que compõe as notas dessa unidade avaliativa
                        </p>
                    </div>
                    <a href="#new-modality" id="new-modality" class="js-new-modality t-button-primary">
                        <img alt="Unidade" src="/themes/default/img/buttonIcon/start.svg">Modalidade
                    </a>
                </div>
                <slot name="modalities" class="t-cards js-modality-container"></slot>
            </div>
        </div>`;

        componentRoot.innerHTML = unityHtml;

        // $(componentRoot).find(".js-type-select").select2("val", this.getAttribute("type"));

        return componentRoot;
    }

    _buildTypeItems() {
        const typesByConcept = [{ key: "UC", name: "Unidade por conceito" }];
        const typesByNumeric = [
            { key: "U", name: "Unidade" },
            { key: "UR", name: "Unidade com Recuperação" },
        ];

        const types = isUnityConcept ? typesByConcept : typesByNumeric;

        const optionBuilder = () => {
            const isSelected =
                this.getAttribute("type") === e.key ? "selected" : "";
            return `<option value='${e.key}' ${isSelected} >${e.name}</option>`;
        };

        return types.map(optionBuilder);
    }

    _buildFormulasItems() {
        const formulas = Array.from($(".formulas")[0].children).map((e) => {
            return { key: e.value, name: e.innerHTML };
        });

        const optionBuilder = (e) => {
            const isSelected =
                this.getAttribute("formula") === e.key ? "selected" : "";
            return `<option value='${e.key}' ${isSelected}> ${e.name} </option>`;
        };

        const formulasItems = formulas.map(optionBuilder);

        return formulasItems;
    }
}

class UnityConceptComponent extends UnityComponent {}

class UnityModalityComponent extends HTMLElement {
    static get observedAttributes() {
        return ["index", "name", "weight"];
    }
    constructor() {
        super();

        const shadow = this.attachShadow({ mode: "open" });
        shadow.appendChild(this.build());
        shadow.appendChild(this.styles());
    }

    styles() {
        const style = document.createElement("style");
        style.textContent = `
            @import '../sass/css/main.css';
        `;
        return style;
    }

    build() {
        const componentRoot = document.createElement("div");
        componentRoot.setAttribute("class", "card");
        const unityElement = $(this).closest(".unity");
        const modalityHtml = template`
            <div class='modality' concept='0'>
                <input type="hidden" class="modality-id">
                <input type="hidden" class="modality-operation" value="create">
                <div class="row">
                    <div class="t-field-text">
                        <label class='t-field-text__label--required'>Nome da modalidade avaliativa: </label>
                        <input type='text' class='modality-name t-field-text__input' modalitytype='C' value="${this.getAttribute(
                            "name"
                        )}" placeholder='Prova, Avaliação, Trabalho, etc.' style='width: calc(100% - 222px);'>
                        ${
                            this.hasAttribute("weight")
                                ? template`
                                    <div class="t-field-text">
                                        <label class='t-field-text__label--required'>Peso:</span></label>
                                        <input type='text' class='t-field-text__input weight form-control' value="${this.getAttribute(
                                            "weight"
                                        )}" placeholder='Peso'>
                                    </div>`
                                : ""
                        }
                    </div>
                    <span class="remove-modality remove-button t-button-icon-danger t-icon-trash"></span>
                </div>
            </div>`;

        componentRoot.innerHTML = modalityHtml;
        return componentRoot;
    }
}

class DefaultComponent extends HTMLElement {
    static get observedAttributes() {
        return [];
    }
    constructor(builder) {
        super();

        const shadow = this.attachShadow({ mode: "open" });
        shadow.appendChild(builder());
        shadow.appendChild(this.styles());
    }

    styles() {
        const style = document.createElement("style");
        style.textContent = `
            @import '../sass/css/main.css';
        `;
        return style;
    }

    build() {
        const componentRoot = document.createElement("div");
        componentRoot.setAttribute("class", "card");

        componentRoot.innerHTML = "<h1>Unimplemented</h1>";
        return componentRoot;
    }
}

customElements.define("unity-component", UnityComponent);
customElements.define("unity-concept-component", UnityConceptComponent);
customElements.define("unity-modality-component", UnityModalityComponent);

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function rule(string, exp) {
    return ((string.match(exp)) && (string !== ''));
}
function changeNameLength(name, limit) {
    return (name.length > limit) ? name.substring(0, limit - 3) + "..." : name;
}

var dateRules = new Object();
dateRules.time = /^([01][0-9]|2[0-3]):[0-5][0-9]:?([0-5][0-9])?$/;
dateRules.date = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3}|[0-9]{2})$/;

var stringRules = new Object();
stringRules.schoolName = /^[A-Z0-9°ºª\- ]{4,100}$/;
stringRules.classroomName = /^[A-Z0-9°ºª\- ?]{4,80}$/;
stringRules.personName = /^[a-zA-ZÀ-ÖØ-öø-ÿ]{1,100}/;
stringRules.email = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
stringRules.schoolAddress = /^[A-Z0-9°ºª\-\/\., ]*$/;
stringRules.rg = /^[A-Z0-9°ºª\- ]{1,20}$/;
stringRules.instructorAddress = /^[A-Z0-9°ºª\-\/\., ]{1,100}$/;
stringRules.instructorAddressNumber = /^[A-Z0-9°ºª\-\/\., ]{1,10}$/;
stringRules.instructorAddressComplement = /^[A-Z0-9°ºª\-\/\., ]{1,20}$/;
stringRules.instructorAddressNeighborhood = /^[A-Z0-9°ºª\-\/\., ]{1,50}$/;
stringRules.studentAddress = /^[A-Z0-9°ºª\-\/\., ]{1,100}$/;
stringRules.studentAddressNumber = /^[A-Z0-9°ºª\-\/\., ]{1,10}$/;
stringRules.studentAddressComplement = /^[A-Z0-9°ºª\-\/\., ]{1,20}$/;
stringRules.studentAddressNeighborhood = /^[A-Z0-9°ºª\-\/\., ]{1,50}$/;

var numberRules = new Object();
numberRules.cep = /^[0-9]{8}$/;
numberRules.cpf = /^[0-9]{11}$/;
numberRules.nis = /^[0-9]{11}$/;
numberRules.cns = /^[0-9]{15}$/;
numberRules.ddd = /^[0-9]{2}$/;
//numberRules.phone = /^([9]?)+([0-9]{8})$/;
numberRules.phone = /^([0-9]{8,9})$/;
numberRules.count = /^[0-9]{0,4}$/;
numberRules.num = /^[0-9]*$/;


function validateYear(year, min, max) {
    min = (typeof min == 'undefined') ? 1900 : min;
    max = (typeof max == 'undefined') ? new Date().getFullYear() : max;
    return (rule(year, numberRules.num) && year >= min && year <= max);
}

function validateLogin(pass) {
    return pass.length >= 4;
}

function validatePassword(pass) {
    return pass.length >= 6;
}

function validateCount(count) {
    if (count.length == 0) {
        return true;
    } else if (count == 0) {
        return false;
    } else {
        return (rule(count, numberRules.count));
    }
}

function validateRG(rg) {
    return (rule(rg, stringRules.rg));
}


function validateDate(date) {
    return (rule(date, dateRules.date));
}

function validateTime(time) {
    return (rule(time, dateRules.time));

}

function stringToDate(str) {
    var date = new Object();
    date.day = str.split("/")[0];
    date.month = str.split("/")[1];
    date.year = str.split("/")[2];
    date.asianStr = date.year + '/' + date.month + '/' + date.day;
    return date;
}

function validateSchoolName(str) {
    return (rule(str, stringRules.schoolName));
}

function validateClassroomName(str) {
    return (rule(str, stringRules.classroomName));
}

function numbersNotEqual(num) {
    var aux = num[0];
    for (var i = 1; i < num.length; i++) {
        if (num[i] == aux)
            aux = num[i];
        else
            return true;
    }
    return false;
}

function validateCEP(cep) {
    return (numbersNotEqual(cep) && rule(cep, numberRules.cep));
}

function validateAddress(str, max) {
    return (str.length <= max && rule(str, stringRules.schoolAddress));
}
function validateInstructorAddress(str) {
    return rule(str, stringRules.instructorAddress);
}
function validateInstructorAddressNumber(str) {
    return rule(str, stringRules.instructorAddressNumber);
}
function validateInstructorAddressComplement(str) {
    return rule(str, stringRules.instructorAddressComplement);
}
function validateInstructorAddressNeighborhood(str) {
    return rule(str, stringRules.instructorAddressNeighborhood);
}

function validateStudentAddress(str) {
    return rule(str, stringRules.studentAddress);
}
function validateStudentAddressNumber(str) {
    return rule(str, stringRules.studentAddressNumber);
}
function validateStudentAddressComplement(str) {
    return rule(str, stringRules.studentAddressComplement);
}
function validateStudentAddressNeighborhood(str) {
    return rule(str, stringRules.studentAddressNeighborhood);
}

function validateDDD(ddd) {
    return rule(ddd, numberRules.ddd);
}

//function validatePhone(phone, length){
function validatePhone(phone) {
    //return (phone.length <= length && numbersNotEqual(phone) && rule(phone, numberRules.phone));
    return rule(phone, numberRules.phone);
}

function validateEmail(email) {
    return (rule(email, stringRules.email));
}


function anoMinMax(min, max, ano) {
    ano = parseInt(ano);
    if (ano >= min && ano <= max) {
        return true
    }
    return false;
}

function validateBirthdayPerson(date) {
    if (validateDate(date)) {
        var fullDate = new Date(date);
        var year = fullDate.getFullYear();
        return anoMinMax(1918, 1999, year);
    } else {
        return false;
    }
}

function compareSimilarName(name1, name2) {
    name_test_1 = name1.split(' ');
    name_test_2 = name2.split(' ');
    var score = 0;
    var response = false;
    for (let i = 0; i < name_test_2.length; i++) {
        if (name_test_2.includes(name_test_1[i])) {
            if (i <= 1) score += 2;
            else score++;
        }
    }
    if (score >= 3) response = true
    return response
}

const { origin, pathname } = window.location;

const buscar = (dados, chave, conteudo) => {
    const item = dados[chave];
    return Object.values(item).indexOf(conteudo) !== -1 ? item : null;
};

function validateNamePerson(personName, handler) {
    var complete_name = personName.split(' ');
    var passExp = true;
    var passSimilarName = [];
    var ret = new Array();
    for (var i = 0; i < complete_name.length; i++) {
        if (!rule(complete_name[i], stringRules.personName)) {
            passExp = false;
            break;
        }
    }

    $.ajax({
        url: `${origin}${pathname}?r=student/comparestudentname`
    }).success(function (response) {
        const names = $.parseJSON(response);
        var names_teste = Object.keys(names);
        var formatSimilares = [];
        const similares = names_teste.filter((name) => compareSimilarName(name, personName));
        var array_students = []
        for (let index = 0; index < similares.length; index++) {
            student_teste = new Object();
            student_teste.name = similares[index];
            student_teste.id = names[similares[index]];
            array_students.push(student_teste);
        }

        for (let index = 0; index < array_students.length; index++) {
            var similares_text = `<a style="color: #2E33B7; font-size: 13px;" href='${origin}${pathname}?r=student/update&id=${array_students[index].id}' >${array_students[index].name}</a><br>`;
            formatSimilares.push(similares_text);
        }
        if (passExp) {
            if (similares.length > 0) {
                ret[0] = true;
                ret[1] = "Cadastro(s) similar(es) encontrado(s), verifique com atenção os dados. Clique para exibir registros";
                ret[2] = `<p style="display: none;" id="registrosSimilares">${formatSimilares}</p>`
                handler(ret);
                return
            }
            if (isset(complete_name[1])) {
                var str4 = null;
                var until4 = 0;
                for (var i = 0; i < personName.length; i++) {
                    if (personName[i] != str4) {
                        str4 = personName[i];
                        until4 = 1;
                    } else {
                        until4++;
                    }

                    if (until4 > 4) {
                        ret[0] = false;
                        ret[1] = "O nome não deve possuir a mesma letra 4 vezes seguidas.";
                        handler(ret);
                        return;
                    }
                }
            } else {
                ret[0] = false;
                ret[1] = "Nome sem sobrenome.";
                handler(ret);
                return;
            }
        } else {
            ret[0] = false;
            ret[1] = "O campo aceita somente caracteres maiúsculos de A a Z, sem cedilhas e/ou acentos. Tamanho mínimo: 1.";
            handler(ret);
            return;
        }
    })

}

function existsStudentWithCPF(cpf, callback) {
    var ret = new Array();
    var passCpf = false;
    $.ajax({
        url: `${origin}${pathname}?r=student/comparestudentcpf&student_cpf=${cpf}`,
    }).success(function (response) {
        $.each($.parseJSON(response), function (student_fk, id) {
            if (student_fk != '') passCpf = true
        });
        
        if (passCpf) {
            ret[0] = false;
            ret[1] = "CPF já vinculado com cadastro de aluno existente.";
            callback(ret);
            return;
        }
    });
}

function validateCpf(cpf) {
    const statusInvalido = {
            valid: false,
            message: "Informe um CPF válido. Deve possuir apenas números."
    };

    const statusValido = {
        valid: true,
        message: "Válido"
    };

    if (cpf == "00000000000" || cpf == "11111111111"
        || cpf == "22222222222" || cpf == "33333333333"
        || cpf == "44444444444" || cpf == "55555555555"
        || cpf == "66666666666" || cpf == "77777777777"
        || cpf == "88888888888" || cpf == "99999999999"
        || !rule(cpf, numberRules.cpf)) {
       
        return statusInvalido;
    } else {
        var soma = 0;
        for (var i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        var resto = soma % 11;
        var digito1 = resto < 2 ? 0 : 11 - resto;

        soma = 0;
        for (var i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = soma % 11;
        var digito2 = resto < 2 ? 0 : 11 - resto;

        if (digito1 !== parseInt(cpf.charAt(9)) || digito2 !== parseInt(cpf.charAt(10))) {
            return statusInvalido;
        }
    }

    if (cpf == "00000000000" || cpf == "00000000191") {
        return statusInvalido;
    } 
    
    if (!rule(cpf, numberRules.cpf)){
        return statusInvalido;
    }

    return statusValido;

}

function validateCivilCertificationTermNumber(term_number, handler) {
    var ret = new Array();
    var passTerm = false;
    $.ajax({
        url: `${origin}${pathname}?r=student/comparestudentcertificate&civil_certification_term_number=${term_number}`,
    }).success(function (response) {
        $.each($.parseJSON(response), function (student_fk, id) {
            console.log(student_fk)
            if (student_fk != '') passTerm = true
        });
        if (passTerm) {
            ret[0] = false;
            ret[1] = "Nº de certidão já vinculada com cadastro de aluno existente.";
            handler(ret);
            return;
        }
    });
}

function validateCivilRegisterEnrollmentNumber(term_number, handler) {
    term_number = term_number.split(/[.-]/);
    term_number = term_number.join('');
    console.log(term_number);
    var ret = new Array();
    var passTerm = false;
    $.ajax({
        url: `${origin}${pathname}?r=student/comparestudentcivilregisterenrollmentnumber&civil_register_enrollment_number=${term_number}`,
    }).success(function (response) {
        $.each($.parseJSON(response), function (student_fk, id) {
            console.log(student_fk)
            if (student_fk != '') passTerm = true
        });
        if (passTerm) {
            ret[0] = false;
            ret[1] = "Nº de certidão já vinculada com cadastro de aluno existente.";
            handler(ret);
            return;
        }
    });
}

function validateNis(nis) {
    return rule(nis, numberRules.nis);
}

function validateCns(cns) {
    return rule(cns, numberRules.cns);
}

function isset(variable) {
    return (variable != 'undefined' && variable != null);
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

function errorMessage(id, message) {
    removeErrorMessage(id);
    id = $(id).attr("id");
    $("#" + id).parent().append("<span id='" + id + "_error' class='error'><br/>" + message + "</span>");
}

function warningMessage(id, message) {
    removeWarningMessage(id);
    id = $(id).attr("id");
    $("#" + id).parent().append("<span id='" + id + "_warn' class='warning'><br/>" + message.replace(',', '') + "</span>");
    $("#registrosSimilares").css('margin', '5px 0px 0px 1px');
}

function removeErrorMessage(id) {
    $(id + '_error').remove();
}

function removeWarningMessage(id) {
    $(id + '_warn').remove();
}

function errorNotification(id) {
    $(id).parent().children().css("border-color", "#D21C1C");
    $(id).parent().children().css("color", "#D21C1C");
    $(id).parent().children().trigger("mouseover");
    setTimeout(function () { $(id).parent().children().trigger("mouseout"); }, 7000);
}
function removeErrorNotification(id) {
    $(id).parent().children().css("border-color", "");
    $(id).parent().children().css("color", "");
}

function warningNotification(id) {
    $(id).parent().children().css("border-color", "#E98305");
    $(id).parent().children().css("color", "#E98305");
    $(id).parent().children().trigger("mouseover");
    setTimeout(function () { $(id).parent().children().trigger("mouseout"); }, 7000);
}
function removeWarningNotification(id) {
    $(id).parent().children().css("border-color", "");
    $(id).parent().children().css("color", "");
}

function addError(id, message) {
    if (message != null) errorMessage(id, message);
    errorNotification(id);
}
function removeError(id, id_caixa, id_icon) {
    if (id_caixa != null) $(id_caixa).attr('data-original-title', '');
    if (id_icon != null) $(id_icon).css('display', 'none');
    removeErrorMessage(id);
    removeErrorNotification(id);
}

function addWarning(id, message, id_caixa, id_icon) {
    $(id_icon).css('display', 'inline-block');
    $(id_caixa).attr('data-original-title', message);
    warningNotification(id);
}
function removeWarning(id, id_caixa, id_icon) {
    $(id_icon).css('display', 'none');
    $(id_caixa).attr('data-original-title', '');
    removeWarningNotification(id);
}

/**
 * Adiciona a classe "required" ao campo, e adiciona um "*" no final do seu texto;
 * 
 * @param {element} id
 * @returns {nothing}
 */
function addRequired(id) {
    removeRequired(id);
    $(id).addClass('required');
    $(id).text($(id).text() + "*");
}
/**
 * Remove a classe "required" ao campo, e remove o "*" do seu texto;
 * 
 * @param {element} id
 * @returns {nothing}
 */
function removeRequired(id) {
    var text = $(id).text();
    $(id).removeClass('required');
    $(id).text(text.replace('*', ''));
}

/**
 * Adiciona a classe "required" ao campo, e adiciona um "*" no final do seu texto;
 * 
 * @param {element} id
 * @returns {nothing}
 */
function addRequiredSelect2(id) {
    var newId = $(id).parent().parent().children("label");
    addRequired(newId);
}

/**
 * Remove a classe "required" ao campo, e remove o "*" do seu texto;
 * 
 * @param {element} id
 * @returns {nothing}
 */
function removeRequiredSelect2(id) {
    var newId = $(id).parent().parent().children("label");
    removeRequired(newId);
}

/**
 * Abre uma nova aba ao clicar.
 * 
 * @param {element} id
 * @returns {nothing}
 */
function registerAndOpenTab(id) {
    $(id).on('click', function (event) {
        event.preventDefault();
        window.open($(this).attr("url"));
    });
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function initDateFieldMaskAndValidation(element) {
    var date = new Date();
    $(element).mask("00/00/0000", {placeholder: "dd/mm/aaaa"});
    $(element).focusout(function () {
        var id = '#' + $(this).attr("id");
        var dateValue = stringToDate($(element).val());
    
    
        if ((!validateDate($(element).val()) || !validateYear(dateValue.year)) && ($(id).val() != '')) {
            //$(formIdentification + 'birthday').attr('value', '');
            addError(id, "Informe uma data válida no formato Dia/Mês/Ano.");
        } else {
            removeError(id);
        }
    });
    
}
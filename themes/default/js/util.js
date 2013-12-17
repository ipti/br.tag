/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function rule(string, exp) {
    return ((string.match(exp)) && (string !== ''));
}


var dateRules = new Object();
dateRules.time = /^([01][0-9]|2[0-3]):[0-5][0-9]:?([0-5][0-9])?$/;
dateRules.date = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3}|[0-9]{2})$/;

var stringRules = new Object();
stringRules.schoolName = /^[A-Z0-9°ºª\- ]{4,100}$/;
stringRules.classroomName = /^[A-Z0-9°ºª\- ?]{4,80}$/;
stringRules.personName = /^[A-Z]{1,100}$/;
stringRules.email = /^([A-Z0-9_.\-])+@[A-Z0-9_]+\.([A-Z]{2,4})$/;
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
numberRules.ddd = /^[0-9]{2}$/;
numberRules.phone = /^([9]?)+([0-9]{8})$/;
numberRules.count = /^[0-9]{1,4}$/;

function validateCount(count) {
    if (count > 0){
        return(rule(count,numberRules.count));
    }else{
        return false;
    }
}

function validateRG(rg) {
    return(rule(rg,stringRules.rg));
}


function validateDate(date) {
    return(rule(date,dateRules.date));
}

function validateTime(time){
    return(rule(time,dateRules.time));
    
}

function stringToDate(str){
    var date    = new Object();
    date.day    = str.split("/")[0];
    date.month  = str.split("/")[1];
    date.year   = str.split("/")[2];
    date.asianStr = date.year+'/'+date.month+'/'+date.day;
    return date;
}

function validateSchoolName(str){
    return (rule(str,stringRules.schoolName));
}

function validateClassroomName(str){
    return (rule(str,stringRules.classroomName));
}

function numbersNotEqual(num){
    var aux = num[0];
    for(var i=1; i<num.length;i++){
        if(num[i] == aux)
            aux = num[i];
        else
            return true;
    }
    return false;
}

function validateCEP(cep){
    return (numbersNotEqual(cep) && rule(cep, numberRules.cep));
}

function validateAddress(str, max){
    return (str.length <= max && rule(str,stringRules.schoolAddress));
}
function validateInstructorAddress(str){
    return rule(str,stringRules.instructorAddress);
}
function validateInstructorAddressNumber(str){
    return rule(str,stringRules.instructorAddressNumber);
}
function validateInstructorAddressComplement(str){
    return rule(str,stringRules.instructorAddressComplement);
}
function validateInstructorAddressNeighborhood(str){
    return rule(str,stringRules.instructorAddressNeighborhood);
}

function validateStudentAddress(str){
    return rule(str,stringRules.studentAddress);
}
function validateStudentAddressNumber(str){
    return rule(str,stringRules.studentAddressNumber);
}
function validateStudentAddressComplement(str){
    return rule(str,stringRules.studentAddressComplement);
}
function validateStudentAddressNeighborhood(str){
    return rule(str,stringRules.studentAddressNeighborhood);
}

function validateDDD(ddd){
    return rule(ddd, numberRules.ddd);
}

function validatePhone(phone, length){
    return (phone.length <= length && numbersNotEqual(phone) && rule(phone, numberRules.phone));
}

function validateEmail(email){
    return (rule(email, stringRules.email));
}


function anoMinMax(min,max,ano) {
    ano = parseInt(ano);
    if(ano >= min && ano <= max){
        return true
    }
    return false;
}

function validadeBirthdayPerson(date){
    if(validateDate(date)){
        var fullDate = new Date(date);
        var year = fullDate.getFullYear();
        return anoMinMax(1918,1999,year);
    }else{
        return false;
    }
}

function validateNamePerson(personName){
    var complete_name = personName.split(' ');  
    var passExp = true;
    for(var i=0; i<complete_name.length;i++){
        if(!rule(complete_name[i],stringRules.personName)){
            passExp=false;
            break;
        } 
    }
    var ret = new Array();
    if(passExp){      
        if(this.isset(complete_name[1])){
            var str4 = null;
            var until4 = 0; 
            for(var i=0;i<personName.length;i++){
                if(personName[i]!=str4){
                    str4 = personName[i];
                    until4=1;
                }else{
                    until4++; 
                }
                
                if(until4 > 4){
                    ret[0] = false;
                    ret[1] = "O nome não pode ter mais de 4 letras seguidas.";
                    return ret;
                }
            }
        }else{
            ret[0] = false;
            ret[1] = "Nome sem sobrenome.";
            return ret;
        }
    }else{
        ret[0] = false;
        ret[1] = "O nome somente deve ter letras.";
        return ret;
    }
    ret[0] = true;
    ret[1] = "bele";
    return ret; 
}

function validateCpf(cpf){
    if(cpf == "00000000000" || cpf == "00000000191")
        return false;
    else
        return rule(cpf, numberRules.cpf);
}

function validateNis(nis){
    return rule(nis, numberRules.nis);
}

function isset(variable){
    return (variable != 'undefined' && variable != null);
}


function errorMessage(id,message){
    removeErrorMessage(id);
    id = $(id).attr("id");
    $("#"+id).parent().append("<span id='"+id+"_error' class='error'><br/>"+message+"</span>");
}
function removeErrorMessage(id){
    $(id+'_error').remove();
}
function errorNotification(id){
    $(id).parent().children().css("border-color", "red");
    $(id).parent().children().css("color", "red");
}
function removeErrorNotification(id){
    $(id).parent().children().css("border-color", "");
    $(id).parent().children().css("color", "");
}

function addError(id, message){
    errorMessage(id, message);
    errorNotification(id);
}
function removeError(id){
    removeErrorMessage(id);
    removeErrorNotification(id);
}
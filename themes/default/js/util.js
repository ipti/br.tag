/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function rule(string, exp) {
    return ((string.match(exp)) && (string !== ''));
}


var dateRules = new Object();
dateRules.hora = /^([01][0-9]|2[0-3]):[0-5][0-9]:?([0-5][0-9])?$/;
dateRules.data = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3}|[0-9]{2})$/;

var stringRules = new Object();
stringRules.schoolName = /^[A-Z0-9°ºª\- ]{4,100}$/;
stringRules.personName = /^[A-Z]{1,100}$/;
stringRules.email = /^([A-Z0-9_.\-])+@[A-Z0-9_]+\.([A-Z]{2,4})$/;
stringRules.schoolAddress = /^[A-Za-z0-9°ºª\-\/\., ][^a-z]*$/;

var numberRules = new Object();
numberRules.cep = /^[0-9]{8}$/;
numberRules.ddd = /^[0-9]{2}$/;
numberRules.phone = /^([9]?)+([0-9]{8})$/;

function validateDate(date) {
    return(rule(date,dateRules.data));
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
                    return "O nome Não pode ter mais de 4 letras seguidas";
                }
            }
        }else{
            return "Nome sem SobreNome";
        }
    }else{
        return "O nome somente deve ter Letras";  
    }
    
     return true;   
}


function isset(variable){
    return (variable != 'undefined' && variable != null);
}




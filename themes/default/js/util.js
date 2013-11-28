/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function rule(string, exp) {
    return ((string.match(exp)) && (string !== ''));
}


var dateRules = new Object();
dateRules.hora = /^([01][0-9]|2[0-3]):[0-5][0-9]:?([0-5][0-9])?$/i;
dateRules.data = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3}|[0-9]{2})$/i;
dateRules.email = /^[A-Za-z0-9_.\-]+@([A-Za-z0-9_]+\.)+[A-Za-z]{2,4}$/i;

function validateDate(date) {
    if (rule(date,dateRules.data)){
        return true;
    }
    return false;
}

function stringTodate(str){
    var date = new Object();
    date.day = str.split("/")[0];
    date.month = str.split("/")[1];
    date.year = str.split("/")[2];
    date.asianStr = date.year+'/'+date.month+'/'+date.day;
    return date;
}

function anoMinMax(min,max,ano) {
    ano = parseInt(ano);
    if(ano >= min && ano <= max){
        return true
    }
    return false;
}

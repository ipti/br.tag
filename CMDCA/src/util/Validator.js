export default class Validation {

    static isEmpty(value){
        return value === '' || value === null;
    }

    static notEmpty(value){
        return !Validation.isEmpty(value);
    }

    static isEmail(email){
        let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(email).toLowerCase());
    }

    static isLetter(value){
        let regex = /^[a-zA-Z\s]*$/;
        return regex.test(value);
    }

    static isInteger(value){
        let regex = /^[0-9]*$/;
        return regex.test(value);
    }

    static isString(value){
        let regex = /^[a-zA-Z0-9\s]*$/;
        return regex.test(value);
    }

    static isDate(value){
        let regex = /^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/g;
        return regex.test(value);
    }

    static isTime(value){
        let regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
        return regex.test(value);
    }

    static isCPF(cpf) {
        let numbers, digits, sum, i, result, equalsDigits;
        equalsDigits = 1;

        if (cpf.length < 11){
            return false;
        }

        for (i = 0; i < cpf.length - 1; i++){
            if (cpf.charAt(i) !== cpf.charAt(i + 1)){
                equalsDigits = 0;
                break;
            }
        }

        if (!equalsDigits) {
            numbers = cpf.substring(0,9);
            digits = cpf.substring(9);
            sum = 0;

            for (i = 10; i > 1; i--){
                sum += numbers.charAt(10 - i) * i;
            }

            result = sum % 11 < 2 ? 0 : 11 - sum % 11;

            if (result !== digits.charAt(0)){
                return false;
            }

            numbers = cpf.substring(0,10);
            sum = 0;

            for (i = 11; i > 1; i--){
                sum += numbers.charAt(11 - i) * i;
            }

            result = sum % 11 < 2 ? 0 : 11 - sum % 11;
            
            if (result !== digits.charAt(1)){
                return false;
            }

            return true;
        }
        else{
            return false;
        }
    }

    static isCNPJ(cnpj) {
        var b = [6,5,4,3,2,9,8,7,6,5,4,3,2];
    
        if((cnpj = cnpj.replace(/[^\d]/g,"")).length != 14)
            return false;
    
        if(/0{14}/.test(cnpj))
            return false;
    
        for (var i = 0, n = 0; i < 12; n += cnpj[i] * b[++i]);
        if(cnpj[12] != (((n %= 11) < 2) ? 0 : 11 - n))
            return false;
    
        for (var i = 0, n = 0; i <= 12; n += cnpj[i] * b[i++]);
        if(cnpj[13] !== (((n %= 11) < 2) ? 0 : 11 - n))
            return false;
    
        return true;
    };

}
(function($){
    $.fn.validationEngineLanguage = function(){};
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {

                "required": {
                    "regex": "none",
                    "alertText": "* Este campo é obrigatório",
                    "alertTextCheckboxMultiple": "* Favor selecionar uma opção",
                    "alertTextCheckboxe": "* Este checkbox é obrigatório",
                    "alertTextDateRange": "* Ambas as datas do intervalo são obrigatórias"
                },

                "requiredInFunction": {
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Campo deve ser igual a teste"
                },

                "dateRange": {
                    "regex": "none",
                    "alertText": "* Intervalo de datas inválido"
                },

                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Intervalo de data e hora inválido"
                },

                "minSize": {
                    "regex": "none",
                    "alertText": "* Permitido o mínimo de ",
                    "alertText2": " caractere(s)"
                },

                "maxSize": {
                    "regex": "none",
                    "alertText": "* Permitido o máximo de ",
                    "alertText2": " caractere(s)"
                },

				"groupRequired": {
                    "regex": "none",
                    "alertText": "* Você deve preencher um dos seguintes campos"
                },

                "min": {
                    "regex": "none",
                    "alertText": "* Valor mínimo é "
                },

                "max": {
                    "regex": "none",
                    "alertText": "* Valor máximo é "
                },

                "past": {
                    "regex": "none",
                    "alertText": "* Data anterior a "
                },

                "future": {
                    "regex": "none",
                    "alertText": "* Data posterior a "
                },

                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Máximo de ",
                    "alertText2": " opções permitidas"
                },

                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Favor selecionar ",
                    "alertText2": " opção(ões)"
                },

                "equals": {
                    "regex": "none",
                    "alertText": "* Os campos não correspondem"
                },

                "creditCard": {
                    "regex": "none",
                    "alertText": "* Número de cartão de crédito inválido"
                },

                "phone": {
                    "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
                    "alertText": "* Número de telefone inválido"
                },

                "email": {
                    "regex": /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,
                    "alertText": "* Endereço de email inválido"
                },

                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* Número inteiro inválido"
                },

                "number": {
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* Número decimal inválido"
                },

                "date": {
                    //  Check if date is valid by leap year
                    "func": function (field) {
                       var pattern = new RegExp(/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01])$/);
                       var match = pattern.exec(field.val());
                       if (match == null)
                           return false;

                       var year = match[1];
                       var month = match[2]*1;
                       var day = match[3]*1;
                       var date = new Date(year, month - 1, day); // because months starts from 0.

                       return (date.getFullYear() == year && date.getMonth() == (month - 1) && date.getDate() == day);
                    },
                    "alertText": "* Data inválida, deve ser no formato AAAA-MM-DD"
                },

                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Endereço IP inválido"
                },

                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* URL inválida"
                },

                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Apenas números"
                },

                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \']+$/,
                    "alertText": "* Apenas letras"
                },

				"onlyLetterAccentSp":{
                    "regex": /^[a-z\u00C0-\u017F\ ]+$/i,
                    "alertText": "* Apenas letras e espaços."
                },

                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* Não são permitidos caracteres especiais"
                },

                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "real": {
                	// Brazilian (Real - R$) money format
                	"regex": /^([1-9]{1}[\d]{0,2}(\.[\d]{3})*(\,[\d]{0,2})?|[1-9]{1}[\d]{0,}(\,[\d]{0,2})?|0(\,[\d]{0,2})?|(\,[\d]{1,2})?)$/,
                    "alertText": "* Número decimal inválido"
                },

                "cpf": {
                    // CPF is the Brazilian ID
                    "func": function(field, rules, i, options){
                        cpf = field.val().replace(/[^0-9]+/g, '');
                        while(cpf.length < 11) cpf = "0"+ cpf;

                        var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
                        var a = cpf.split('');
                        var b = new Number;
                        var c = 11;
                        b += (a[9] * --c);
                        if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
                        b = 0;
                        c = 11;
                        for (y=0; y<10; y++) b += (a[y] * c--);
                        if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }

                        var error = false;
                        if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) error = true;
                        return !error;
                    },
                    "alertText": "* CPF inválido",
                    "alertTextOK": "* CPF válido"
                },

                "ajaxUserCall": {
                    "url": "ajaxValidateFieldUser",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    "alertText": "* This user is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },

                "ajaxUserCallPhp": {
                    "url": "phpajax/ajaxValidateFieldUser.php",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This username is available",
                    "alertText": "* This user is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },

                "ajaxNameCall": {
                    // remote json service location
                    "url": "ajaxValidateFieldName",
                    // error
                    "alertText": "* This name is already taken",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This name is available",
                    // speaks by itself
                    "alertTextLoad": "* Validating, please wait"
                },

                "ajaxNameCallPhp": {
                    // remote json service location
                    "url": "phpajax/ajaxValidateFieldName.php",
                    // error
                    "alertText": "* This name is already taken",
                    // speaks by itself
                    "alertTextLoad": "* Validating, please wait"
                },

                "validate2fields": {
                    "alertText": "* Please input HELLO"
                },

                //tls warning:homegrown not fielded
                "dateFormat":{
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Data inválida"
                },

                //tls warning:homegrown not fielded
                "dateTimeFormat": {
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1}$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^((1[012]|0?[1-9]){1}\/(0?[1-9]|[12][0-9]|3[01]){1}\/\d{2,4}\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1})$/,
                    "alertText": "* Formato de data inválido",
                    "alertText2": "Formato esperado: ",
                    "alertText3": "mm/dd/yyyy hh:mm:ss AM|PM or ",
                    "alertText4": "yyyy-mm-dd hh:mm:ss AM|PM"
                },

                "className": {
                    "regex": /^[a-zA-Z_]+[a-zA-Z0-9_]*$/,
                    "alertText": "* Não é um nome de classe válido"
                },
            };
        }
    };

    $.validationEngineLanguage.newLang();

})(jQuery);

// ===========================
// Custom validation for email
// ===========================
var validate_email = function(field, rules, i, options) {

    var exist = false;

    $.ajax({

        url: $('#URL_ROOT').val() + '/app-user/check-email/',
        context: document.body,
        cache: false,
        async: false,
        data: { 'email' : field.val() },
        type: 'POST',
        success: function(data){

            json = $.parseJSON(data);

            if(json.return == true)
                exist = true;
        }
    });

    if( exist )
        return "* Endereço de email já utilizado";
}
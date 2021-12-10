
$('#classroom').change(function () {
    $.ajax({
        type: 'POST',
        url: getGradesUrl,
        cache: false,
        data: {classroom: $("#classroom").val()},
        beforeSend:function(){
            $(".classroom").hide();
        },
        success: function (data) {
            data = jQuery.parseJSON(data);


            generateGradesForm(data);
            $('select.grade-dropdown').select2({
                allowClear: true,
                placeholder: "Selecione..."
            });
            $(".classroom").show();
        },
    });
});

$("#save").on("click", function () {
    $("#classes-form").submit();
});

$(document).on("keydown", function (e) {
//   var up = 38;
//   var down = 40;
//   if(e.keyCode === up){
//       $("li.active").prev().children("a[data-toggle]").click();
//   } else if(e.keyCode === down){
//       $("li.active").next().children("a[data-toggle]").click();
//   }
    if (e.keyCode === 13) {
        e.preventDefault();
        $("li.active").next().children("a[data-toggle]").click();
    }
});

$(document).on("keyup", "input.grade", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^(10|\d)(?:(\.|\,)\d{0,2}){0,1}$/;
        if (val.match(grade) === null) {
            val = "";
        } else {
            if (val > 10)
                val = 10;
        }
    }
    this.value = val;
});

$(document).on("keyup", "input.frequency-fields", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^[0-9]+$/;
        if (val.match(grade) === null) {
            val = "";
        }
        if (val > 366){

            val = 366;
        }
    }
    this.value = val;
});



$(document).on("keyup", "*[class^='dias-letivos']", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^[0-9]+$/;
        if (val.match(grade) === null) {
            val = "";
        }
        if (val > 366){

            val = 366;
        }
    }
    this.value = val;
});


$(document).on("keyup", "*[class^='school-days-group']", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^[0-9]+$/;
        if (val.match(grade) === null) {
            val = "";
        }
        if (val > 366){

            val = 366;
        }
    }
    this.value = val;
});

$(document).on("keyup", "*[class^='carga-horaria']", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^[0-9]+$/;
        if (val.match(grade) === null) {
            val = "";
        }
        if (val > 8784){

            val = 8784;
        }
    }
    this.value = val;
});


$(document).on("keyup", "input.frequency-percentage", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/;
        if (val.match(grade) === null) {
            val = "";
        } else {
            if (val > 100)
                val = 100;
        }
    }
    this.value = val;
});

$(document).on("click", ".no-disciplines-link", function () {
    $(".alert-no-disciplines").toggle();
});
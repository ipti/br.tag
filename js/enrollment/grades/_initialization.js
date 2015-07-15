$('#classroom').change(function () {
    $.ajax({
        type: 'POST',
        url: getGradesUrl,
        cache: false,
        data: {classroom: $("#classroom").val()},
        success: function (data) {
            data = jQuery.parseJSON(data);
            generateGradesForm(data);
        }
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

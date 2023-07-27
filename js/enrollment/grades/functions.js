
var lastValidValue = "";

function initializeGradesMask() {
    $(document).on("focus", "input.grade", function (e) {
        lastValidValue = this.value
    });
    
    $("input.grade").on("input", function (e) {
        e.preventDefault();
        var val = this.value;
        if (!$.isNumeric(val)) {
            val = val === "" ? "" : lastValidValue;
        } else {
            grade = /^(10|\d)(?:(\.|\,)\d{0,1}){0,1}$/;
            if (val.match(grade) === null) {
                val = lastValidValue;
            }
        }
        lastValidValue = val;
        this.value = val;
    });
}
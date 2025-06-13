$(document).ready(function () {
    $.ajax({
        type:'POST',
        url:`?r=enrollmentonline/enrollmentonlinestudentidentification/loadSolicitations`,
        sucess: function(response) {
            console.log(response);
        }
    })
})

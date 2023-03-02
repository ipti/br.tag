const action = window.location.search;
$(document).ready(function () {
    if ($(".js-tag-table").length) {
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            let indexActionButtons;
            if(action.includes("school") 
            || action.includes("activeDisableUser")) {
                indexActionButtons = 2;
            }
            if(action.includes("classroom") 
            || action.includes("instructor")
            || action.includes("manageUsers")) {
                indexActionButtons = 3;
            }
            if(action.includes("student") 
            || action.includes("curricularmatrix") 
            || action.includes("courseplan")) {
                indexActionButtons = 4;
            }
            $(".js-tag-table").dataTable({
                language: getLanguagePtbr(),
                responsive: true,
                columnDefs: [{
                    orderable: false, targets: [indexActionButtons] 
                }],
            });

        });
    }
});
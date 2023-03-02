const action = window.location.search;
$(document).ready(function () {
    if ($(".js-tag-table").length) {
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            if(action.includes("school") 
            || action.includes("activeDisableUser")) {
                $(".js-tag-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    columnDefs: [{
                        orderable: false, targets: [2] 
                    }],
                });
            }
            if(action.includes("classroom") 
            || action.includes("instructor")
            || action.includes("manageUsers")) {
                $(".js-tag-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    columnDefs: [{
                        orderable: false, targets: [3] 
                    }],
                });
            }
            if(action.includes("student") 
            || action.includes("curricularmatrix") 
            || action.includes("courseplan")) {
                $(".js-tag-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    columnDefs: [{
                        orderable: false, targets: [4] 
                    }],
                });
            }         


        });
    }
});
const action = window.location.search;
console.log(action)
$(document).ready(function () {
    if ($(".js-tag-table").length) {
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            if(action.includes("school")) {
                console.log("2")
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
                console.log("3")
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
                console.log("4")
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
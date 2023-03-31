const action = window.location.search;
$(document).ready(function () {
    if($(".js-tag-table").has(".empty").length > 0){
        return;
    }
    if ($(".js-tag-table").length) {
        const isMobile = window.innerWidth <= 768;
        const numColumns = $(".js-tag-table th").length;
        const columnsIndex = new Array(numColumns-1).fill(1).map( (_, i) => i+1);

        
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
                select: {
                    items: 'cell'
                },
                "bLengthChange": true,
                columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: [indexActionButtons] }],
            });

        });
    }
});
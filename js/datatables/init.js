const action = window.location.search;
console.log(`${window.location}?r=student/getstudentajax`);
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
            || action.includes("courseplan")
            || action.includes("professional")) {
                indexActionButtons = 4;
            }
            if(action.includes("student")) {
                $(document).ready(function () {
                    $(".js-tag-table").DataTable({
                        language: getLanguagePtbr(),
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: `${window.location}/getstudentajax`,
                            type: "POST",
                            data: function (d) {
                                d.page = parseInt(d.start / d.length) + 1;
                                d.perPage = d.length;
                                d.search = { value: $('input[type="search"]').val() };
                                return d;
                            }
                        },
                        searching: true,
                    });
                });
            }else {
                $(".js-tag-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    select: {
                        items: 'cell'
                    },
                    "bLengthChange": false,
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: [indexActionButtons] }],
                });
            }
        });
    }
});
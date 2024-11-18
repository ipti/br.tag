$(document).ready(function(){
    $(".js-toggle-drawer, .menu-cover").click(function(){
        $(".js-drawer").toggleClass("t-drawer--mobile-hidden");
        $(".menu-cover").toggleClass("sidebar-cover")
    });


    $("#menu-electronic-diary-trigger").click(function(){
        $("#menu-electronic-diary").toggleClass("active");
    });

    $("#menu-integrations-trigger").click(function(){
        $("#menu-integrations").toggleClass("active");
    });

    if($('#content :input:not(button)').length > 0) {
        $('#content :input:not(button)').each(function () {
            // Armazena o valor inicial de cada input
            $(this).data('oldValue', $(this).val());
        });

        $('a:not(#content a), button:not(#content button)').on('click', function (event) {
            event.preventDefault();

            let action = $(this)
            $('#content :input:not(button)').each(function (event) {

                if($(this).data('oldValue') != $(this).val()){
                    console.log($(this).data('oldValue'))
                    console.log($(this).val())
                    $("#tag-modal").modal('show');
                    $("#tag-modal .tag-continue-modal").on("click", function (event) {
                        if (action.is('a')) {
                            const href = action.attr('href');
                            if (href) {
                                window.location.href = href;
                            }
                        } else {
                            action.trigger('click');
                        }
                    })
                }
            });


        });
        $('#content :input:not(button)').on('input change', function () {

        });
    }
});

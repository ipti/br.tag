////////////////////////////////////////////////
// Tabs and Pagination                        //
////////////////////////////////////////////////
/*
$('.tab-classroom li a').click(function(){
    let classActive = $('li[class="active"]');
    let divActive = $('div .active');
    let li1 = 'tab-classroom';
    let li2 = 'tab-instructors';
    let li3 = 'tab-students';
    let tab = '';
    switch($(this).parent().attr('id')) {
        case li1 : tab = li1;
            $('.prev').hide();
            $('.next').show();
            $('.last').hide(); break;
        case li2 : tab = li2;
            $('.prev').show();
            $('.next').hide();
            $('.last').show(); break;
        case li3 : tab = li3;
            $('.prev').show();
            $('.next').hide();
            $('.last').hide(); break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#'+tab).addClass("active");
    $('#'+next_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});
$('.next').click(function(){
    let classActive = $('ul.tab-classroom li[class="active"]');
    let divActive = $('div .active');
    let li1 = 'tab-classroom';
    let li2 = 'tab-instructors';
    let next = '';
    switch(classActive.attr('id')) {
        case li1 : next = li2;
            $('.prev').show();
            $('.next').hide();
            $('.last').show(); break;
        case li2 : next = li2; break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = next.substring(4);
    next_content = next_content.toString();
    $('#'+next).addClass("active");
    $('#'+next_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});
$('.prev').click(function(){
    let classActive = $('li[class="active"]');
    let divActive = $('div .active');
    let li1 = 'tab-classroom';
    let li2 = 'tab-instructors';
    let li3 = 'tab-students';
    let previous = '';
    switch(classActive.attr('id')) {
        case li1 : previous = li1;  break;
        case li2 : previous = li1;
            $('.prev').hide();
            $('.last').hide();
            $('.next').show(); break;
        case li3 : previous = li2;
            $('.prev').show();
            $('.next').hide();
            $('.last').show(); break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let previous_content = previous.substring(4);
    previous = previous.toString();
    $('#'+previous).addClass("active");
    $('#'+previous_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});
$('.heading-buttons').css('width', $('#content').width());
    */

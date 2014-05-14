
$('.tab-student li a').click(function() {
    var classActive = $('li[class="active"]');
    var divActive = $('div .active');
    var li1 = 'tab-student-identify';
    var li2 = 'tab-student-documents';
    var li3 = 'tab-student-address';
    var tab = '';
    switch ($(this).parent().attr('id')) {
        case li1 :
            tab = li1;
            $('.prev').hide();
            $('.next').show();
            $('.last').hide();
            break;
        case li2 :
            tab = li2;
            $('.prev').show();
            $('.next').show();
            $('.last').hide();
            break;
        case li3 :
            tab = li3;
            $('.prev').show();
            $('.next').hide();
            $('.last').show();
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    var next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + tab).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({scrollTop: 0}, 'fast');
})

$('.next').click(function() {
    var classActive = $('li[class="active"]');
    var divActive = $('div .active');
    var li1 = 'tab-student-identify';
    var li2 = 'tab-student-documents';
    var li3 = 'tab-student-address';
    var next = '';
    switch (classActive.attr('id')) {
        case li1 :
            next = li2;
            $('.prev').show();
            break;
        case li2 :
            next = li3;
            $('.next').hide();
            $('.last').show();
            break;
        case li3 :
            next = li3;
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    var next_content = next.substring(4);
    next_content = next_content.toString();
    $('#' + next).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({scrollTop: 0}, 'fast');
});

$('.prev').click(function() {
    var classActive = $('li[class="active"]');
    var divActive = $('div .active');
    var li1 = 'tab-student-identify';
    var li2 = 'tab-student-documents';
    var li3 = 'tab-student-address';
    var previous = '';
    switch (classActive.attr('id')) {
        case li1 :
            previous = li1;
            break;
        case li2 :
            previous = li1;
            $('.prev').hide();
            break;
        case li3 :
            previous = li2;
            $('.last').hide();
            $('.next').show();
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    var previous_content = previous.substring(4);
    previous = previous.toString();
    $('#' + previous).addClass("active");
    $('#' + previous_content).addClass("active");
    $('html, body').animate({scrollTop: 0}, 'fast');
});

    
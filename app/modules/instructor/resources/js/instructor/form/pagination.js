
$('.tab-instructor li a').click(function() {
    var classActive = $('ul.tab-instructor li.active');
    var divActive = $('div.active');
    var li1 = 'tab-instructor-identify';
    var li2 = 'tab-instructor-address';
    var li3 = 'tab-instructor-data';
    var tab = '';
    switch ($(this).parent().attr('id')) {
        case li1 :
            tab = li1;
            $('.prev').hide();
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li2 :
            tab = li2;
            $('.prev').show();
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li3 :
            tab = li3;
            $('.prev').show();
            $('.next').hide();
            $('.last').show();
            $(formInstructorvariableData + 'scholarity').trigger('change');
            break;
    }
    classActive.removeClass("active");
    divActive.removeClass("active");
    var next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + tab).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({scrollTop: 0}, 'fast');
});

$('.next').click(function() {
    var classActive = $('ul.tab-instructor li.active');
    var divActive = $('div.active');
    var li1 = 'tab-instructor-identify';
    var li2 = 'tab-instructor-address';
    var li3 = 'tab-instructor-data';
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
            $('#instructorVariableData').hide();
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
    var classActive = $('ul.tab-instructor li.active');
    var divActive = $('div.active');
    var li1 = 'tab-instructor-identify';
    var li2 = 'tab-instructor-address';
    var li3 = 'tab-instructor-data';
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
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
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


$('.tab-instructordata li a').click(function() {
    var classActive = $('li[class="sub-active"]');
    var divActive = $('div.sub-active');
    var li1 = 'tab-instructor-data1';
    var li2 = 'tab-instructor-data2';
    var li3 = 'tab-instructor-data3';
    var tab = '';
    switch ($(this).parent().attr('id')) {
        case li1 :
            tab = li1;
            $('#instructor-data1').show();
            $('#instructor-data2').hide();
            $('#instructor-data3').hide();
            actualFilter = filter[1];
            break;
        case li2 :
            tab = li2;
            $('#instructor-data1').hide();
            $('#instructor-data2').show();
            $('#instructor-data3').hide();
            actualFilter = filter[2];
            break;
        case li3 :
            tab = li3;
            $('#instructor-data1').hide();
            $('#instructor-data2').hide();
            $('#instructor-data3').show();
            actualFilter = filter[3];
            break;
    }
    classActive.removeClass("sub-active");
    divActive.removeClass("sub-active")
    var next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + next_content).addClass("sub-active");
    $('#' + tab).addClass("sub-active");
});
    
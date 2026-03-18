
$('.tab-instructor li a').click(function () {
    let classActive = $('ul.tab-instructor li.active');
    let divActive = $('div.active');
    let li1 = 'tab-instructor-identify';
    let li2 = 'tab-instructor-address';
    let li3 = 'tab-instructor-data';
    let tab = '';
    switch ($(this).parent().attr('id')) {
        case li1:
            tab = li1;
            $('.prev').hide();
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li2:
            tab = li2;
            $('.prev').show();
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li3:
            tab = li3;
            $('.prev').show();
            $('.next').hide();
            $('.last').show();
            $(formInstructorvariableData + 'scholarity').trigger('change');
            break;
    }
    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + tab).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});

$('.next').click(function () {
    let classActive = $('ul.tab-instructor li.active');
    let divActive = $('div.active');
    let li1 = 'tab-instructor-identify';
    let li2 = 'tab-instructor-address';
    let li3 = 'tab-instructor-data';
    let next = '';
    switch (classActive.attr('id')) {
        case li1:
            next = li2;
            $('.prev').show();
            break;
        case li2:
            next = li3;
            $('.next').hide();
            $('.last').show();
            $('#instructorVariableData').hide();
            break;
        case li3:
            next = li3;
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = next.substring(4);
    next_content = next_content.toString();
    $('#' + next).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});

$('.prev').click(function () {
    let classActive = $('ul.tab-instructor li.active');
    let divActive = $('div.active');
    let li1 = 'tab-instructor-identify';
    let li2 = 'tab-instructor-address';
    let li3 = 'tab-instructor-data';
    let previous = '';
    switch (classActive.attr('id')) {
        case li1:
            previous = li1;
            break;
        case li2:
            previous = li1;
            $('.prev').hide();
            break;
        case li3:
            previous = li2;
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            $('.next').show();
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let previous_content = previous.substring(4);
    previous = previous.toString();
    $('#' + previous).addClass("active");
    $('#' + previous_content).addClass("active");
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});


$('.tab-instructordata li a').click(function () {
    let classActive = $('li[class="sub-active"]');
    let divActive = $('div.sub-active');
    let li1 = 'tab-instructor-data1';
    let li2 = 'tab-instructor-data2';
    let li3 = 'tab-instructor-data3';
    let tab = '';
    switch ($(this).parent().attr('id')) {
        case li1:
            tab = li1;
            $('#instructor-data1').show();
            $('#instructor-data2').hide();
            $('#instructor-data3').hide();
            actualFilter = filter[1];
            break;
        case li2:
            tab = li2;
            $('#instructor-data1').hide();
            $('#instructor-data2').show();
            $('#instructor-data3').hide();
            actualFilter = filter[2];
            break;
        case li3:
            tab = li3;
            $('#instructor-data1').hide();
            $('#instructor-data2').hide();
            $('#instructor-data3').show();
            actualFilter = filter[3];
            break;
    }
    classActive.removeClass("sub-active");
    divActive.removeClass("sub-active")
    let next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + next_content).addClass("sub-active");
    $('#' + tab).addClass("sub-active");
});

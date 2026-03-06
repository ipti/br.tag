/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$('.js-tab-school li a').click(function () {
    let classActive = $('li[class="active"]');
    let divActive = $('div .active');
    let li1 = 'tab-school-indentify';
    let li2 = 'tab-school-addressContact';
    let li3 = 'tab-school-structure';
    let li4 = 'tab-school-equipment';
    let li5 = 'tab-school-manager';
    let li6 = 'tab-school-education';
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
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li4:
            tab = li4;
            $('.prev').show();
            $('.next').show();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li5:
            tab = li5;
            $('.prev').show();
            $('.next').show();
            $('.last').hide();
            window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
            break;
        case li6:
            tab = li6;
            $('.prev').show();
            $('.next').hide();
            $('.last').show();
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = tab.substring(4);
    next_content = next_content.toString();
    $('#' + tab).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({
        scrollTop: 0
    }, 'fast');
})

$('.next').click(function () {
    let classActive = $('ul.js-tab-school').find('li.active');
    let divActive = $('div .active');
    let li1 = 'tab-school-indentify';
    let li2 = 'tab-school-addressContact';
    let li3 = 'tab-school-structure';
    let li4 = 'tab-school-equipment';
    let li5 = 'tab-school-manager';
    let li6 = 'tab-school-education';

    let next = '';

    switch (classActive.attr('id')) {
        case li1:
            next = li2;
            $('.prev').show();
            break;
        case li2:
            next = li3;
            break;
        case li3:
            next = li4;
            $('.next').show();
            $('.last').hide();
            break;
        case li4:
            next = li5;
            break;
        case li5:
            next = li6;
            $('.next').hide();
            $('.last').show();
            break;
        case li6:
            next = li7;
            break;
        case li7:
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let next_content = next.substring(4);
    next_content = next_content.toString();
    $('#' + next).addClass("active");
    $('#' + next_content).addClass("active");
    $('html, body').animate({
        scrollTop: 0
    }, 'fast');
});

$('.prev').click(function () {
    let classActive = $('ul.js-tab-school').find('li.active');
    let divActive = $('div .active');
    let li1 = 'tab-school-indentify';
    let li2 = 'tab-school-addressContact';
    let li3 = 'tab-school-structure';
    let li4 = 'tab-school-equipment';
    let li5 = 'tab-school-manager';
    let li6 = 'tab-school-education';

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
            break;
        case li4:
            previous = li3;
            break;
        case li5:
            previous = li4;
            break;
        case li6:
            previous = li5;
            $('.last').hide();
            $('.next').show();
            break;
    }

    classActive.removeClass("active");
    divActive.removeClass("active");
    let previous_content = previous.substring(4);
    previous = previous.toString();
    $('#' + previous).addClass("active");
    $('#' + previous_content).addClass("active");
    $('html, body').animate({
        scrollTop: 0
    }, 'fast');
});

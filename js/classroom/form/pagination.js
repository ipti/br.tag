$(function () {
    changeTab(0);
});
    let tabs = ["tab-classroom", "tab-instructors", "tab-students", "tab-daily"];
    changeTabNumber();

function changeTabNumber(){
    $('.js-change-number-2').html("2");
    $('.js-change-number-3').html("3");
    $('.js-change-number-4').html("4");
    $('.js-change-number-5').html("5");
}
function changeTab(index){
	let activeTab = $('.js-tab-control li.active');
	let activePane = $('div .active');
	let size = tabs.length -1;

    if(index == 0){
        $('.prev').hide();
        $('.next').show();
        window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
    }else if(index == size){
        $('.prev').show();
    	$('.next').hide();
    	$('.last').show();
    }else{
        $('.prev').show();
        $('.next').show();
        window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
    }

	newTab = tabs[index];

    activeTab.removeClass("active");
    activePane.removeClass("active");

    let newPane = newTab.substring(4).toString();
    $('#' + newTab).addClass("active");
    $('#' + newPane).addClass("active");

    $('html, body').animate({scrollTop: 0}, 'fast');
}

function change2nextTab(){
	let activeTab = $('.js-tab-control li.active');
    let tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab+1);
}

function change2prevTab(){
	let activeTab = $('.js-tab-control li.active');
    let tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab-1);
}

function change2clickedTab(clicked){
	let clickedTab = clicked.attr('id');
    let tab = tabs.indexOf(clickedTab);
	changeTab(tab);
}

$('.js-tab-control li a').click(function() {
    let clickedTab = $(this).parent();
    change2clickedTab(clickedTab);
});

$('.next').click(function() {
	change2nextTab();
});

$('.prev').click(function() {
	change2prevTab();
});
$(function () {
    changeTabNumber(0);
});

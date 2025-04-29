var tabs = ['tab-student-identify','tab-student-affiliation', 'tab-student-address', 'tab-student-enrollment'];

function changeTabNumber(){
    $('.js-change-number-3').html("3");
}
function changeTab(index){
	var activeTab = $('.js-tab-control li.active');
	var activePane = $('div .active');
	var size = tabs.length -1;

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

    newPane = newTab.substring(4).toString();

    $('#' + newTab).addClass("active");
    $('#' + newPane).addClass("active");

    $('html, body').animate({scrollTop: 0}, 'fast');
}

function change2nextTab(){
	var activeTab = $('.js-tab-control li.active');
    var tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab+1);
}

function change2prevTab(){
	var activeTab = $('.js-tab-control li.active');
    var tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab-1);
}

function change2clickedTab(clicked){
	var clickedTab = clicked.attr('id');
    var tab = tabs.indexOf(clickedTab);
	changeTab(tab);
}

$('.js-tab-control li a').click(function() {
    var clickedTab = $(this).parent();
    change2clickedTab(clickedTab);
});

$('.next').click(function() {
	change2nextTab();
});

$('.prev').click(function() {
	change2prevTab();
});

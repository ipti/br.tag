let tabs = ['tab-classroom', 'tab-instructors', 'tab-students', 'tab-daily'];
function changeTab(index){
    // if(index >= 0){


	var activeTab = $('.js-tab-control li.active');
	var activePane = $('div .active');

	var size = tabs.length -1;
    if(index == 0){
        $('.prev').hide();
        $('.next').show();
        window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
        debugger;
    }else if(index == size){
        $('.prev').show();
    	$('.next').hide();
    	$('.last').show();
        debugger;
    }else{
        $('.prev').show();
        $('.next').show();
        window.location.search.includes("update") ? $('.last').show() : $('.last').hide();
        debugger;
    }
    // if (index == 0) {
    //     $('.prev').hide();
    // }

	newTab = tabs[index];

    activeTab.removeClass("active");
    activePane.removeClass("active");

    newPane = newTab.substring(4).toString();

    $('#' + newTab).addClass("active");
    $('#' + newPane).addClass("active");

    $('html, body').animate({scrollTop: 0}, 'fast');
// } else{
//     newTab = tabs[1];
//     $('.prev').hide();
// }
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
    console.log("passei aqui");
	var clickedTab = clicked.attr('id');
    var tab = tabs.indexOf(clickedTab);
	changeTab(tab);
}

$('.tab-courseplan li a').click(function() {
    var clickedTab = $(this).parent();
    change2clickedTab(clickedTab);
    debugger;
    // var tab = tabs.indexOf(clickedTab.attr("id"));
    // if (tab == 0) {
    //     $('.prev').hide();
    // }
    // console.log;
});

$('.next').click(function() {
	change2nextTab();
});

$('.prev').click(function() {
	change2prevTab();
});

var simple = getUrlVars()['simple'];
if (simple == '1') {
    var tabs = ['tab-student-identify', 'tab-student-deficiency', 'tab-student-address', 'tab-student-enrollment'];
}else{
    var tabs = ['tab-student-identify', 'tab-student-deficiency', 'tab-student-documents', 'tab-student-address', 'tab-student-enrollment'];
}
function changeTab(index){
	var activeTab = $('li[class="active"]');
	var activePane = $('div .active');
	
	var size = tabs.length -1;
    
    if(index == 0){
        $('.prev').hide();
        $('.next').show();
        $('.last').hide();
    }else if(index == size){
        $('.prev').show();
    	$('.next').hide();
    	$('.last').show();
    }else{
        $('.prev').show();
        $('.next').show();
        $('.last').hide();
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
	var activeTab = $('ul.tab-student li[class="active"]');
    var tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab+1);
}

function change2prevTab(){
	var activeTab = $('li[class="active"]');
    var tab = tabs.indexOf(activeTab.attr("id"));
	changeTab(tab-1);
}

function change2clickedTab(clicked){
	var clickedTab = clicked.attr('id');
    var tab = tabs.indexOf(clickedTab);
	changeTab(tab);
}

$('.tab-student li a').click(function() {
    var clickedTab = $(this).parent();
    change2clickedTab(clickedTab);
});

$('.next').click(function() {
	change2nextTab();
});

$('.prev').click(function() {
	change2prevTab();
});

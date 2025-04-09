$(document).ready(function(){
    const userIsInstructor = $(".isInstructor");
    if (userIsInstructor.length > 0){
        const reportContainers = $('.container-box').children();
        reportContainers.addClass('hide');
        const eletronicDiary = $('.isVisibleForInstructor');
        eletronicDiary.removeClass('hide');
    }
})

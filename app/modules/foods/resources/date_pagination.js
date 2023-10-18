$(document).on("click", '.js-change-paginatiobn', function(){
   $('.js-change-paginatiobn.active').each(function() {
    $(this).removeClass('active');
  });
    $(this).addClass("active");
})
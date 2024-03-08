let data = [];
let table = $('table').DataTable( {
    data: data,
    ordering:  true,
    searching: false,
    paginate: true,
    language: getLanguagePtbr()
  });
  
$(".js-add-notice-item").on("click", function () {
    let selectFood = $("select.js-taco-foods")
    let yearAmount = $(".js-notice-year-amount").val()
    let itemmMeasurement = $("select.js-item-measurement").val()
    let itemDescription = $(".js-item-description").val()
    if (selectFood != "" && yearAmount != "" && itemmMeasurement != "") {
       let name = selectFood.find('option:selected').text().replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');

        data.push([
            name,
            yearAmount,
            itemmMeasurement,
            itemDescription,
            selectFood.val()
        ])
        table.destroy();
        table = $('table').DataTable( {
            data: data,
            ordering:  true,
            searching: false,
            paginate: true,
            language: getLanguagePtbr()
          });
    }
})
$(".js-submit").on("click", function () {
    let notice  = {
        name: $(".js-notice-name").val(),
        date: $(".js-date").val(),
        noticeItems: data
    }
    console.log(notice)
    $.ajax({
        url: "?r=foods/foodnotice/create",
        type: "POST",
        data: {
            notice: notice
        }
      }).success(function (response) {

      })
})
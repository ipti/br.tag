let items = [];
$(".js-add-notice-item").on("click", function () {
    let name = $(".js-notice-item-name").val()
    let yearAmount = $(".js-notice-year-amount").val()
    let itemmMeasurement = $("select.js-item-measurement").val()
    let itemDescription = $(".js-item-description").val()
    if (name != "" && yearAmount != "" && itemmMeasurement != "") {

        items.push({
            name: name,
            year_amount: yearAmount,
            measurement: itemmMeasurement,
            description: itemDescription,
        })
    }
})

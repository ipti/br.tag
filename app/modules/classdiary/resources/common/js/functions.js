$(".js-select-disciplines").on("change", function() {
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/getclassrooms`,
        type: "POST",
        data: {
            discipline: this.value
        }
    }).success(function (response) {
        // console.log(response)
        const result = JSON.parse(response);
        const classrooms = $(".js-add-classrooms-cards");
        
        const cardsClassrooms = result.reduce((acc, element) => 
            acc += `
            <div class="column no-grow">
                <a href="#" class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary">${element["discipline_name"]}</div>
                        <div class="t-cards-title">${element["name"]}</div>
                        <div class="t-cards-text clear-margin--left">${element["stage_name"]}</div>
                    </div>
                </a>
            </div>`, "");

        classrooms.html(cardsClassrooms);
    }) 
});

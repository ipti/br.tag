 function renderClasroomsCards(discipline){
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/getclassrooms`,
        type: "POST",
        data: {
            discipline: discipline 
        }
    }).success(function (response) {
        const result = JSON.parse(response);
        const classrooms = $(".js-add-classrooms-cards");
            console.log(result["minorSchoolingClassroom"]);
        /* var cardsClassrooms = result["classrooms"].reduce((acc, element) => 
            acc += `
            <div class="column clearfix no-grow">
                <a href="${window.location.host}?r=classdiary/default/ClassDiary&classroom_fk=${element["id"]}&stage_fk=${element["stage_fk"]}&discipline_fk=${element["edcenso_discipline_fk"]}&discipline_name=${element["discipline_name"]}" class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary">${element["discipline_name"]}</div>
                        <div class="t-cards-title">${element["name"]}</div>
                        <div class="t-cards-text clear-margin--left">${element["stage_name"]}</div>
                    </div>
                </a>
            </div>`, "");
        cardsClassrooms += result["minorSchoolingClassroom"].reduce((acc, element) => 
        acc += `
        <div class="column clearfix no-grow">
            <a class="t-cards">
                <div class="t-cards-content">
                    <div class="t-tag-primary">aaaaa</div>
                    <div class="t-cards-title">${element["turma"]["name"]}</div>
                    <div class="t-cards-text clear-margin--left">${element["turma"]["stage_name"]}</div>
                </div>
            </a>
        </div>`, ""); */
        classrooms.html(cardsClassrooms);
    }) 
 }

$(".js-select-disciplines").on("change", function() {
    renderClasroomsCards(this.value);
});
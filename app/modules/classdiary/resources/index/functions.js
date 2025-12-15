function renderClasroomsCards(discipline) {
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/getclassrooms`,
        type: "POST",
        data: {
            discipline: discipline,
        },
    }).success(function (response) {
        const result = JSON.parse(response);

        const classrooms = $(".js-add-classrooms-cards");
        var cardsClassrooms = result["classrooms"].reduce(
            (acc, element) =>
                (acc += `
            <div class="column clearfix no-grow">
                <a href="${window.location.host}?r=classdiary/default/classDays&classroomFk=${element["id"]}&stageFk=${element["stage_fk"]}&disciplineFk=${element["edcenso_discipline_fk"]}&disciplineName=${element["discipline_name"]}&classroomName=${element["name"]}" class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary">${element["discipline_name"]}</div>
                        <div class="t-cards-title">${element["name"]}</div>
                        <div class="t-cards-text clear-margin--left">${element["stage_name"]}</div>
                    </div>
                </a>
            </div>`),
            ""
        );
        cardsClassrooms += result["minorSchoolingClassroom"].reduce(
            (acc, element) =>
                (acc += `
        <div class="column clearfix no-grow">
            <a href="${window.location.host}?r=classdiary/default/classDays&classroomFk=${element["id"]}&stageFk=${element["stage_fk"]}&disciplineFk=''&disciplineName=&classroomName=${element["name"]}" class="t-cards">
                <div class="t-cards-content">
                    <div class="t-cards-title">${element["name"]}</div>
                    <div class="t-cards-text clear-margin--left">${element["stage_name"]}</div>
                </div>
            </a>
        </div>`),
            ""
        );
        classrooms.append(cardsClassrooms);
    });
}

$(".js-select-disciplines").on("change", function () {
    renderClasroomsCards(this.value);
});

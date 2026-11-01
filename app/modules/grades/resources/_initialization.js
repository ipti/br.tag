let url = new URL(window.location.href);
let conceptId = url.searchParams.get('id');

$(document).on("click", "#saveConcept", function () {
    let conceptName = $('#conceptName').val();
    let conceptAcronym = $('#conceptAcronym').val();

    if(!conceptId) {
        $.ajax({
            type: 'POST',
            url: "?r=grades/concept/create",
            cache: false,
            data: {
                name: conceptName,
                acronym: conceptAcronym
            }
        }).success(function(response) {
            window.location.href = "?r=grades/concept";
        })
    } else {
        $.ajax({
            type: 'POST',
            url: `?r=grades/concept/update&id=${conceptId}`,
            cache: false,
            data: {
                conceptId: conceptId,
                name: conceptName,
                acronym: conceptAcronym
            }
        }).success(function(response) {
            window.location.href = "?r=grades/concept";
        })
    }
});

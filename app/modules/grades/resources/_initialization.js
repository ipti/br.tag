let url = new URL(window.location.href);
let conceptId = url.searchParams.get('id');

$(document).on("click", "#saveConcept", function () {
    let conceptName = $('#conceptName').val();
    let conceptAcronym = $('#conceptAcronym').val();
    let conceptValue = $('#conceptValue').val();

    if(!conceptId) {
        $.ajax({
            type: 'POST',
            url: "?r=gradeconcept/default/create",
            cache: false,
            data: {
                name: conceptName,
                acronym: conceptAcronym,
                value: conceptValue
            }
        }).success(function(response) {
            window.location.href = "?r=gradeconcept/default";
        })
    } else {
        $.ajax({
            type: 'POST',
            url: `?r=gradeconcept/default/update&id=${conceptId}`,
            cache: false,
            data: {
                conceptId: conceptId,
                name: conceptName,
                acronym: conceptAcronym,
                value: conceptValue
            }
        }).success(function(response) {
            window.location.href = "?r=gradeconcept/default";
        })
    }
});

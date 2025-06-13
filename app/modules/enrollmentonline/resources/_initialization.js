$(document).ready(function () {
    $.ajax({
        type:'POST',
        url:`?r=enrollmentonline/enrollmentonlinestudentidentification/loadSolicitations`,
        success: function(response) {
            console.log(response);
        }
    })
})

function renderOnlineEnrollmentSolicitations(result){
    const solicitations = $(".js-add-solicitations");
    let cardsSolicitations = result.reduce((acc, element) =>
        acc +=
    `
        <div class="column clearfix no-grow">
            <a href="${window.location.host}?r=enrollmentonline/enrolmentonlineidentification/update&id=${element["id"]}" class="t-cards">
                <div class="t-cards-content">
                    <div class="t-tag-primary">${element["studentName"]}</div>
                    <div class="t-cards-title">${element["schoolName"]}</div>
                    <div class="t-cards-text clear-margin--left">${element["solicitationStatus"]}</div>
                </div>
            </a>
        </div>
    `, "");
    solicitations.html(DOMPurify.sanitize(cardsSolicitations));
}

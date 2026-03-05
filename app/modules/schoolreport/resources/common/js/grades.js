$.getJSON(getGradesUrl, {}, function (json) {
    $.each(json, function (i, v) {
        let discipline = i;
        let color = function (grade) {
            if (grade < 5) return 'red';
            if (grade >= 5) return 'blue';
            //if(grade >= 5 && grade < 7) return 'yellow';
            //if(grade >= 7 && grade < 9) return 'blue';
            //if(grade >= 9) return 'green';
        };

        let text = function (grade) {
            if (grade == null) return "<td></td>";
            else return '<td class="center aligned"><span class="ui ' + color(grade) + ' label">' + grade + '</span></td>';
        }

        let gradeTR = $("#grades tbody tr[did=" + v.discipline_fk + "]");
        let grade1 = (v.grade1 >= 5) ? v.grade1 : (v.recovery_grade1 > v.grade1 ? v.recovery_grade1 : v.grade1);
        let grade2 = (v.grade2 >= 5) ? v.grade2 : (v.recovery_grade2 > v.grade2 ? v.recovery_grade2 : v.grade2);
        let grade3 = (v.grade3 >= 5) ? v.grade3 : (v.recovery_grade3 > v.grade3 ? v.recovery_grade3 : v.grade3);
        let grade4 = (v.grade4 >= 5) ? v.grade4 : (v.recovery_grade4 > v.grade4 ? v.recovery_grade4 : v.grade4);
        let media = (parseInt(grade1) + parseInt(grade2) + parseInt(grade3) + parseInt(grade4)) / 4;
        media = media >= 5 ? media : (v.recovery_final_grade > media ? v.recovery_final_grade : media);

        if (gradeTR.size() === 0) {
            $("#grades").append("<tr did='" + v.discipline_fk + "'></tr>");
            gradeTR = $("#grades tbody tr[did=" + v.discipline_fk + "]");
        }
        //gradeTR.append('<tr>');
        gradeTR.html("");
        gradeTR.append('<td did="' + v.discipline_fk + '" class="left aligned">' + discipline + '</td>');
        gradeTR.append(text(v.grade1));
        gradeTR.append(text(v.recovery_grade1));
        gradeTR.append(text(v.grade2));
        gradeTR.append(text(v.recovery_grade2));
        gradeTR.append(text(v.grade3));
        gradeTR.append(text(v.recovery_grade3));
        gradeTR.append(text(v.grade4));
        gradeTR.append(text(v.recovery_grade4));
        gradeTR.append(text(v.recovery_final_grade));
        gradeTR.append('<td class="center aligned"><a class="ui ' + color(media) + ' image label">' + media + '<div class="detail">' + (media >= 5 ? "Aprovado" : "Reprovado") + '</div></td>');
        //gradeTR.append('</tr>');
    });
});

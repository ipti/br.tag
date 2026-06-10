function buildInputOrSelect(rule, grade, conceptOptions, isFinal) {
    if(rule == "C") {
        const optionsValues = Object.values(conceptOptions);
        const optionsKeys = Object.keys(conceptOptions);

        return `
        <td class='grade-td'>
            <select class="grade-concept ${isFinal ? 'final-concept' : ''}" style='margin: 0px 10px 0px 10px; width: 100px'>
            <option value=""></option>
            ${optionsValues
                .map(
                    (conceptOption, index) => template`
                <option value="${optionsKeys[index]}" ${
                        optionsKeys[index] == grade
                            ? "selected"
                            : ""
                    }>
                    ${conceptOption}
                </option>`
                )
                .join("")}
            </select>
        </td>
            `
    } else {
    return `
    <td class='grade-td'>
        <input type='text' class='grade' value='${grade}'></input>
    </td>`
    }
}

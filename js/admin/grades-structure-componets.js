class GradeStruct extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: "open" });
    }

    unities = [];

    addUnity(unity) {
        this.unities.push(unity);
    }

    render() {
        this.shadow.innerHTML = template`



        `;
    }
}

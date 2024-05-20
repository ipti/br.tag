css\lunch.css
C:\workingHome\br.tag\css\lunch.css


.modal{

display: block;
overflow-y: hidden;

}

.modal-title {
font-size: 18px;
padding-top: 5px !important;
color: #000000;
}

.modal-x{
display: flex;
align-items: initial;
justify-content: center;
flex-direction: column;
margin-bottom: 10p;
color: black;
font-size: 14px;
font-weight: 500;
}

.span-text{
color: #5f738c;
font-size: 14px;
font-weight: 500;
margin-bottom: 25px;
margin-left: 30px;
margin-right: 5px;

}
.text-information {
font-size: 14px;
font-weight: 500;
}
.text-aviso{
font-size: 14px;
font-weight: 700;
text-align: center;
}

.content-information{
text-align: center;
}

.linha-personalizada {
border: 0;
height: 1px;
background-color: #BAC7D5;
margin: 20px 15px;
}

.text-h4{
margin: 21px 31px -16px;
}


.recommendation-ingredient{
display: flex;
gap: 4px;
margin-left: 10px;
}

.recommendation-ingredient p{
color: #5F738C;
font-size: 14px;
font-weight: 500;

}

.recommendation-ingredient .ingredient-link{
color: #3F45EA;
font-size: 14px;
font-weight: 500;
}

.recommendation-ingredient .ingredient-link::hover{
text-decoration: underline;
}

.semaforo-line {
position: relative;
}

.semaforo-line::before {
content: '';
display: inline-block;
width: 10px;
height: 10px;
border-radius: 50%;
margin-right: 5px;
background-color: ${semaforoColor};
position: absolute;
top: 50%;
transform: translateY(-50%);
left: -20px;
}
Obsevre esse codigo:

.t-modal-container {
    color: $colorsTextPrimary;
    box-sizing: border-box;
    height: auto;
    max-height: 80vh;
    background: $colorsBaseWhiteNormal;
    border-radius: $borderRadiusNormal;
    overflow-y: scroll;
    &.larger {
        min-width: 70vw;
        right: 15vw;
        left: 15vw;
        margin: auto;
    }
    &.t-modal-rec{
        overflow-y: hidden;
        min-height: 500px;
    }
    .t-modal__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: $SizeLarge $SizeLarge;
        .t-title {
            font-size: $FontSizeHeadingTitle2;
            color: $colorsTextPrimary;
            font-weight: $fontWeightHeadingDisplay;
            margin: 0;
        }
        #myModalLabel{
            font-size: 18px;
            color: $colorTextTitleModal;
        }
        .close {
            opacity: 1;
            font-size: $SizeNormal;
        }
    }
    .t-modal__body {
        padding: $SizeNormal $SizeLarge;
    }
    .t-modal__footer {
        a, button {
            flex: 1;
            padding: $spaceNormal;

        }
    }
    &.height {
        &--is-four-fifths {
            height: 80vh;
        }
        &--is-three-fifths {
            height: 60vh;
        }
    }
    .modal-x{
        display: flex;
        align-items: initial;
        justify-content: center;
        flex-direction: column;
        margin-bottom: 10px;
        color: $colorTextTitleModal;
        font-size: 14px;
        font-weight: 500;
        }
        .span-text{
            color: $colorsBaseInkLight;
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
        background-color: $colorsBaseInkLighter;
        margin: 20px 15px;
        }
        .text-h4{
            margin: 21px 31px -16px;
            }

            .recommendation-ingredient{
                display: flex;
                gap: 4px;
                margin-left: 10px;

                p{
                    color: $colorsBaseInkLight;
                    font-size: 14px;
                    font-weight: 500;
                }
            }
                .ingredient-link{
                    color: $colorsBaseProductNormal;
                    font-size: 14px;
                    font-weight: 500;

                    &:hover{
                        text-decoration: underline;
                        }

                    }
            .semaforo-line {
                position: relative;
            }
}




com ofazer para se &.t-modal-rec onde min-height passar de 500px é necessário mostrar o overflow-y: scroll

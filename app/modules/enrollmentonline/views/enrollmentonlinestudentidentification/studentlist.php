<div id="mainPage" class="main">
    <div class="row">
        <div class="column">
            <h1>
                Pré-Matrículas
            </h1>
        </div>

    </div>
    <div class="row">


        <?php
        foreach ($studentList as $student):

            ?>
            <div class="column clearfix no-grow">
                <a href="?r=enrollmentonline/Enrollmentonlinestudentidentification/update&id=<?= $student[0]["id"] ?>"
                    class="t-cards">
                    <div class="t-cards-content">
                        <div class="t-tag-primary"><?= $student['status'] ?></div>
                        <?php if($student['status'] == 'Aprovado'){ ?>
                        <div class="t-tag-primary"><?= $student['school'] ?></div>
                        <?php } ?>
                        <div class="t-cards-title"><?= $student[0]["name"] ?></div>
                        <div class="t-cards-text clear-margin--left"><?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $student[0]["cpf"]) ?></div>
                    </div>
                </a>
            </div>
            <?php

        endforeach;
        ?>
    </div>
</div>

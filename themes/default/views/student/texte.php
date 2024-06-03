// Adicione esta função ao controlador StudentController
public function actionPrintCertificate()
{
    // Renderiza uma view simples com a mensagem "ok"
    $this->render('printCertificate');
}




Criar a View para a Nova Página:
Crie uma nova view chamada printCertificate.php dentro da pasta protected/views/student.


<!-- protected/views/student/printCertificate.php -->
<h1>Ok</h1>
<p>Aqui está a mensagem: ok</p>


<?php
echo $modelStudentIdentification->isNewRecord ? "" : '<a href=' . @Yii::app()->createUrl(
    'student/printCertificate' // Rota para a nova ação
) . ' class="t-icon-printer t-button-secondary t-button-print-certificate" id="print-certificate">Imprimir Certificado</a>';
?>




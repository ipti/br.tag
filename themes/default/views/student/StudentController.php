<?php

class StudentController extends Controller
{
    // Outras ações já existentes...

    // Adicione esta função ao controlador StudentController
    public function actionPrintCertificate()
    {
        // Renderiza uma view simples com a mensagem "ok"
        $this->render('printCertificate');
    }
}
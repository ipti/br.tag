<?php

class StudentMapper 
    {
        public static function parseToSEDAlunoFicha(
            StudentIdentification $student_tag,
            StudentDocumentsAndAddress $student_docs_tag
        )
        {
            // Dados Pessoais
            $in_dados_pessoais = new InDadosPessoais();
            $in_dados_pessoais->setInNomeAluno($student_tag->name);
            $in_dados_pessoais->setInNomeMae($student_tag->filiation_1);
            $in_dados_pessoais->setInNomePai($student_tag->filiation_2);
            $in_dados_pessoais->setInDataNascimento($student_tag->birthday);
            $in_dados_pessoais->setInCorRaca($student_tag->color_race);
            $in_dados_pessoais->setInSexo($student_tag->sex);
            $in_dados_pessoais->setInBolsaFamilia($student_tag->bf_participator);
            /// TODO: ADICIONAR CAMPO
            $in_dados_pessoais->setInQuilombola(0);
            $in_dados_pessoais->setInNumeroCNS($student_docs_tag->cns);
            $in_dados_pessoais->setInNacionalidade($student_tag->nationality);
            $in_dados_pessoais->setInNomeMunNascto($student_tag->edcensoCityFk->name);
            $in_dados_pessoais->setInUfMunNascto($student_tag->edcensoUfFk->acronym);
            $in_dados_pessoais->setInPaisOrigem($student_tag->edcensoNationFk->name);
            $in_dados_pessoais->setInCodPaisOrigem($student_tag->edcenso_nation_fk);
            /// TODO: ADICIONAR CAMPO
            // $student_personal_data->setDataEntradaPais($student_tag->dataEntradaPais);
            
            // Deficiências
            $in_deficiencia = new InDeficiencia();
            
            // Documentos  
            $in_documents = new InDocumentos();
            $in_documents->setInCodigoInep($student_tag->inep_id);
            $in_documents->setInCpf($student_docs_tag->cpf);
            $in_documents->setInNumNis($student_docs_tag->nis);
            $in_documents->setInNumDoctoCivil($student_docs_tag->rg_number);
            $in_documents->setInDigitoDoctoCivil($student_docs_tag->rg_number);
            $in_documents->setInUfDoctoCivil($student_docs_tag->rgNumberEdcensoUfFk->acronym);
            $in_documents->setInDataEmissaoDoctoCivil($student_docs_tag->rg_number_expediction_date);
            $in_documents->setInDataEmissaoDoctoCivil($student_docs_tag->civil_certification_date);

            // Endereco
            $in_endereco_residencial = new InEnderecoResidencial();
            $in_endereco_residencial->setInLogradouro($student_docs_tag->address);
            $in_endereco_residencial->setInNumero($student_docs_tag->number);
            $in_endereco_residencial->setInBairro($student_docs_tag->neighborhood);
            $in_endereco_residencial->setInNomeCidade($student_docs_tag->edcensoCityFk->name);
            $in_endereco_residencial->setInUfCidade($student_docs_tag->edcensoUfFk->acronym);
            $in_endereco_residencial->setInComplemento($student_docs_tag->complement);
            $in_endereco_residencial->setInCep($student_docs_tag->cep);
            $in_endereco_residencial->setInAreaLogradouro($student_docs_tag->residence_zone);
            $in_endereco_residencial->setInCodLocalizacaoDiferenciada($student_docs_tag->diff_location);
            $in_endereco_residencial->setInLatitude(0);
            $in_endereco_residencial->setInLongitude(0);

            // Recursos
            $in_recurso_avaliacao = new InRecursoAvaliacao();
            $in_recurso_avaliacao->setInNenhum($student_tag->resource_none);
            $in_recurso_avaliacao->setInAuxilioLeitor($student_tag->resource_aid_lector);
            $in_recurso_avaliacao->setInAuxilioTranscricao($student_tag->resource_aid_transcription);
            $in_recurso_avaliacao->setInLeituraLabial($student_tag->resource_lip_reading);
            $in_recurso_avaliacao->setInProvaBraile($student_tag->resource_braille_test);
            $in_recurso_avaliacao->setInProvaAmpliada($student_tag->resource_zoomed_test_20);
            $font_size = $student_tag->resource_zoomed_test_16 ? 1 : null;
            $font_size = $student_tag->resource_zoomed_test_20 ? 0 : null;
            $in_recurso_avaliacao->setInFonteProva($font_size);
            $in_recurso_avaliacao->setInProvaVideoLibras($student_tag->resource_video_libras);
            $in_recurso_avaliacao->setInProvaLinguaPortuguesa($student_tag->resource_proof_language);

            // Rastreio
            // $in_rastreio = new InRastreio();

            $student_register = new AlunoFicha(
                $in_dados_pessoais,
                $in_deficiencia,
                $in_recurso_avaliacao,
                $in_documents,
                null,
                null,
                $in_endereco_residencial,
                null
            );

            return $student_register;
        }
        /**
         * Summary of parseToTAGExibirFichaAluno
         * @param OutExibirFichaAluno $exibirFichaAluno
         */
        public static function parseToTAGExibirFichaAluno(OutExibirFichaAluno $exibirFichaAluno)
        {
            $parseResult = [];
        
            $outDadosPessoais = $exibirFichaAluno->getOutDadosPessoais();
            $outDocumentos = $exibirFichaAluno->getOutDocumentos();
            $outCertidaoNova = $exibirFichaAluno->getOutCertidaoNova();
            $outCertidaoAntiga = $exibirFichaAluno->getOutCertidaoAntiga();
            $outEnderecoResidencial = $exibirFichaAluno->getOutEnderecoResidencial();

            $listaAluno = new EnrollmentSEDDataSource();
            $response = $listaAluno->getListarMatriculasRA(new InAluno($outDadosPessoais->getOutNumRa(), null, $outDadosPessoais->getOutSiglaUfra()));
            $schoolInep = '35'.$response->getOutListaMatriculas()[0]->getOutCodEscola();

            $studentIdentification = new StudentIdentification;
            $studentIdentification->school_inep_id_fk = $schoolInep;
            $studentIdentification->inep_id = $outDadosPessoais->getOutNumRa();
            $studentIdentification->gov_id = $outDadosPessoais->getOutDigitoRa();
            $studentIdentification->name = $outDadosPessoais->getOutNomeAluno();
            $studentIdentification->filiation = $outDadosPessoais->getOutNomeMae() != "" || $outDadosPessoais->getOutNomePai() != "" ? 1 : 0;
            $studentIdentification->filiation_1 = $outDadosPessoais->getOutNomeMae();
            $studentIdentification->filiation_2 = $outDadosPessoais->getOutNomePai();
            $studentIdentification->birthday = date_create_from_format('d/m/Y', $outDadosPessoais->getOutDataNascimento())->format('Y-m-d');
            $studentIdentification->color_race = empty($outDadosPessoais->getOutCorRaca())? 0 : $outDadosPessoais->getOutCorRaca();
            $studentIdentification->sex = $outDadosPessoais->getOutCodSexo();
            $studentIdentification->bf_participator = $outDadosPessoais->getOutCodBolsaFamilia();
            $studentIdentification->nationality = intval($outDadosPessoais->getOutNacionalidade());
            $studentIdentification->edcenso_nation_fk = intval(EdcensoNation::model()->find("name = :name", [":name" => $outDadosPessoais->getOutNomePaisOrigem()])->id);
            $studentIdentification->edcenso_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outDadosPessoais->getOutUfMunNascto()])->id);
            $studentIdentification->edcenso_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outDadosPessoais->getOutNomeMunNascto()])->id);
            $studentIdentification->deficiency = 0;
            $studentIdentification->send_year = intval(Yii::app()->user->year);

            //StudentDocuments
            $studentDocumentsAndAddress = new StudentDocumentsAndAddress;
            $studentDocumentsAndAddress->school_inep_id_fk = $schoolInep;
            $studentDocumentsAndAddress->cpf = $outDocumentos->getOutCpf();
            $studentDocumentsAndAddress->nis = $outDocumentos->getOutNumNis();
            $studentDocumentsAndAddress->rg_number = $outDocumentos->getOutNumDoctoCivil() + $outDocumentos->getOutDigitoDoctoCivil();
            
            if($outDocumentos->getOutDataEmissaoDoctoCivil()) {
                $studentDocumentsAndAddress->rg_number_expediction_date = date_create_from_format('d/m/Y', $outDocumentos->getOutDataEmissaoDoctoCivil())->format('Y-m-d');
            }
            if($outDocumentos->getOutDataEmissaoCertidao()) {
                $studentDocumentsAndAddress->civil_certification_date = date_create_from_format('d/m/Y', $outDocumentos->getOutDataEmissaoCertidao())->format('Y-m-d');
            }


            //Address
            $studentDocumentsAndAddress->address = $outEnderecoResidencial->getOutLogradouro();
            $studentDocumentsAndAddress->number = $outEnderecoResidencial->getOutNumero();
            $studentDocumentsAndAddress->neighborhood = $outEnderecoResidencial->getOutBairro();
            $studentDocumentsAndAddress->complement = $outEnderecoResidencial->getOutComplemento();
            $studentDocumentsAndAddress->cep = $outEnderecoResidencial->getOutCep();
            $studentDocumentsAndAddress->residence_zone = $outEnderecoResidencial->getOutAreaLogradouro() == "URBANA" ? 1 : 2;
            
            if($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Não está localizado em área de localização diferenciada") {
                $studentDocumentsAndAddress->diff_location = 7;
            }else if($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Área onde se localizada em Comunidade remanescente de Quilombos") {
                $studentDocumentsAndAddress->diff_location = 3;
            }else if($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Terra indígena") {
                $studentDocumentsAndAddress->diff_location = 2;
            }else if($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Área de assentamento") {
                $studentDocumentsAndAddress->diff_location = 1;
            }

            //Civil_certification
            if(isset($outCertidaoNova)) {
                // @todo identificar como descobrir o município de certidao nova
                $studentDocumentsAndAddress->civil_certification = 2;
                $studentDocumentsAndAddress->civil_certification_term_number = $outCertidaoNova->getOutCertMatr08();
                $studentDocumentsAndAddress->civil_certification_sheet = $outCertidaoNova->getOutCertMatr07();
                $studentDocumentsAndAddress->civil_certification_book = $outCertidaoNova->getOutCertMatr06();
            }else if(isset($outCertidaoAntiga)) {
                $studentDocumentsAndAddress->civil_certification = 1;
                $studentDocumentsAndAddress->civil_certification_type = 1;
                $studentDocumentsAndAddress->civil_certification_term_number = $outCertidaoAntiga->getOutNumCertidao();
                $studentDocumentsAndAddress->civil_certification_sheet = $outCertidaoAntiga->getOutFolhaRegNum();
                $studentDocumentsAndAddress->civil_certification_book = $outCertidaoAntiga->getOutNumLivroReg();
                $studentDocumentsAndAddress->notary_office_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outCertidaoAntiga->getOutUfComarca()])->id);
                $studentDocumentsAndAddress->notary_office_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outCertidaoAntiga->getOutNomeMunComarca()])->id);
            }

            $parseResult["StudentIdentification"] = $studentIdentification;
            $parseResult["StudentDocumentsAndAddress"] = $studentDocumentsAndAddress;

            return $parseResult;
        }
    }
?>
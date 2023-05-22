<?php

Yii::import('application.modules.sedsp.models.*');
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
        public static function parseToTAGAlunoFicha($content)
        {
            $response = json_decode($content);
            $result = [];

            $outDadosPessoais = $response->outDadosPessoais;
            $outDocumentos = $response->outDocumentos;
            $outCertidaoNova = $response->outCertidaoNova;
            $outCertidaoAntiga = $response->outCertidaoAntiga;
            $outEnderecoResidencial = $response->outEnderecoResidencial;

            $student_tag = new StudentIdentification;
            $student_docs_tag = new StudentDocumentsAndAddress;

            // StudentIdentification
            $student_tag->school_inep_id_fk = Yii::app()->user->school;
            $student_tag->inep_id = $outDocumentos->outCodINEP;
            $student_tag->gov_id = $outDadosPessoais->outNumRA;
            $student_tag->name = $outDadosPessoais->outNomeAluno;
            $student_tag->filiation = $outDadosPessoais->outNomeMae != "" || $outDadosPessoais->outNomePai != "" ? 1 : 0;
            $student_tag->filiation_1 = $outDadosPessoais->outNomeMae;
            $student_tag->filiation_2 = $outDadosPessoais->outNomePai;
            $student_tag->birthday = date_create_from_format('d/m/Y', $outDadosPessoais->outDataNascimento)->format('Y-m-d');
            $student_tag->color_race = $outDadosPessoais->outCorRaca;
            $student_tag->sex = $outDadosPessoais->outCodSexo;
            $student_tag->bf_participator = $outDadosPessoais->outCodBolsaFamilia;
            $student_tag->nationality = intval($outDadosPessoais->outNacionalidade);
            $student_tag->edcenso_nation_fk = intval(EdcensoNation::model()->find("name = :name", [":name" => $outDadosPessoais->outNomePaisOrigem])->id);
            $student_tag->edcenso_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outDadosPessoais->outUFMunNascto])->id);
            $student_tag->edcenso_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outDadosPessoais->outNomeMunNascto])->id);
            $student_tag->deficiency = 0;
            $student_tag->send_year = intval(Yii::app()->user->year);

            // StudentDocumentsAndAddress
            // documents
            $student_docs_tag->cpf = $outDocumentos->outCPF;
            $student_docs_tag->nis = $outDocumentos->outNumNIS;
            $student_docs_tag->rg_number = $outDocumentos->outNumDoctoCivil + $outDocumentos->outDigitoDoctoCivil;
            if($outDocumentos->outDataEmissaoDoctoCivil) {
                $student_docs_tag->rg_number_expediction_date = date_create_from_format('d/m/Y', $outDocumentos->outDataEmissaoDoctoCivil)->format('Y-m-d');
            }
            if($outDocumentos->outDataEmissaoCertidao) {
                $student_docs_tag->civil_certification_date = date_create_from_format('d/m/Y', $outDocumentos->outDataEmissaoCertidao)->format('Y-m-d');
            }
            // address
            $student_docs_tag->address = $outEnderecoResidencial->outLogradouro;
            $student_docs_tag->number = $outEnderecoResidencial->outNumero;
            $student_docs_tag->neighborhood = $outEnderecoResidencial->outBairro;
            $student_docs_tag->complement = $outEnderecoResidencial->outComplemento;
            $student_docs_tag->cep = $outEnderecoResidencial->outCep;
            $student_docs_tag->residence_zone = $outEnderecoResidencial->outAreaLogradouro == "URBANA" ? 1 : 2;
            if($outEnderecoResidencial->outLocalizacaoDiferenciada == "Não está localizado em área de localização diferenciada") {
                $student_docs_tag->diff_location = 7;
            }else if($outEnderecoResidencial->outLocalizacaoDiferenciada == "Área onde se localizada em Comunidade remanescente de Quilombos") {
                $student_docs_tag->diff_location = 3;
            }else if($outEnderecoResidencial->outLocalizacaoDiferenciada == "Terra indígena") {
                $student_docs_tag->diff_location = 2;
            }else if($outEnderecoResidencial->outLocalizacaoDiferenciada == "Área de assentamento") {
                $student_docs_tag->diff_location = 1;
            }

            // civil_certification
            if(isset($outCertidaoNova)) {
                // @todo identificar como descobrir o município de certidao nova
                $student_docs_tag->civil_certification = 2;
                $student_docs_tag->civil_certification_term_number = $outCertidaoNova->outCertMatr08;
                $student_docs_tag->civil_certification_sheet = $outCertidaoNova->outCertMatr07;
                $student_docs_tag->civil_certification_book = $outCertidaoNova->outCertMatr06;
            }else if(isset($outCertidaoAntiga)) {
                $student_docs_tag->civil_certification = 1;
                $student_docs_tag->civil_certification_type = 1;
                $student_docs_tag->civil_certification_term_number = $outCertidaoAntiga->outNumCertidao;
                $student_docs_tag->civil_certification_sheet = $outCertidaoAntiga->outFolhaRegNum;
                $student_docs_tag->civil_certification_book = $outCertidaoAntiga->outNumLivroReg;
                $student_docs_tag->notary_office_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outCertidaoAntiga->outUFComarca])->id);
                $student_docs_tag->notary_office_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outCertidaoAntiga->outNomeMunComarca])->id);
            }

            $result["StudentIdentification"] = $student_tag;
            $result["StudentDocumentsAndAddress"] = $student_docs_tag;

            return $result;
        }
    }
    


?>
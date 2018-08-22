/**
 * Complaint Form
 */
import React, { Component } from 'react';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input,
	TextArea,
	Col
} from 'reactstrap';

import FormControlLabel from '@material-ui/core/FormControlLabel';
import FormLabel from '@material-ui/core/FormLabel';
import FormControl from '@material-ui/core/FormControl';
import Radio from '@material-ui/core/Radio';
import RadioGroup from '@material-ui/core/RadioGroup';

// rct card box

import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';


export default class ComplaintForm extends Component {

    state = {
        tipoViolencia: 'maus_tratos'
    }

    handleChangeRadio = (e, key) => {
		this.setState({ [key]: e.target.value });
	}

    render(){
        const { classes } = this.props;
        return (
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Nova Denúncia">
                        <Form>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Dados Pessoais</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="Nome">Nome da criança</Label>
                                        <Input type="text" name="name" id="Nome" bsSize="lg" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-4">
                                    <FormGroup>
                                        <Label for="Nome">Idade</Label>
                                        <Input type="text" name="name" id="Nome" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="sexo">Sexo</Label>
                                        <Input type="select" name="sexo" id="sexo" bsSize="lg">
                                            <option value="F">Feminino</option>
                                            <option value="M">Masculino</option>
                                        </Input>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-6">
                                    <FormGroup>
                                        <Label for="ano">Ano/Série</Label>
                                        <Input type="text" name="ano" id="ano" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-male-female zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Responsáveis</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-md-12">
                                    <FormGroup>
                                        <Label for="responsavel">Nome do responsável</Label>
                                        <Input type="text" name="responsavel" id="responsavel" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-pin zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Endereço</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-5">
                                    <FormGroup>
                                        <Label for="cidade">Cidade</Label>
                                        <Input type="text" name="cidade" id="cidade" bsSize="lg" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-7">
                                    <FormGroup>
                                        <Label for="logradouro">Rua/Av./Rodovia</Label>
                                        <Input type="text" name="logradouro" id="logradouro" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-3">
                                    <FormGroup>
                                        <Label for="numero">Número</Label>
                                        <Input type="text" name="numero" id="numero" bsSize="lg" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="bairro">Bairro</Label>
                                        <Input type="text" name="bairro" id="bairro" bsSize="lg" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-5">
                                    <FormGroup>
                                        <Label for="complemento">Complemento</Label>
                                        <Input type="text" name="complemento" id="complemento" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Dados da ocorrência</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-12">
                                    <FormControl component="fieldset">
                                        <RadioGroup row aria-label="tipoViolencia" name="tipoViolencia" value={this.state.tipoViolencia}
                                            onChange={(e) => this.handleChangeRadio(e, 'tipoViolencia')} >
                                            <FormControlLabel value="maus_tratos" control={<Radio />} label="Maus Tratos" />
                                            <FormControlLabel value="negligencia" control={<Radio />} label="Negligência" />
                                            <FormControlLabel value="fisica" control={<Radio />} label="Violência física" />
                                            <FormControlLabel value="psicologica" control={<Radio />} label="Violência psicológica" />
                                            <FormControlLabel value="sexual" control={<Radio />} label="Violência sexual" />
                                            <FormControlLabel value="outros" control={<Radio />} label="Outros" />
                                        </RadioGroup>
                                    </FormControl>
                                </div>
                            </div>

                            <div className="row mt-40">
                                <div className="col-sm-12">
                                    <FormGroup>
                                        <Label for="descricao">Descrição dos fatos</Label>
                                        <textarea className="form-control" name="descricao" rows="5"></textarea>
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="encaminhado">Encaminhado em</Label>
                                        <Input type="date" name="encaminhado" id="encaminhado" bsSize="lg" placeholder="" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-6">
                                    <FormGroup>
                                        <Label for="recebido">Recebido em</Label>
                                        <Input type="date" name="recebido" id="recebido" bsSize="lg" placeholder="" />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="noticiante">Noticiante</Label>
                                        <Input type="text" name="noticiante" id="noticiante" bsSize="lg" />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-6">
                                    <FormGroup>
                                        <Label for="receptor">Recebido por</Label>
                                        <Input type="text" name="receptor" id="receptor" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>
                            <Button color="primary">Cadastrar</Button>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}
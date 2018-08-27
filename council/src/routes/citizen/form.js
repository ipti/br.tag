/**
 * Citizen Form
 */
import React, { Component } from 'react';
import SimpleBar from 'Components/NavBar/SimpleBar';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input,
	TextArea,
} from 'reactstrap';

import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

import FormControlLabel from '@material-ui/core/FormControlLabel';
import FormLabel from '@material-ui/core/FormLabel';
import FormControl from '@material-ui/core/FormControl';
import Radio from '@material-ui/core/Radio';
import RadioGroup from '@material-ui/core/RadioGroup';

export default class CitizenForm extends Component {

	constructor(props){
		super(props);
		this.state = {
			processId: 0
		};
    }

	render() {
		return (
			<div>
				<SimpleBar />
					<div className="session-inner-wrapper mt-40">
						<div className="container">
							<div className="row justify-content-md-center align-items-center">
								<div className="col-sm-5 col-md-5 col-lg-10">
                                    <RctCollapsibleCard heading="Nova Denúncia">
                                        <Form>
                                            <div className="row mb-4">
                                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                    <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                                    <h3 className="mb-0 text-primary"> Dados dos envolvidos</h3>
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
                                                        <Label for="Nome">Nome do agressor</Label>
                                                        <Input type="text" name="name" id="Nome" bsSize="lg" />
                                                    </FormGroup>
                                                </div>
                                                <div className="col-md-12">
                                                    <FormGroup>
                                                        <Label for="Nome">Nome da vítima</Label>
                                                        <Input type="text" name="name" id="Nome" bsSize="lg" />
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
                                            <div className="row mt-40">
                                                <div className="col-sm-12">
                                                    <FormGroup>
                                                        <Label for="descricao">Descrição dos fatos</Label>
                                                        <textarea className="form-control" name="descricao" rows="5"></textarea>
                                                    </FormGroup>
                                                </div>
                                            </div>
                                        </Form>
                                        <Button color="primary">Enviar</Button>
                                    </RctCollapsibleCard>
								</div>
							</div>
						</div>
					</div>
			</div>
		);
	}
}

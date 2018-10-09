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
	Alert,
} from 'reactstrap';

import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

import api from 'Api';
import config from '../../constants/AppConfig';
import param from '../../util/Param';

export default class CitizenForm extends Component {

    errorValidation = {}

	constructor(props){
		super(props);
		this.state = this.getInitialState()
    }

    getInitialState() {
        const initialState = {
            child: null,
            aggressor: null,
            address: {},
            city: null,
            street: null,
            neighborhood: null,
            number: null,
            complement: null,
            description: null,
            forwards: [],
            error: [],
            success: [],
            disableButton: false
        };

        return initialState;
    }
    
    resetState(update={}) {
        this.setState({...this.getInitialState(), ...update});
        this.forceUpdate();
        document.forms[0].reset();
    }   

    handleChange = (e, key) => {
        this.setState({ [key]: e.target.value });
    }

    normalizeFields = () => {
        var date = new Date();
        var actualDate = `${date.getDate()}/${new String(date.getMonth() + 1).padStart(2,'0')}/${date.getFullYear()}`
        const fields = {
            child: {
                name: this.state.child
            },
            aggressor: this.state.aggressor,
            place: config.citizen.institution,
            address: {
                city: this.state.city,
                street: this.state.street,
                number: this.state.number,
                neighborhood: this.state.neighborhood,
                complement: this.state.complement,
            },
            forwards: [
                {
                    description: this.state.description,
                    date: actualDate,
                    user: config.citizen.id,
                    institution: config.citizen.institution
                }
            ]
        }
        return fields;
    }

    setError(field, message){
        field.classList.add('is-invalid');
        field.nextSibling.style.display = 'block';
        field.nextSibling.innerHTML = message;
    }

    clearError(field){
        field.classList.remove('is-invalid');
        field.nextSibling.style.display = 'none';
        field.nextSibling.innerHTML = '';
    }

    validate(){
        var error = false;
        let el = (id) => document.getElementById(id);
        let fields = [
            {field: 'aggressor', message: 'Informe o nome do agressor'},
            {field: 'child', message: 'Informe o nome da criança'},
            {field: 'city', message: 'Informe a cidade'},
            {field: 'street', message: 'Informe a rua'},
            {field: 'neighborhood', message: 'Informe o bairro'},
            {field: 'description', message: 'Informe o ocorrido'},
        ];

        for (let i = 0; i < fields.length; i++) {
            const element = fields[i];
            if(el(element.field).value == ''){
                this.setError(el(element.field), element.message);
                error = true;
            }
            else{
                this.clearError(el(element.field));
            }
        }

        return !error;
    }
    
    handleSubmit() {
       if(this.validate()) {
           this.setState({ ['disableButton']: true });

            var header = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            };

            var data = this.normalizeFields();
            var url = param(data);

            api.post(`/v1/citizen/create?access-token=${config.citizen.access_token}`, url, header)
                .then(function(response){
                    if(typeof response.data.status !== 'undefined'){
                        if(response.data.status == '1'){
                            this.setState({['success']: [`Denúncia Nº: ${response.data.data._id} cadastrada com sucesso.`]});
                            alert(`Denúncia cadastrada. \nNúmero: ${response.data.data._id}`);
                            this.resetState();
                        }
                        else{
                            const errors = response.data.error;
                            const messageError = Object.keys(errors).map(key => 
                                errors[key].map(id => `${key} - ${id}`)
                            );
                            this.setState({['error']: messageError});
                        }
                    }
                    this.setState({ ['disableButton']: false });
                }.bind(this))
                .catch(function(error){
                    this.setState({ ['disableButton']: false });
            }.bind(this));
        }
      }

	render() {
        const displayBlock ={display:'block'}
        if (this.state.disableButton) {
			return (
				<RctSectionLoader />
			)
		}
		return (
			<div>
				<SimpleBar />
					<div className="session-inner-wrapper mt-40">
						<div className="container">
							<div className="row justify-content-md-center align-items-center">
								<div className="col-sm-5 col-md-5 col-lg-10">
                                    <RctCollapsibleCard heading="Nova Denúncia">
                                        {
                                            this.state.error.length > 0 &&
                                            this.state.error.map((item, index) =>
                                                <Alert color="danger" id={index}> {item} </Alert>
                                            )
                                        }
                                        {
                                            this.state.success.length > 0 &&
                                            this.state.success.map((item, index) =>
                                                <Alert color="success" id={index}> {item} </Alert>
                                            )
                                        }
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
                                                        <Input type="text" name="aggressor" id="aggressor" autoComplete="off" value={this.state.aggressor} onChange={(e) => this.handleChange(e, 'aggressor')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                                <div className="col-md-12">
                                                    <FormGroup>
                                                        <Label for="Nome">Nome da vítima</Label>
                                                        <Input type="text" name="child" id="child" autoComplete="off" value={this.state.child} onChange={(e) => this.handleChange(e, 'child')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
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
                                                        <Label for="city">Cidade</Label>
                                                        <Input type="text" name="city" id="city" autoComplete="off" value={this.state.city} onChange={(e) => this.handleChange(e, 'city')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-7">
                                                    <FormGroup>
                                                        <Label for="street">Rua/Av./Rodovia</Label>
                                                        <Input type="text" name="street" id="street" autoComplete="off" value={this.state.street} onChange={(e) => this.handleChange(e, 'street')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-sm-12 col-md-3">
                                                    <FormGroup>
                                                        <Label for="number">Número</Label>
                                                        <Input type="text" name="number" id="number" autoComplete="off" value={this.state.number} onChange={(e) => this.handleChange(e, 'number')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-4">
                                                    <FormGroup>
                                                        <Label for="neighborhood">Bairro</Label>
                                                        <Input type="text" name="neighborhood" id="neighborhood" autoComplete="off" value={this.state.neighborhood} onChange={(e) => this.handleChange(e, 'neighborhood')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-5">
                                                    <FormGroup>
                                                        <Label for="complement">Complemento</Label>
                                                        <Input type="text" name="complement" id="complement" autoComplete="off" value={this.state.complement} onChange={(e) => this.handleChange(e, 'complement')} bsSize="lg" />
                                                        <div className="invalid-feedback" ></div>
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
                                                        <textarea className="form-control" id="description" name="description" autoComplete="off" value={this.state.description} onChange={(e) => this.handleChange(e, 'description')} rows="5"></textarea>
                                                        <div className="invalid-feedback" ></div>
                                                    </FormGroup>
                                                </div>
                                            </div>
                                        </Form>
                                        <Button color="primary" disabled={this.state.disableButton} onClick={() => this.handleSubmit()}>Enviar</Button>
                                    </RctCollapsibleCard>
								</div>
							</div>
						</div>
					</div>
			</div>
		);
	}
}

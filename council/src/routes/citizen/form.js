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

import api from 'Api';
import config from '../../constants/AppConfig';

export default class CitizenForm extends Component {

	constructor(props){
		super(props);
		this.state = this.getInitialState()
    }

    getInitialState = () => {
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
            disableButton: false
        };

        return initialState;
    }

    resetState = () => {
        this.setState(this.getInitialState());
    }   

    handleChange = (e, key) => {
        this.setState({ [key]: e.value });
    }

    normalizeFields = () => {
        var date = new Date();
        var actualDate = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`
        const fields = {
            child: this.state.child,
            aggressor: this.state.aggressor,
            address: {
                city: this.state.city,
                street: this.state.street,
                number: this.state.number,
                neighborhood: this.state.neighborhood,
                complement: this.state.complement,
                forwards: [
                    {
                        description: this.state.description,
                        date: actualDate,
                        user: 'Cidadão'
                    }
                ]
            }
        }
        return fields;
    }
    
    handleSubmit() {
        
        this.setState({ ['disableButton']: true });

        var param = {
            headers: {
                "Authorization": `Bearer ${config.citizen.access_token}`,
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Headers': 'OPTIONS',
                'Content-Type': 'application/json'
            },
            withCredentials: true,
        };
        console.log(param);
        api.post('/v1/citizen/create', this.normalizeFields(), param)
            .then(function(response){
                if(typeof response.data.status !== 'undefined'){
                    if(response.data.status == '1'){
                        alert(`Denúncia enviada! \n Número: ${response.data.data._id}`);
                        this.resetState();
                    }
                    else{
                        const errors = response.data.error;
                        const messageError = Object.keys(errors).map(key => 
                            errors[key].map(id => errors[key][id])
                        );
                        console.log(messageError);
                        this.setState({['error']: messageError});
                    }
                }
                this.setState({ ['disableButton']: false });
            }.bind(this))
            .catch(function(error){
                this.setState({ ['disableButton']: false });
        }.bind(this));
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
                                        {
                                            this.state.error.lenght > 0 &&
                                            this.state.error.map((item, index) =>
                                                <Alert color="danger" id={index}> {item} </Alert>
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
                                                    </FormGroup>
                                                </div>
                                                <div className="col-md-12">
                                                    <FormGroup>
                                                        <Label for="Nome">Nome da vítima</Label>
                                                        <Input type="text" name="child" id="child" autoComplete="off" value={this.state.child} onChange={(e) => this.handleChange(e, 'child')} bsSize="lg" />
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
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-7">
                                                    <FormGroup>
                                                        <Label for="street">Rua/Av./Rodovia</Label>
                                                        <Input type="text" name="street" id="street" autoComplete="off" value={this.state.street} onChange={(e) => this.handleChange(e, 'street')} bsSize="lg" />
                                                    </FormGroup>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-sm-12 col-md-3">
                                                    <FormGroup>
                                                        <Label for="number">Número</Label>
                                                        <Input type="text" name="number" id="number" autoComplete="off" value={this.state.number} onChange={(e) => this.handleChange(e, 'number')} bsSize="lg" />
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-4">
                                                    <FormGroup>
                                                        <Label for="neighborhood">Bairro</Label>
                                                        <Input type="text" name="neighborhood" id="neighborhood" autoComplete="off" value={this.state.neighborhood} onChange={(e) => this.handleChange(e, 'neighborhood')} bsSize="lg" />
                                                    </FormGroup>
                                                </div>
                                                <div className="col-sm-12 col-md-5">
                                                    <FormGroup>
                                                        <Label for="complement">Complemento</Label>
                                                        <Input type="text" name="complement" id="complement" autoComplete="off" value={this.state.complement} onChange={(e) => this.handleChange(e, 'complement')} bsSize="lg" />
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
                                                        <textarea className="form-control" name="description" autoComplete="off" value={this.state.description} onChange={(e) => this.handleChange(e, 'description')} rows="5"></textarea>
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

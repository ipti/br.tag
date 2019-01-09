/** 
 * Food Action
*/

import React, { Component } from "react";
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

export default class Food_Action extends Component {
    constructor(props){
        super(props);
        this.state = this.getInitialState()
    }

    getInitialState() {
        const initialState = {
            child: null,
            parent: null,
            rg: null,
            cpf: null,
            phone: null,
            city_parent: null,
            street_parent: null,
            number_parent: null,
            neighborhood_parent: null,
            complement_parent: null,

            requisite: null,
            city_requisite: null,
            street_requisite: null,
            number_requisite: null,
            neighborhood_requisite: null,
            complement_requisite: null,

            description: null,
            disableButton: false
        };
        return initialState;
    }

    normalizeFields = () => {
        const fields = {
            child: { 
                name: this.state.child
             },
            parent: {
                name: this.state.parent,
                rg_parent: this.state.rg,
                cpf_parent: this.state.cpf,
                phone_parent: this.state.phone_parent,
            },
            address_parent: { 
                city: this.state.city_parent,
                street: this.state.street_parent,
                number: this.state.number_parent,
                neighborhood: this.state.neighborhood_parent,
                complement: this.state.complement_parent,
             },
            requisite: this.state.requisite,
            address_requisite: { 
                city: this.state.city_requisite,
                street: this.state.street_requisite,
                number: this.state.number_requisite,
                neighborhood: this.state.neighborhood_requisite,
                complement: this.state.complement_requisite,
             },
            forwards: { 
                description: this.state.description
             }
        }
        return fields;
    }

    handleChange = (e, key) => {
        this.setState({ [key]: e.target.value });
    }

    handleSubmit() {
        var data = this.normalizeFields();

        console.log(data);
    }

    render(){
        return(
            <div>
                <SimpleBar/>
                <div className="session-inner-wrapper mt-40">
                    <div className="container">
                        <div className="row justify-content-md-center align-items-center">
                            <div className="col-sm-5 col-md-5 col-lg-10">
                                <RctCollapsibleCard heading="Nova Ação Alimentar">
                                    <Form>
                                        <div className="row mb-4">
                                            <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                <span className="mr-2">
                                                    <i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i>
                                                </span>
                                                <h2 className="mb-0 text-primary">Dados do Requerente</h2>
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
                                                    <Label for="Nome">Nome da Criança</Label>
                                                    <Input type="text" name="child" id="child" value={ this.state.child } onChange={ (e) => this.handleChange(e, 'child') } />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-md-12">
                                                <FormGroup>
                                                    <Label for="Nome">Nome do(a) Genitor(a)</Label>
                                                    <Input type="text" name="parent" id="parent" value={ this.state.parent } onChange={ (e) => this.handleChange(e, 'parent') } />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col">
                                                <FormGroup>
                                                    <Label for="RG">RG do(a) Genitor(a)</Label>
                                                    <Input type="text" name="rg" id="rg" value={ this.state.rg } onChange={ (e) => this.handleChange(e, 'rg') } />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col">
                                                <FormGroup>
                                                    <Label for="CPF">CPF do(a) Genitor(a)</Label>
                                                    <Input type="text" name="cpf" id="cpf" value={ this.state.cpf } onChange={ (e) => this.handleChange(e, 'cpf') } />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col">
                                                <FormGroup>
                                                    <Label for="phone">Telefone do(a) Genitor(a)</Label>
                                                    <Input type="text" name="phone" id="phone" value={ this.state.phone } onChange={ (e) => this.handleChange(e, 'phone') } />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                <span className="mr-2">
                                                    <i className="zmdi zmdi-pin zmdi-hc-lg text-primary"></i>
                                                </span>
                                                <h3 className="mb-0 text-primary">Endereço</h3>
                                            </div>
                                        </div>
                                        <div className="row mb-4">
                                            <div className="col-sm-6 col-md-6">
                                                <span className="w-100 form-header-primary"></span>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col-sm-12 col-md-5">
                                                <FormGroup>
                                                    <Label for="city_parent">Cidade</Label>
                                                    <Input type="text" name="city_parent" id="city_parent" value={ this.state.city_parent } onChange={(e) => this.handleChange(e, 'city_parent')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-7">
                                                <FormGroup>
                                                    <Label for="street_parent">Rua/Av./Rodovia</Label>
                                                    <Input type="text" name="street_parent" id="street_parent" value={ this.state.street_parent } onChange={(e) => this.handleChange(e, 'street_parent')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-sm-12 col-md-3">
                                                <FormGroup>
                                                    <Label for="number_parent">Número</Label>
                                                    <Input type="text" name="number_parent" id="number_parent" value={ this.state.number_parent } onChange={(e) => this.handleChange(e, 'number_parent')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-4">
                                                <FormGroup>
                                                    <Label for="neighborhood_parent">Bairro</Label>
                                                    <Input type="text" name="neighborhood_parent" id="neighborhood_parent" value={ this.state.neighborhood_parent } onChange={(e) => this.handleChange(e, 'neighborhood_parent')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-5">
                                                <FormGroup>
                                                    <Label for="complement_parent">Complemento</Label>
                                                    <Input type="text" name="complement_parent" id="complement_parent" value={ this.state.complement_parent } onChange={(e) => this.handleChange(e, 'complement_parent')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div>
                                                <span className="mr-2"></span>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                <span className="mr-2">
                                                    <i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i>
                                                </span>
                                                <h2 className="mb-0 text-primary">Dados do Requerido</h2>
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
                                                    <Label for="Nome">Nome do Requerido</Label>
                                                    <Input type="text" name="requisite" id="requisite" value={ this.state.requisite } onChange={(e) => this.handleChange(e, 'requisite')} />
                                                    <div className="invalid-feedback"></div>
                                                </FormGroup>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                <span className="mr-2">
                                                    <i className="zmdi zmdi-pin zmdi-hc-lg text-primary"></i>
                                                </span>
                                                <h3 className="mb-0 text-primary">Endereço</h3>
                                            </div>
                                        </div>
                                        <div className="row mb-4">
                                            <div className="col-sm-6 col-md-6">
                                                <span className="w-100 form-header-primary"></span>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col-sm-12 col-md-5">
                                                <FormGroup>
                                                    <Label for="city_requisite">Cidade</Label>
                                                    <Input type="text" name="city_requisite" id="city_requisite" value={ this.state.city_requisite } onChange={(e) => this.handleChange(e, 'city_requisite')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-7">
                                                <FormGroup>
                                                    <Label for="street_requisite">Rua/Av./Rodovia</Label>
                                                    <Input type="text" name="street_requisite" id="street_requisite" value={ this.state.street_requisite } onChange={(e) => this.handleChange(e, 'street_requisite')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-sm-12 col-md-3">
                                                <FormGroup>
                                                    <Label for="number_requisite">Número</Label>
                                                    <Input type="text" name="number_requisite" id="number_requisite" value={ this.state.number_requisite } onChange={(e) => this.handleChange(e, 'number_requisite')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-4">
                                                <FormGroup>
                                                    <Label for="neighborhood_requisite">Bairro</Label>
                                                    <Input type="text" name="neighborhood_requisite" id="neighborhood_requisite" value={ this.state.neighborhood_requisite } onChange={(e) => this.handleChange(e, 'neighborhood_requisite')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                            <div className="col-sm-12 col-md-5">
                                                <FormGroup>
                                                    <Label for="complement_requisite">Complemento</Label>
                                                    <Input type="text" name="complement_requisite" id="complement_requisite" value={ this.state.complement_requisite } onChange={(e) => this.handleChange(e, 'complement_requisite')} />
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div>
                                                <span className="mr-2"></span>
                                            </div>
                                        </div>

                                        <div className="row mb-4">
                                            <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                                <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                                <h2 className="mb-0 text-primary">Observações</h2>
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
                                                    <Label for="observacao">Descrição da Observação</Label>
                                                    <textarea className="form-control" rows="5" name="description" id="description" value={ this.state.description } onChange={(e) => this.handleChange(e, 'description')}></textarea>
                                                    <div className="invalid-feedback" ></div>
                                                </FormGroup>
                                            </div>
                                        </div>
                                    </Form>

                                    <Button color="primary" disable={ this.state.disableButton} onClick={() => this.handleSubmit()} >Enviar</Button>

                                </RctCollapsibleCard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
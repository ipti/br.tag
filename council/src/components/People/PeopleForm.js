import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
import FeedbackError from 'Components/Form/FeedbackError';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangePeopleForm } from 'Actions';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';


class PeopleForm extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
    }

    initialState = () =>{
        return {
            ...this.props.formFields
        }
    }

    handleChange = (e, key) => {
        const value = { [key]: e.target.value };
        this.setState({...value}, () => this.props.onChangePeopleForm({...value}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Pessoa">
                        <Form>
                            <Section title="Dados Pessoais" icon="collection-text" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    <FormGroup>
                                        <Label for="child">Nome completo</Label>
                                        <Input invalid={!this.props.errors.name.valid} type="text" name="name" id="name" autoComplete="off" value={this.state.name} onChange={(e) => this.handleChange(e, 'name')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.name.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-4">
                                    <FormGroup>
                                        <Label for="birthday">Nascimento</Label>
                                        <Input invalid={!this.props.errors.birthday.valid} type="text" name="birthday" id="birthday" autoComplete="off" value={this.state.birthday} onChange={(e) => this.handleChange(e, 'birthday')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.birthday.errors} />
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="mother">Mãe</Label>
                                        <Input invalid={!this.props.errors.mother.valid} type="text" name="mother" id="mother" autoComplete="off" value={this.state.mother} onChange={(e) => this.handleChange(e, 'mother')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.mother.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="father">Pai</Label>
                                        <Input invalid={!this.props.errors.father.valid} type="text" name="father" id="father" autoComplete="off" value={this.state.father} onChange={(e) => this.handleChange(e, 'father')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.father.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="sex">Sexo</Label>
                                        <Input invalid={!this.props.errors.sex.valid} type="select" name="sex" id="sex" value={this.state.sex} onChange={(e) => this.handleChange(e, 'sex')} bsSize="lg">
                                            <option value=""></option>
                                            <option value="F">Feminino</option>
                                            <option value="M">Masculino</option>
                                        </Input>
                                        <FeedbackError errors={this.props.errors.sex.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="rg">RG</Label>
                                        <Input invalid={!this.props.errors.rg.valid} type="text" name="rg" id="rg" autoComplete="off" value={this.state.rg} onChange={(e) => this.handleChange(e, 'rg')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.rg.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="cpf">CPF</Label>
                                        <Input invalid={!this.props.errors.cpf.valid} type="text" name="cpf" id="cpf" autoComplete="off" value={this.state.cpf} onChange={(e) => this.handleChange(e, 'cpf')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.cpf.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="civilStatus">Estado Civil</Label>
                                        <Input invalid={!this.props.errors.civilStatus.valid} type="select" name="civilStatus" id="civilStatus" value={this.state.civilStatus} onChange={(e) => this.handleChange(e, 'civilStatus')} bsSize="lg">
                                            <option value=""></option>
                                            <option value="solteiro">Solteiro</option>
                                            <option value="casado">Casado</option>
                                            <option value="divorciado">Divorciado</option>
                                            <option value="separado">Separado</option>
                                            <option value="viuvo">Viúvo</option>
                                        </Input>
                                        <FeedbackError errors={this.props.errors.civilStatus.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="profession">Profissão</Label>
                                        <Input invalid={!this.props.errors.profession.valid} type="text" name="profession" id="profession" autoComplete="off" value={this.state.profession} onChange={(e) => this.handleChange(e, 'profession')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.profession.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="scholarity">Escolaridade</Label>
                                        <Input invalid={!this.props.errors.scholarity.valid} type="text" name="scholarity" id="scholarity" autoComplete="off" value={this.state.scholarity} onChange={(e) => this.handleChange(e, 'scholarity')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.scholarity.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="placeBirthday">Local de Nascimento</Label>
                                        <Input invalid={!this.props.errors.placeBirthday.valid} type="text" name="placeBirthday" id="placeBirthday" autoComplete="off" value={this.state.placeBirthday} onChange={(e) => this.handleChange(e, 'placeBirthday')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.placeBirthday.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="nacionality">Nacionalidade</Label>
                                        <Input invalid={!this.props.errors.nacionality.valid} type="text" name="nacionality" id="nacionality" autoComplete="off" value={this.state.nacionality} onChange={(e) => this.handleChange(e, 'nacionality')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.nacionality.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <Section title="Endereço" icon="collection-text" />

                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="street">Rua/Av.</Label>
                                        <Input type="text" invalid={!this.props.errors.street.valid} name="street" id="street" autoComplete="off" value={this.state.street} onChange={(e) => this.handleChange(e, 'street')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.street.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="number">Número</Label>
                                        <Input invalid={!this.props.errors.number.valid} type="text" name="number" id="number" autoComplete="off" value={this.state.number} onChange={(e) => this.handleChange(e, 'number')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.number.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="complement">Complemento</Label>
                                        <Input type="text" invalid={!this.props.errors.complement.valid} name="complement" id="complement" autoComplete="off" value={this.state.complement} onChange={(e) => this.handleChange(e, 'complement')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.complement.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="neighborhood">Bairro</Label>
                                        <Input invalid={!this.props.errors.neighborhood.valid} type="text" name="neighborhood" id="neighborhood" autoComplete="off" value={this.state.neighborhood} onChange={(e) => this.handleChange(e, 'neighborhood')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.neighborhood.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="zip">CEP</Label>
                                        <Input invalid={!this.props.errors.zip.valid} type="text" name="zip" id="zip" autoComplete="off" value={this.state.zip} onChange={(e) => this.handleChange(e, 'zip')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.zip.errors} />
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="city">Cidade</Label>
                                        <Input invalid={!this.props.errors.city.valid} type="text" name="city" id="city" autoComplete="off" value={this.state.city} onChange={(e) => this.handleChange(e, 'city')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.city.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="state">Estado</Label>
                                        <Input invalid={!this.props.errors.state.valid} type="text" name="state" id="state" autoComplete="off" value={this.state.state} onChange={(e) => this.handleChange(e, 'state')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.state.errors} />
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="country">País</Label>
                                        <Input invalid={!this.props.errors.country.valid} type="text" name="country" id="country" autoComplete="off" value={this.state.country} onChange={(e) => this.handleChange(e, 'country')} bsSize="lg" />
                                        <FeedbackError errors={this.props.errors.country.errors} />
                                    </FormGroup>
                                </div>
                            </div>
                            <Button color="primary" onClick={ () => this.handleSave()} >Salvar</Button>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}

PeopleForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ people }) => {
    return people;
 };

 export default connect(mapStateToProps, {onChangePeopleForm})(PeopleForm);
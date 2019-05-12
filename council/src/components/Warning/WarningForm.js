import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeWarningForm } from 'Actions';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';


class WarningForm extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
    }

    initialState = () =>{
        return {
            ...this.props.formFields,
        }
    }

    handleChange = (inputValue, key) => {
        const value = { [key]: inputValue };
        this.setState({...value}, () => this.props.onChangeWarningForm({...value}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Advertência">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-6 warning-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="warning-label text-ellipsis"> Informe o Adolescente</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.personAdolescent} handleChange={(value) => this.handleChange(value, 'personAdolescent')} />
                                        <Input type="hidden" invalid={!this.props.errors.personAdolescent.valid} />
                                        <FeedbackError errors={this.props.errors.personAdolescent.errors} />
                                    </FormGroup>
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="warning-label text-ellipsis"> Informe o representante</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.personRepresentative} handleChange={(value) => this.handleChange(value, 'personRepresentative')} />
                                        <Input type="hidden" invalid={!this.props.errors.personRepresentative.valid} />
                                        <FeedbackError errors={this.props.errors.personRepresentative.errors} />
                                    </FormGroup>
                                    <FormGroup>
                                        <h4 className="warning-label text-ellipsis">
                                            <Label for="reason">Motivo</Label>
                                        </h4>
                                        <Input invalid={!this.props.errors.reason.valid} type="textarea" name="reason" id="reason" value={this.state.reason} onChange={(e) => this.handleChange(e.target.value, 'reason')} />
                                        <FeedbackError errors={this.props.errors.reason.errors} />
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

WarningForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ warning }) => {
    return warning;
 };

 export default connect(mapStateToProps, {onChangeWarningForm})(WarningForm);
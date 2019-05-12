import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeServiceForm } from 'Actions';
import {
    Button,
    Form,
    FormGroup,
    Label,
    Input
} from 'reactstrap';


class ServiceForm extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
    }

    initialState = () =>{
        return {
            ...this.props.formFields,
        }
    }

    handleChange = (e, key) => {
        const value = { [key]: e.target.value };
        this.setState({...value}, () => this.props.onChangeServiceForm({...value}));
    }

    handleChangeSelect = (value) =>{
        const data = { _id: value };
        this.setState({...data}, () => this.props.onChangeServiceForm({...data}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Requisição de Serviço">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-6 notification-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="notification-label text-ellipsis"> Req. de Serviço </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state._id} handleChange={this.handleChangeSelect} />
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

ServiceForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ service }) => {
    return service;
};

export default connect(mapStateToProps, {onChangeServiceForm})(ServiceForm);
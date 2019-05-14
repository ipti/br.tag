import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeNotificationForm } from 'Actions';
import { formatTime, formatDate } from 'Helpers/formats';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';


class NotificationForm extends Component{

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
        this.setState({...value}, () => this.props.onChangeNotificationForm({...value}));
    }

    handleChangeSelect = (value) =>{
        const data = { notified: value };
        this.setState({...data}, () => this.props.onChangeNotificationForm({...data}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Notificação">
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
                                                <h4 className="notification-label text-ellipsis"> Informe o Notificado</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.notified} handleChange={this.handleChangeSelect} />
                                        <FeedbackError errors={this.props.errors.notified.errors} />
                                    </FormGroup>

                                    <div className="row">
                                        <div className="col-sm-12 col-md-8">
                                            <FormGroup>
                                                <Label for="child">Dia</Label>
                                                <Input invalid={!this.props.errors.date.valid} type="text" name="date" id="date" autoComplete="off" maxLength={10} value={this.state.date} onChange={(e) => this.handleChange(e, 'date')} onInput ={ (e) => e.target.value = formatDate(e.target.value)} bsSize="lg" />
                                                <FeedbackError errors={this.props.errors.date.errors} />
                                            </FormGroup>
                                        </div>
                                        <div className="col-sm-6 col-md-4">
                                            <FormGroup>
                                                <Label for="birthday">Hora</Label>
                                                <Input invalid={!this.props.errors.time.valid} type="text" name="time" id="time" autoComplete="off" maxLength={5} value={this.state.time} onChange={(e) => this.handleChange(e, 'time')} onInput ={ (e) => e.target.value = formatTime(e.target.value)} bsSize="lg" />
                                                <FeedbackError errors={this.props.errors.time.errors} />
                                            </FormGroup>
                                        </div>
                                    </div>
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

NotificationForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ notification }) => {
    return notification;
 };

 export default connect(mapStateToProps, {onChangeNotificationForm})(NotificationForm);
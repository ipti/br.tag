import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeAttendanceForm } from 'Actions';
import { formatTime, formatDate } from 'Helpers/formats';
import {
	Button,
	Form,
	FormGroup,
    Input,
    Label
} from 'reactstrap';


class AttendanceForm extends Component{

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
        this.setState({...value}, () => this.props.onChangeAttendanceForm({...value}));
    }

    handleChangeSelect = (value, key) =>{
        const data = { [key]: value };
        this.setState({...data}, () => this.props.onChangeAttendanceForm({...data}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        const types = this.props.types || [];
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Novo Atendimento">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-8 attendance-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />

                                    <div className="row">
                                        <div className="col-sm-12">
                                            <FormGroup>
                                                <Label for="place">Local</Label>
                                                <Input invalid={!this.props.errors.place.valid} type="text" name="place" id="place" autoComplete="off" maxLength={120} value={this.state.place} onChange={(e) => this.handleChange(e, 'place')} bsSize="lg" />
                                                <FeedbackError errors={this.props.errors.place.errors} />
                                            </FormGroup>
                                        </div>
                                    </div>

                                    <div className="row">
                                        <div className="col-sm-12">
                                            <FormGroup>
                                                <Label for="type">Tipo de Atendimento</Label>
                                                <Input invalid={!this.props.errors.type.valid} type="select" name="type" id="type" autoComplete="off" maxLength={120} value={this.state.type} onChange={(e) => this.handleChange(e, 'type')} bsSize="lg" >
                                                    <option>Selecione...</option>
                                                    {
                                                        types.map((type, index) => {
                                                            return <option key={index} value={type.id}>{type.description}</option>
                                                        })
                                                    }
                                                </Input>
                                                <FeedbackError errors={this.props.errors.type.errors} />
                                            </FormGroup>
                                        </div>
                                    </div>

                                    <div className="row">
                                        <div className="col-sm-12 col-md-8">
                                            <FormGroup>
                                                <Label for="date">Data</Label>
                                                <Input invalid={!this.props.errors.date.valid} type="text" name="date" id="date" autoComplete="off" maxLength={10} value={this.state.date} onChange={(e) => this.handleChange(e, 'date')} onInput ={ (e) => e.target.value = formatDate(e.target.value)} bsSize="lg" />
                                                <FeedbackError errors={this.props.errors.date.errors} />
                                            </FormGroup>
                                        </div>
                                        <div className="col-sm-6 col-md-4">
                                            <FormGroup>
                                                <Label for="time">Hora</Label>
                                                <Input invalid={!this.props.errors.time.valid} type="text" name="time" id="time" autoComplete="off" maxLength={5} value={this.state.time} onChange={(e) => this.handleChange(e, 'time')} onInput ={ (e) => e.target.value = formatTime(e.target.value)} bsSize="lg" />
                                                <FeedbackError errors={this.props.errors.time.errors} />
                                            </FormGroup>
                                        </div>
                                    </div>

                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="attendance-label text-ellipsis"> Informe a Pessoa</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <FormGroup>
                                        <PeopleSelect value={this.state.person} handleChange={(value) => this.handleChangeSelect(value, 'person')} />
                                        <Input type="hidden" invalid={!this.props.errors.person.valid} />
                                        <FeedbackError errors={this.props.errors.person.errors} />
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

AttendanceForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ attendance }) => {
    return attendance;
 };

 export default connect(mapStateToProps, {onChangeAttendanceForm})(AttendanceForm);
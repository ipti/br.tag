import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeHousingForm } from 'Actions';
import Select from 'react-select'
import { EditorState } from 'draft-js';
import { Editor } from 'react-draft-wysiwyg';
import {
	Button,
	Form,
	FormGroup,
	Input
} from 'reactstrap';
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css';


class HousingForm extends Component{

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
        this.setState({...value}, () => this.props.onChangeHousingForm({...value}));
    }

    handleChangeSelect = (value, key) =>{
        const data = { [key]: value };
        this.setState({...data}, () => this.props.onChangeHousingForm({...data}));
    }

    handleChangeEditor = (value, key) =>{
        const data = { [key]: value };
        this.setState({...data}, () => this.props.onChangeHousingForm({...data}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        const senders = [
            { label: localStorage.getItem('institution_type'), value: localStorage.getItem('institution')}
        ];

        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Termo de Abrigamento">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-6 housing-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="housing-label text-ellipsis"> Informe a Criança</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.child} handleChange={(value) => this.handleChangeSelect(value, 'child')} />
                                        <FeedbackError errors={this.props.errors.child.errors} />
                                    </FormGroup>

                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="housing-label text-ellipsis"> Informe o Destinatário</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.receiver} handleChange={(value) => this.handleChangeSelect(value, 'receiver')} />
                                        <FeedbackError errors={this.props.errors.receiver.errors} />
                                    </FormGroup>

                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="housing-label text-ellipsis"> Informe o Remetente</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <FormGroup>
                                        <Select placeholder="Selecione o remetente" name="sender" id="sender"  defaultValue={this.state.sender} onChange={(value) => this.handleChangeSelect(value, 'sender')} options={senders} />
                                        <FeedbackError errors={this.props.errors.sender.errors} />
                                    </FormGroup>

                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="w-100">
                                                <h4 className="housing-label text-ellipsis"> Informe o Motivo</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <FormGroup>
                                        <Editor
                                            editorState={this.state.motive}
                                            toolbarClassName="toolbarClassName"
                                            wrapperClassName="wrapperClassName"
                                            editorClassName="housing-editor"
                                            onEditorStateChange={(e) => this.handleChangeEditor(e, 'motive')}
                                            toolbar={{
                                                options: ['inline', 'fontSize', 'colorPicker', 'list', 'textAlign', 'link', 'image', 'history'],
                                                inline: { inDropdown: false },
                                                list: { inDropdown: true },
                                                textAlign: { inDropdown: true },
                                                link: { inDropdown: true },
                                                history: { inDropdown: false }
                                            }}
                                            wrapperStyle={{
                                                'border': '1px solid #C5C5C5',
                                                'borderRadius': '3px'
                                            }}
                                            toolbarStyle={{
                                                'border': 'none',
                                                'borderBottom': '1px solid #C5C5C5'
                                            }}
                                            editorStyle={{
                                                'padding': '10px'
                                            }}
                    
                                        />
                                        <div className="invalid-feedback" ></div>
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

HousingForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ housing }) => {
    return housing;
 };

 export default connect(mapStateToProps, {onChangeHousingForm})(HousingForm);
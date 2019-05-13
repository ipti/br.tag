import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PeopleSelect from 'Components/People/PeopleSelect';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeFoodForm } from 'Actions';
import { Editor } from 'react-draft-wysiwyg';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';


class FoodForm extends Component{

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
        this.setState({...value}, () => this.props.onChangeFoodForm({...value}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro Ação de alimentos">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-6 food-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="food-label text-ellipsis"> Informe o requerente</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.personApplicant} handleChange={(value) => this.handleChange(value, 'personApplicant')} />
                                        <Input type="hidden" invalid={!this.props.errors.personApplicant.valid} />
                                        <FeedbackError errors={this.props.errors.personApplicant.errors} />
                                    </FormGroup>
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="food-label text-ellipsis"> Informe o representante</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.personRepresentative} handleChange={(value) => this.handleChange(value, 'personRepresentative')} />
                                        <Input type="hidden" invalid={!this.props.errors.personRepresentative.valid} />
                                        <FeedbackError errors={this.props.errors.personRepresentative.errors} />
                                    </FormGroup>
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <h4 className="food-label text-ellipsis"> Informe o requerido</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <PeopleSelect value={this.state.personRequired} handleChange={(value) => this.handleChange(value, 'personRequired')} />
                                        <Input type="hidden" invalid={!this.props.errors.personRequired.valid} />
                                        <FeedbackError errors={this.props.errors.personRequired.errors} />
                                    </FormGroup>
                                    <FormGroup>
                                        <h4 className="food-label text-ellipsis">
                                            <Label for="reason">Motivo</Label>
                                        </h4>
                                        <Editor
                                            editorState={this.state.reason}
                                            toolbarClassName="toolbarClassName"
                                            wrapperClassName="wrapperClassName"
                                            editorClassName="food-editor"
                                            onEditorStateChange={(value) => this.handleChange(value, 'reason')}
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
                                        <Input type="hidden" invalid={!this.props.errors.reason.valid} />
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

FoodForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ food }) => {
    return food;
 };

 export default connect(mapStateToProps, {onChangeFoodForm})(FoodForm);
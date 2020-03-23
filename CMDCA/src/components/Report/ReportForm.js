import React, { Component } from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import FeedbackError from 'Components/Form/FeedbackError';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { onChangeReportForm } from 'Actions';
import { Editor } from 'react-draft-wysiwyg';

import {
    Button,
    Form,
    FormGroup,
    Label,
    Input
} from 'reactstrap';


class ReportForm extends Component{

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
        this.setState({...value}, () => this.props.onChangeReportForm({...value}));
    }

    handleChangeEditor = (value, key) =>{
        const data = { [key]: value };
        this.setState({...data}, () => this.props.onChangeReportForm({...data}));
    }

    handleSave(){
        this.props.save();
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro de Relatório">
                        <Form>
                            <div className="row justify-content-center">
                                <div className="col-sm-12 col-md-10 report-container-select">
                                    <Input type="hidden" name="id" id="id" autoComplete="off" value={this.state._id} />
                                    
                                    <div className="row mx-0 mb-3">
                                        <div className="col-7 px-0 d-flex align-items-center">
                                            <div className="w-100">
                                                <h4 className="report-label text-ellipsis"> Descrição </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <FormGroup>
                                        <Editor
                                            editorState={this.state.description}
                                            toolbarClassName="toolbarClassName"
                                            wrapperClassName="wrapperClassName"
                                            editorClassName="report-editor"
                                            onEditorStateChange={(e) => this.handleChangeEditor(e, 'description')}
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
                                        <Input type="hidden" invalid={!this.props.errors.description.valid} />
                                        <FeedbackError errors={this.props.errors.description.errors} />
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

ReportForm.propTypes = {
    save: PropTypes.func.isRequired,
    errors: PropTypes.object.isRequired
};

const mapStateToProps = ({ report }) => {
    return report;
};

export default connect(mapStateToProps, {onChangeReportForm})(ReportForm);
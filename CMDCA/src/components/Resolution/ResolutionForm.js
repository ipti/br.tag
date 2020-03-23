import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
import { Editor } from 'react-draft-wysiwyg';
import { EditorState, ContentState, convertToRaw } from 'draft-js';
import PropTypes from 'prop-types';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';


export default class ResolutionForm extends Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.state={
            title:'',
            text_resolution:EditorState.createEmpty(),
            content_text:ContentState.toString(),
            nameResponsable:'',
            office:'',
            date:''
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }
    handleChangeEditor = (value,key) =>{
        this.setState({[key]:value})
    }

    componentDidMount(){
        let dt = new Date();
        let dt_formated=dt.getDate()+"/"+dt.getMonth()+"/"+dt.getFullYear();
        this.setState({date: dt_formated});
    }

    handleClickTest = (event) =>{
        let dt = new Date();
        let dt_formated=dt.getDate()+"/"+dt.getMonth()+"/"+dt.getFullYear();
        this.setState({date: dt_formated});
        let contentState = this.state.text_resolution.getCurrentContent();
        let note = {content: convertToRaw(contentState)};
        note["content"] = JSON.stringify(note.content);
        alert(this.state.date);
        console.log(dt.getDate()+"/"+dt.getMonth()+"/"+dt.getFullYear());
        console.log(this.state.date)
        console.log(note.content);
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro de resolução">
                        <Form>
                            <Section title="Dados da Resolução:" icon="collection-text" />
                            <div className="row">
                                <div className="col-sm-12 col-md-12">
                                    <FormGroup>
                                        <Label for="title">Título da resolução:</Label>
                                        <Input type="text"  name="title" id="title" autoComplete="off" bsSize="lg" value={this.state.title} onChange={(e) => this.handleChange(e, 'title')}/>
                                    </FormGroup>
                                </div>
                            </div>    
                            <FormGroup>
                                <h4 className="warning-label text-ellipsis">
                                     <Label for="text_resolution">Texto da resolução:</Label>
                                </h4>
                                <Input type="hidden"  />
                                <Editor
                                    editorState={this.state.text_resolution}
                                    onEditorStateChange={(value) => this.handleChangeEditor(value, 'text_resolution')}   
                                    editorClassName="warning-editor"
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
                                        
                            </FormGroup>
                            <Section title="Responsável:" icon="account-box" />
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="person-responsable">Responsável pela assinatura:</Label>
                                        <Input type="text"  name="person-responsable" id="person-responsable" autoComplete="off" bsSize="lg" value={this.state.nameResponsable} onChange={(e) => this.handleChange(e, 'nameResponsable')}/>
                                        
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="number">Cargo:</Label>
                                        <Input type="text" name="number" id="number" autoComplete="off" bsSize="lg" value={this.state.office} onChange={(e) => this.handleChange(e, 'office')}/>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-2">
                                    <Button color="success" onClick={this.handleClickTest}>Publicar</Button>
                                </div>
                                <div className="col-sm-12 col-md-2">
                                    <Button color="orange">Limpar</Button>
                                </div>
                            </div>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}

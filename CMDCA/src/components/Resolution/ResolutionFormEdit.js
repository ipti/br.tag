import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
import api from '../../api/index.js';
import successalert from '../../util/successalert';
import catcherror from '../../util/catcherror';

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
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.state={
            title:'',
            responsable:'',
            office:'',
            file:'',
            filename:'',
            load:false,
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }

    componentDidMount(){
        this.load();
    }

    load = async () => {
        this.setState({load:true});
        await api.get(`/resolution/${this.props.match.params.resolutionID}`).then((response)=>{
            this.setState({
                title:response.data.resolution.title,
                responsable:response.data.resolution.responsable,
                office:response.data.resolution.office,
                load: false
            });
        }).catch((error)=>{
            catcherror(error);
        });    
    };

    handleSubmit = async()=>{
        this.setState({load:true});
        const data = {
            title: this.state.title,
            responsable: this.state.responsable,
            office: this.state.office,
        }
        await api.put(`/resolution/${this.props.match.params.resolutionID}`,data).then(()=>{
            successalert();
            this.clearState();
            this.props.history.push('/app/resolution/list');
        })
        .catch((error)=>{
            this.clearState();
            catcherror(error);
        });
        
      }

    clearState = () =>{
        this.setState({
            title:'',
            responsable:'',
            office:'',
            filename:'',
            file:'',
            load:false,
        });
    }

    redirect = () =>{
        this.clearState();
        this.props.history.push('/app/resolution/list');
    }

    onChangeHandler=(event)=>{
        let file = event.target.files[0];
        this.setState({
            file: file
        });
        
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
                                {/*<div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Selecionar arquivo da resolução:</h4></Label>
                                    <Input type="file" name="advisorImage" id="advisorImage" value={this.state.image} onChange={(e) => this.handleChange(e, 'image')}/>
                                    <input type="file" name="file" placeholder="Selecione um documento" onChange={this.onChangeHandler}/>
                                </div>*/}
                            </div>    
                            
                            <Section title="Responsável:" icon="account-box" />
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="person-responsable">Responsável pela assinatura:</Label>
                                        <Input type="text"  name="person-responsable" id="person-responsable" autoComplete="off" bsSize="lg" value={this.state.responsable} onChange={(e) => this.handleChange(e, 'responsable')}/>
                                        
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
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Publicando...':'Publicar'}</Button>
                                </div>
                                <div className="col-sm-12 col-md-2">
                                    <Button color="secondary" disabled={this.state.load} onClick={this.redirect}>Voltar</Button>
                                </div>
                            </div>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}

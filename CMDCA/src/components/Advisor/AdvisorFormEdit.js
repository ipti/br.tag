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
    Input,
} from 'reactstrap';


export default class AdvisorForm extends Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.redirect=this.redirect.bind(this);
        this.state={
            name:"",
            about:"",
            action:"",
            contact:"",
            load:false
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }
    
    async componentDidMount(){
        this.load();
    }

    load = async () => {
        this.setState({load:true});
        await api.get(`/advisor/${this.props.match.params.advisorID}`).then((response)=>{
            console.log(response);
            this.setState({
                name:response.data.advisor.name,
                about:response.data.advisor.about,
                action:response.data.advisor.action,
                contact:response.data.advisor.contact,
                load: false,
            });
        }).catch((error)=>{
            console.log(error.response)
            catcherror(error);
        });    
    };

    handleSubmit = async()=>{
        this.setState({load:true});
        const data = {
            name:this.state.name,
            action:this.state.action,
            about:this.state.about,
            contact:this.state.contact
        };
        await api.put(`/advisor/${this.props.match.params.advisorID}`,data).then((response)=>{
            successalert();
            this.clearState();
            this.props.history.push('/app/advisor/list');
        })
        .catch((error)=>{
            this.clearState();
            catcherror(error);
        });
        
    };

    clearState = () =>{
        this.setState({
            file:"",
            name:"",
            about:"",
            action:"",
            contact:"",
            load:false,
        });
    }

    redirect = () =>{
        this.clearState();
        this.props.history.push('/app/advisor/list');
    }

    
    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro de Conselheiro">
                        <Form>
                            <Section title="Dados do conselheiro:" icon="account-box" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Nome do conselheiro:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.name} onChange={(e) => this.handleChange(e, 'name')}/>
                                    </FormGroup>
                                </div>
                                
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="function"><h4>Função:</h4></Label>
                                        <Input type="text"  name="function" id="action" autoComplete="off" bsSize="lg" value={this.state.action} onChange={(e) => this.handleChange(e, 'action')}/>   
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="contact"><h4>Contato:</h4></Label>
                                        <Input type="text" name="contact" id="contact" autoComplete="off" bsSize="lg" value={this.state.contact} onChange={(e) => this.handleChange(e, 'contact')}/>
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-12">
                                        <FormGroup>
                                            <Label for="advisor-resume"><h4>Sobre o conselheiro:</h4></Label>
                                            <Input type="textarea"  name="advisor-resume" id="advisor-resume" autoComplete="off"  value={this.state.about} onChange={(e) => this.handleChange(e, 'about')}/>   
                                        </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Cadastrando...':'Cadastrar'}</Button>
                                </div>
                                <div className="col-sm-12 col-md-2 p-1">
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

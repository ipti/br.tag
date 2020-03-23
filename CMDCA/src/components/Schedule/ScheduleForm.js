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


export default class ScheduleForm extends Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.state={
            name:'',
            hour:'',
            date:'',
            local:'',
            load:false,
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }
    
    handleClickTest = (event) =>{
        alert(this.state.date,this.state.hour);
    }

    clearState = () =>{
        this.setState({
            name:'',
            date:'',
            hour:'',
            location:'',
            load:false,
        });
    }

    async handleSubmit(){
        this.setState({load:true});
        const data = {
            name:this.state.name,
            date:this.state.date,
            hour:this.state.hour,
            location:this.state.location,
        }
        await api.post('/events',data).then(()=>{
            successalert();
          this.clearState();
        })
        .catch((error)=>{
            catcherror(error);
            this.setState({
                load:false
            });
        })
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Cadastro de Evento">
                        <Form>
                            <Section title="Dados do Evento:" icon="collection-text" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Nome do evento:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.name} onChange={(e) => this.handleChange(e, 'name')}/>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="date"><h4>Data:</h4></Label>
                                        <Input type="date"  name="date" id="date" autoComplete="off" bsSize="lg" value={this.state.date} onChange={(e) => this.handleChange(e, 'date')}/>   
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="hour"><h4>Hora:</h4></Label>
                                        <Input type="time" name="hour" id="hour" autoComplete="off" bsSize="lg" value={this.state.hour} onChange={(e) => this.handleChange(e, 'hour')}/>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="local"><h4>Local:</h4></Label>
                                        <Input type="text" name="local" id="local" autoComplete="off" bsSize="lg" value={this.state.location} onChange={(e) => this.handleChange(e, 'location')}/>
                                    </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Cadastrando...':'Cadastrar'}</Button>
                                </div>
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="orange" disabled={this.state.load} onClick={this.clearState.bind(this)}>Limpar</Button>
                                </div>
                            </div>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}

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
        this.redirect=this.redirect.bind(this);
        this.state={
            name:'',
            hour:'',
            date:'',
            location:'',
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
        await api.get(`/events/${this.props.match.params.scheduleID}`).then((response)=>{
            this.setState({
                name:response.data.event.name,
                hour:response.data.event.hour,
                location:response.data.event.location,
                date:response.data.event.date,
                load: false
            });
        }).catch((error)=>{
            catcherror(error);
        });    
    };

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
        await api.put(`/events/${this.props.match.params.scheduleID}`,data).then(()=>{
            successalert();
            this.clearState();
            this.props.history.push('/app/schedule/list');
        })
        .catch((error)=>{
            catcherror(error);
            this.setState({
                load:false
            });
        })
    }

    redirect = () =>{
        this.clearState();
        this.props.history.push('/app/schedule/list');
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Editar Evento">
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

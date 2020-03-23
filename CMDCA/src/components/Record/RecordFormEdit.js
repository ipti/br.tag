import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
import api from '../../api/index';
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
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.redirect=this.redirect.bind(this);
        this.state={
            file:null,
            files:[],
            title:'',
            filename:'',
            load:false,
            id:'',
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
        await api.get(`/record/${this.props.match.params.recordID}`).then((response)=>{
            this.setState({
                title:response.data.record.title,
                load: false
            });
        }).catch((error)=>{
            catcherror(error);
        });    
    };

    handleSubmit = async()=>{
        this.setState({load:true});
        const data = {
            title: this.state.title
        }
        await api.put(`/record/${this.props.match.params.recordID}`,data).then(()=>{
            successalert();
            this.clearState();
            this.props.history.push('/app/record/list');
        })
        .catch((error)=>{
            //this.clearState();
            catcherror(error);
        });
        
      }

    clearState = () =>{
        this.setState({
            title:'',
            load:false,
        });
    }

    redirect = () =>{
        this.clearState();
        this.props.history.push('/app/record/list');
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
                    <RctCollapsibleCard heading="Editar documento recordiro">
                        <Form>
                            <Section title="Dados do documento:" icon="account-box" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Título do documento:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.title} onChange={(e) => this.handleChange(e, 'title')}/>
                                    </FormGroup>
                                </div>
                                
                                {/*<div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Selecionar documento a ser publicado:</h4></Label>
                                    
                                    <input type="file" name="file" onChange={this.onChangeHandler}/>
                                </div> */}
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Salvando...':'Salvar'}</Button>
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

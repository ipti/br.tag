import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
import api from '../../api/multpart.js';
import successalert from '../../util/successalert';
import catcherror from '../../util/catcherror';

import {
	Button,
	Form,
	FormGroup,
	Label,
    Input,
    Media
} from 'reactstrap';


export default class RecordForm extends Component{
    constructor(props){
        super(props);
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.state={
            file:null,
            files:[],
            title:'',
            filename:'',
            load:false,
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }

    handleChangeFile = (event) => {
        this.setState({file: event.target.files});
    }
    
    handleClickTest = (event) =>{
        alert(this.state.file);
    }

     async handleSubmit(){
        this.setState({load:true});
        const data = new FormData();
        data.append('file',this.state.file);
        data.append('title',this.state.title);
        await api.post('/record',data).then(()=>{
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

    clearState = () =>{
        this.setState({
            file:'',
            title:'',
            load:false,
        });
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
                    <RctCollapsibleCard heading="Adicionar documento recordiro">
                        <Form>
                            <Section title="Dados do documento:" icon="account-box" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Título do documento:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.title} onChange={(e) => this.handleChange(e, 'title')}/>
                                    </FormGroup>
                                </div>
                                
                                <div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Selecionar documento a ser publicado:</h4></Label>
                                    {/*<Input type="file" name="file" id="advisorImage" value={this.state.filename} onChange={this.onChange}/>*/}
                                    <input type="file" name="file" onChange={this.onChangeHandler}/>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Publicando...':'Publicar'}</Button>
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

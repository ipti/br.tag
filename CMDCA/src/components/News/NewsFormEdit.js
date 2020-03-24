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


export default class NewsForm extends Component{
    constructor(props){
        super(props);
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
        this.redirect=this.redirect.bind(this);
        this.state={
            file:'',
            files:[],
            title:'',
            content:'',
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
        await api.get(`/news/${this.props.match.params.newsID}`).then((response)=>{
            this.setState({
                title:response.data.news.title,
                content:response.data.news.content,
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
            content: this.state.content,
        }
        await api.put(`/news/${this.props.match.params.newsID}`,data).then(()=>{
            successalert();
            this.clearState();
            this.props.history.push('/app/news/list');
        })
        .catch((error)=>{
            this.clearState();
            catcherror(error);
        });
        
      }

    clearState = () =>{
        this.setState({
            title:'',
            content:'',
            load:false,
        });
    }

    redirect = () =>{
        this.clearState();
        this.props.history.push('/app/news/list');
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
                <div className="col-sm-12 col-md-12">
                    <RctCollapsibleCard heading="Editar notícia">
                        <Form>
                            <Section title="Dados da notícia:" icon="collection-text" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Título:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.title} onChange={(e) => this.handleChange(e, 'title')}/>
                                    </FormGroup>
                                </div>
                                
                                {/*<div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Adicionar foto da notícia:</h4></Label>
                                    <Input type="file" name="advisorImage" id="advisorImage" value={this.state.image} onChange={(e) => this.handleChange(e, 'image')}/>
                                    <input type="file" name="file" onChange={this.onChangeHandler}/>
                                </div>*/}
                            </div>

                            <div className="row py-3">
                                <div className="col-sm-12 col-md-12">
                                        <FormGroup>
                                            <Label for="advisor-resume"><h4>Conteúdo:</h4></Label>
                                            <Input type="textarea"  name="advisor-resume" id="advisor-resume" autoComplete="off"  value={this.state.content} onChange={(e) => this.handleChange(e, 'content')}/>   
                                        </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" disabled={this.state.load} onClick={this.handleSubmit}>{this.state.load?'Publicando...':'Publicar'}</Button>
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

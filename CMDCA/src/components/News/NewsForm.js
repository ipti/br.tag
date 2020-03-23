import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
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
        this.handleChange = this.handleChange.bind(this);
        this.state={
            title:'',
            text:'',
            data:'',
            image:'',
            imageURL:''
        };
    };

    handleChange = (event,key) => {
        this.setState({ [key]: event.target.value });
    }
    
    handleClickTest = (event) =>{
        alert(this.state.image);
    }

    clearState = () =>{
        this.setState({
            title:'',
            text:'',
            data:'',
            image:'',
            imageURL:''
        });
    }

    render(){
        return(
            <div className="row justify-content-md-center">
                <div className="col-sm-12 col-md-12">
                    <RctCollapsibleCard heading="Cadastro de notícia">
                        <Form>
                            <Section title="Dados da notícia:" icon="collection-text" />
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="name"><h4>Título:</h4></Label>
                                        <Input type="text"  name="name" id="name" autoComplete="off" bsSize="lg" value={this.state.name} onChange={(e) => this.handleChange(e, 'name')}/>
                                    </FormGroup>
                                </div>
                                
                                <div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Adicionar foto da notícia:</h4></Label>
                                    <Input type="file" name="advisorImage" id="advisorImage" value={this.state.image} onChange={(e) => this.handleChange(e, 'image')}/>
                                </div>
                            </div>

                            <div className="row py-3">
                                <div className="col-sm-12 col-md-12">
                                        <FormGroup>
                                            <Label for="advisor-resume"><h4>Texto:</h4></Label>
                                            <Input type="textarea"  name="advisor-resume" id="advisor-resume" autoComplete="off"  value={this.state.description} onChange={(e) => this.handleChange(e, 'description')}/>   
                                        </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success">Publicar</Button>
                                </div>
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="orange" onClick={this.clearState.bind(this)}>Limpar</Button>
                                </div>
                            </div>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}

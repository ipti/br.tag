import React, {Component} from 'react';
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import Section from 'Components/Form/Section';
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
        this.state={
            name:'',
            function:'',
            description:'',
            contact:'',
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
            name:'',
            function:'',
            description:'',
            contact:'',
            image:'',
            imageURL:''
        });
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
                                
                                <div className="col-sm-12 col-md-4">
                                    <Label for="advisorImage"><h4>Adicionar foto do conselheiro:</h4></Label>
                                    <Input type="file" name="advisorImage" id="advisorImage" value={this.state.image} onChange={(e) => this.handleChange(e, 'image')}/>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="function"><h4>Função:</h4></Label>
                                        <Input type="text"  name="function" id="function" autoComplete="off" bsSize="lg" value={this.state.function} onChange={(e) => this.handleChange(e, 'function')}/>   
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
                                            <Input type="textarea"  name="advisor-resume" id="advisor-resume" autoComplete="off"  value={this.state.description} onChange={(e) => this.handleChange(e, 'description')}/>   
                                        </FormGroup>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-12 col-md-2 p-1">
                                    <Button color="success" onClick={this.handleClickTest}>Cadastrar</Button>
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

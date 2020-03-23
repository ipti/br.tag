import React, {Component} from 'react';
import axios from 'axios';
import {Card, Button, CardHeader, CardBody, Alert, CardImg} from 'reactstrap';
import { Receipt } from '@material-ui/icons';


export default class ResolutionList extends Component{
    constructor(props){
        super(props);
        this.state={
            advisor:[],
            results:0,
            carregando:false
        };
    };

    async componentDidMount(){
        this.setState({carregando:true});
        var header = {
            headers: {
                'Content-Type': 'application/json'    
            }
        };
        let url="http://www.mocky.io/v2/5d6677653300005b00449cfa";
        await axios.get(url,header).then((response)=>{
            console.log(response.data)
            this.setState({
                advisor:response.data.advisor,
                results: response.data.advisor.length,
                carregando: false
            });
        });
    }
    render(){
        
        return(
            <div>
                <h1>Conselheiros</h1>
                {this.state.results===0 && this.state.carregando===false?
                <Alert color="danger">
                    Nenhum conselheiro encontrado!
                </Alert>:
                <div className="row">
                    {this.state.advisor.map(advisor => (
                        <div className="col-sm-12 col-md-4 p-2" key={advisor.id}>
                        <Card key={advisor.id} >
                            <CardHeader className="card-title"><h3>{advisor.name}</h3></CardHeader>
                            <CardImg className="img-fluid p-1 w-50 rounded mx-auto d-block" src={advisor.imageURL}/>
                            <CardBody>
                                <p align='Justify'>{advisor.description}</p>
                                <p>
                                    Função: {advisor.fuction}<br/>
                                    Contato: {advisor.contact}
                                </p>
                            </CardBody>
                            <div className="row">
                                <div className="col-sm-12 col-md-6 p-3">
                                    <Button color="orange" size="sm">Editar</Button>
                                </div>
                                <div className="col-sm-12 col-md-6 p-3">
                                    <Button color="danger" size="sm">Remover</Button>
                                </div>
                            </div>    
                        </Card>
                        </div>
                    ))}    
                </div>
                }
            </div>

        );
    }
}
import React, {Component} from 'react';
import axios from 'axios';
import {Card, Button, CardHeader, CardBody, Alert, CardImg} from 'reactstrap';
import { Receipt } from '@material-ui/icons';


export default class NewsList extends Component{
    constructor(props){
        super(props);
        this.state={
            news:[],
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
        let url="http://www.mocky.io/v2/5d691aa13300002700b688bc";
        await axios.get(url,header).then((response)=>{
            console.log(response.data)
            this.setState({
                news:response.data.news,
                results: response.data.news.length,
                carregando: false
            });
        });
    }
    render(){
        
        return(
            <div>
                <h1>Notícias Publicadas:</h1>
                {this.state.results===0 && this.state.carregando===false?
                <Alert color="danger">
                    Nenhuma notícia encontrada!
                </Alert>:
                <div className="row">
                    {this.state.news.map(news => (
                        <div className="col-sm-12 col-md-4 p-2" key={news.id}>
                        <Card key={news.id} >
                            <CardHeader className="card-title"><h3>{news.title}</h3></CardHeader>
                            <CardImg className="img-fluid  w-20 rounded mx-auto d-block" src={news.imageURL}/>
                            <CardBody>
                                <p>
                                    Data: {news.date}
                                </p>
                                
                            </CardBody>
                            <div className="row justify-content-md-center">
                                <div className="col-sm-12 col-md-3 p-1">
                                    <Button color="primary" size="sm">Visualizar</Button>
                                </div>
                                <div className="col-sm-12 col-md-3 p-1">
                                    <Button color="orange" size="sm">Editar</Button>
                                </div>
                                <div className="col-sm-12 col-md-3 p-1">
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
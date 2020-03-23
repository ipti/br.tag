import React, {Component} from 'react';
import axios from 'axios';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import {Card, Button, CardHeader, CardBody, Alert} from 'reactstrap';
import { Receipt } from '@material-ui/icons';
import './style.css';

export default class ResolutionList extends Component{
    constructor(props){
        super(props);
        this.state={
            resolutions:[],
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
        let url="http://www.mocky.io/v2/5d53ff4b2f00004237861362";
        await axios.get(url,header).then((response)=>{
            console.log(response.data)
            this.setState({
                resolutions:response.data.resolutions,
                results: response.data.resolutions.length,
                carregando: false
            });
        });
    }
    render(){
        
        return(
            <div>
                <h1>Resoluções Publicadas</h1>
                {this.state.results===0 && this.state.carregando===false?
                <Alert color="danger">
                    Nenhuma resolução encontrada!
                </Alert>:
                <div className="row">
                    {this.state.resolutions.map(resolutions => (
                        <div className="col-sm-12 col-md-3 p-2" key={resolutions.id}>
                        <Card key={resolutions.id} >
                            <CardHeader className="card-title"><Receipt/> {resolutions.tittle}</CardHeader>
                            <CardBody>
                                <p>Assinada por {resolutions.nameResponsable} nas suas atribuições de {resolutions.office} em {resolutions.date}</p>
                            </CardBody>
                            <div className="row">
                                <div className="col-sm-12 col-md-6 p-3">
                                    <Button color="primary" size="sm">Visualizar</Button>
                                </div>
                                <div className="col-sm-12 col-md-6 p-3">
                                    <Button color="danger" size="sm">Excluir</Button>
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
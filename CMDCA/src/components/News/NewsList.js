import React, {Component} from 'react';
import {Card, Button, CardHeader, CardBody, Alert, CardImg, Row, Col, Label} from 'reactstrap';
import { SkipPrevious, SkipNext, Announcement } from '@material-ui/icons';
import {NavLink} from 'react-router-dom'
import api from '../../api';
import catcherror from '../../util/catcherror';
import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

const alertSW= withReactContent(Swal); 


export default class NewsList extends Component{
    constructor(props){
        super(props);
        this.state={
            news:[],
            results:0,
            totalPages:1,
            currentPage:1,
            lastpage:1,
            carregando:false
        };
    };

    async componentDidMount(){
        this.load();
    }

    load = async (page=1) => {
        this.setState({carregando:true});
        await api.get(`/news/?page=${page}`).then((response)=>{
            this.setState({
                news:response.data.docs,
                currentPage: response.data.page,
                results: response.data.total,
                totalPages: response.data.pages,
                carregando: false
            });
            
        }).catch((error)=>{
            catcherror(error);
        });
        
    };

    //Função para retroceder a página
    prevPage=() =>{
        const {currentPage} =this.state;
        if (currentPage === 1) return;
        const pageNumber = currentPage - 1;
        this.load(pageNumber);
    };
    
    //Função para avançar a página  
    nextPage=() =>{
        const {currentPage, totalPages} =this.state;
        if (currentPage === totalPages) return;
        const pageNumber = currentPage + 1;
        this.load(pageNumber);
    };

    deleteRegister = async (e,id)=>{
        alertSW.fire({
            title: 'Confirma a exclusão deste registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'yes'
          }).then(async (result) => {
            if (result.value) {
                await api.delete(`/news/${id}`)
                .then(()=>{
                    this.load();
                    alertSW.fire({
                        icon: 'success',
                        title: 'Registro deletado com sucesso!',
                        text: ''
                    });
                })
                .catch((err)=>{
                    catcherror(err);
                })
            }
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
                <div >
                    <Row>
                        <Col lg="8" sm="12">
                            <Label><h6>Total de notícias: {this.state.results}</h6></Label>
                        </Col>
                        <Col lg="1" sm="3" xs="3">
                            <Button size="sm" color="primary" onClick={this.prevPage}  disabled={this.state.currentPage===1}><SkipPrevious fontSize="inherit"/></Button>
                        </Col>
                        <Col lg="2" sm="6" xs="6" className="justify-content-center">
                            <Label><center>{this.state.currentPage} de {this.state.totalPages}</center></Label>
                        </Col>
                        <Col lg="1" sm="3" xs="3">
                            <Button size="sm" color="primary" onClick={this.nextPage}  disabled={this.state.currentPage===this.state.totalPages}><SkipNext fontSize="inherit"/></Button>
                        </Col>
                    </Row>
                    <div className="row">
                    {this.state.news.map(news => (
                        <div className="col-sm-12 col-md-4 col-lg-3 p-2" key={news._id}>
                        <Card key={news.id} >
                            <CardHeader className="card-title"><h3><Announcement fontSize="inherit"/> {news.title}</h3></CardHeader>
                            <CardImg className="img-fluid  w-20 rounded mx-auto d-block" src={news.url} style={{maxHeight:'40px'}}/>
                            <CardBody style={{minHeight:'100px'}}>
                                <p>
                                    Conteúdo: {news.content}
                                </p>
                                
                            </CardBody>
                            <div className="row justify-content-md-center" >
                                <div className="col-sm-6 col-md-4 mb-1">
                                    <NavLink to={`form/${news._id}`}><Button color="secondary" block size="sm">Editar</Button></NavLink>
                                </div>
                                <div className="col-sm-6 col-md-4 mb-1">
                                    <Button  color="danger" size="sm" onClick={(e) => this.deleteRegister(e,news._id)}>Excluir</Button>
                                </div>
                            </div>    
                        </Card>
                        </div>
                    ))}    
                </div>
                </div>
                }
            </div>

        );
    }
}
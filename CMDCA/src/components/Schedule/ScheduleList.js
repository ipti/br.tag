import React, {Component} from 'react';
import {Card, Button, CardHeader, CardBody, Alert, Row, Col, Label} from 'reactstrap';
import { SkipPrevious, SkipNext, Event} from '@material-ui/icons';
import {NavLink} from 'react-router-dom';
import api from '../../api/index.js';
import catcherror from '../../util/catcherror';
import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

const alertSW= withReactContent(Swal);


export default class ScheduleList extends Component{
    constructor(props){
        super(props);
        this.state={
            schedule:[],
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
        await api.get(`/events/?page=${page}`).then((response)=>{
            this.setState({
                schedule:response.data.docs,
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
                await api.delete(`/events/${id}`)
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
                <h1>Eventos Agendados:</h1>
                
                {this.state.results===0 && this.state.carregando===false?
                <Alert color="danger">
                    Nenhum evento encontrado!
                </Alert>:
                <div >
                    <Row>
                    <Col lg="8" sm="12">
                        <Label><h6>Total de eventos: {this.state.results}</h6></Label>
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
                    {this.state.schedule.map(schedule => (
                        <div className="col-sm-12 col-md-4 col-lg-3 p-1" key={schedule._id}>
                        <Card key={schedule._id} >
                            <CardHeader className="card-title"><h4><Event/> {schedule.name}</h4></CardHeader>
                            <CardBody>
                                <p>
                                    Local: {schedule.location} <br/>
                                    Horário: {schedule.hour} <br/>
                                    Data: {schedule.date}
                                </p>
                            </CardBody>
                            <div className="row">
                                <div className="col-sm-12 col-md-6 mb-1">
                                    <NavLink to={`form/${schedule._id}`}><Button color="secondary" block size="sm">Editar</Button></NavLink>
                                </div>
                                <div className="col-sm-12 col-md-6 mb-1">
                                    <Button color="danger" size="sm" onClick={(e) => this.deleteRegister(e,schedule._id)}>Excluir</Button>
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
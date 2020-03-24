import React, {Component} from 'react';
import {Button} from 'reactstrap';
import { Group, Reorder,  Assignment, BorderColor, MonetizationOn } from '@material-ui/icons';

export default class Home extends Component{
    redirect=(id)=>{
        switch(id){
            case 1:
                this.props.history.push('/app/schedule/list');
                break;
            case 2:
                this.props.history.push('/app/finances/list');
                break;
            case 3:
                this.props.history.push('/app/record/list');
                break;
            case 4:
                this.props.history.push('/app/notice/list');
                break;
            case 5:
                this.props.history.push('/app/advisor/list');
                break;
            default:
                break;
        }
    }
    render(){
        return(
            <div>
                <center><h1>Bem vindo(a), {localStorage.getItem("user_name")==="Teste Atualizado"?"Usuário":localStorage.getItem("user_name")}!</h1></center>
                <br/>
                <br/>
                <br/>
                <div className="row mb-0">
                    <div className="mx-auto">
                        <img className="img-fluid" src={require("../../assets/img/cartaz.png" )} alt="cartaz"/>
                    </div>
                </div>
                <br/>
                <div className="row justify-content-md-center" >
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="primary" size="sm" onClick={(e) => this.redirect(1)}><Assignment/>Agenda</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="orange" size="sm" onClick={(e) => this.redirect(2)}><MonetizationOn/> Finanças</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="danger" size="sm" onClick={(e) => this.redirect(3)}><BorderColor/> Atas</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="warning" size="sm" onClick={(e) => this.redirect(4)}><Reorder/> Editais</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="success" size="sm" onClick={(e) => this.redirect(5)}><Group/> Conselheiros</Button>
                    </div>
                </div>
                <br/>
            </div>
        );
    }
}
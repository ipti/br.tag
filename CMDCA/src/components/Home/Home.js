import React, {Component} from 'react';
import {Button} from 'reactstrap';
import { Group, Reorder,  Assignment, BorderColor, MonetizationOn } from '@material-ui/icons';

export default class Home extends Component{
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
                        <Button color="primary" size="sm"><Assignment/> Resoluções</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="orange" size="sm"><MonetizationOn/> Finanças</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="danger" size="sm"><BorderColor/> Atas</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="warning" size="sm"><Reorder/> Editais</Button>
                    </div>
                    <div className="col-sm-6 col-md-2 p-1">
                        <Button color="success" size="sm"><Group/> Conselheiros</Button>
                    </div>
                </div>
                <br/>
            </div>
        );
    }
}
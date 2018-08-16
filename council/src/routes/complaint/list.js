/**
* Report Page
*/
import React, { Component } from 'react';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import Hidden from '@material-ui/core/Hidden';
import { NavLink } from 'react-router-dom';

import ComplaintListItem from './components/ComplaintListItem';

const listItems = [
    {
        title: 'Processo nº: 201808161-94',
        description: 'Maus Tratos - Notificado em 22/12/2018 no Conselho Tutelar por José Carlos em favor de Carla Beatriz',
        status: false,
        statusColor: 'red',
        icon: 'zmdi zmdi-eye zmdi-hc-md'
    },
    {
        title: 'Processo nº: 201808162-51',
        description: 'Violência Psicológica - Notificado em 16/08/2018 no Conselho Tutelar por Anônimo em favor de Bill Gates',
        status: true,
        statusColor: 'yellow',
        icon: 'zmdi zmdi-eye zmdi-hc-md'
    },
    {
        title: 'Processo nº: 201808163-72',
        description: 'Trabalho Infantil - Notificado em 12/08/2018 no Conselho Tutelar por Anônimo em favor de Steve Jobs',
        status: false,
        statusColor: 'yellow',
        icon: 'zmdi zmdi-eye zmdi-hc-md'
    },
    {
        title: 'Processo nº: 201808164-32',
        description: 'Negligência- Notificado em 10/08/2018 no Conselho Tutelar por Anônimo em favor de Miguel Falabela',
        status: false,
        statusColor: 'yellow',
        icon: 'zmdi zmdi-eye zmdi-hc-md'
    },
    {
        title: 'Processo nº: 201808166-32',
        description: 'Bullying - Notificado em 09/08/2018 no Conselho Tutelar por Diretor Escolar em favor de Carla Beatriz',
        status: false,
        statusColor: 'green',
        icon: 'zmdi zmdi-eye zmdi-hc-md'
    }
]

export default class ComplaintList extends Component {
	
	constructor(props) {
		super(props);
		this.state = {
			visible: true
		};
		this.onDismiss = this.onDismiss.bind(this);
	}
	onDismiss() {
		this.setState({ visible: false });
	}

	render() {
        const { match, } = this.props;
		return (
			<div className="report-wrapper">
				<div className="page-title d-flex justify-content-between align-items-center">
                    <div className="page-title-wrap">
                        <i className="ti-angle-left"></i>
                        <h2>Denúncias</h2>
                    </div>
                </div>
                    <Hidden mdDown>
                        <div className="mb-30">
                            <div className="row">
                                <div className="col-md-2">
                                    <Button component={NavLink} to={`${match.path.replace("list","")}insert`} variant="contained" className="btn-danger text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-plus-circle mr-10 font-lg"></i>
                                        Nova
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" className="btn-primary text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-assignment-returned mr-10 font-lg"></i>
                                        Recebidas
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" className="btn-warning text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-search mr-10 font-lg"></i>
                                        Em análise
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" className="btn-secondary text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-mail-reply-all mr-10 font-lg"></i>
                                        Direcionadas
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" className="btn-success text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-tab mr-10 font-lg"></i>
                                        Concluídas
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </Hidden>
                    <Hidden smUp>
                        <IconButton component={NavLink} to={`${match.url}/insert`} className="text-danger" aria-label="Nova">
                            <i className="zmdi zmdi-plus-circle"></i>
                        </IconButton>
                        <IconButton className="label-blue" aria-label="Recebidas">
                            <i className="zmdi zmdi-assignment-returned"></i>
                        </IconButton>
                        <IconButton className="label-yellow" aria-label="Em análise">
                            <i className="zmdi zmdi-search"></i>
                        </IconButton>
                        <IconButton className="label-grey" aria-label="Direcionadas">
                            <i className="zmdi zmdi-mail-reply-all"></i>
                        </IconButton>
                        <IconButton className="label-green" aria-label="Concluídas">
                            <i className="zmdi zmdi-tab"></i>
                        </IconButton>
                    </Hidden>

                <ComplaintListItem {...this.props} listItems={listItems} />
                
			</div>
		);
	}
}

/**
 * Complaint View Item
 */
import React, { Component } from 'react';
import { Scrollbars } from 'react-custom-scrollbars';
import { Badge } from 'reactstrap';
import EmailDetail from 'Routes/mail/components/EmailDetail';
import Button from '@material-ui/core/Button';
import TextField from '@material-ui/core/TextField';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogContentText from '@material-ui/core/DialogContentText';
import DialogTitle from '@material-ui/core/DialogTitle';
import { FormGroup, Label, Input } from 'reactstrap';

// rct card box
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

class ComplaintViewItem extends Component {

	state = {
        activities: null,
        pid: null,
        initialDate: null,
        finalDate: null,
        type: null,
        open: false,
        openSend: false,
    };

	handleClickOpen = () => {
		this.setState({ open: true });
	};

	handleClose = () => {
		this.setState({ open: false });
    };
    
	handleClickOpenSend = () => {
		this.setState({ openSend: true });
	};

	handleCloseSend = () => {
		this.setState({ openSend: false });
	};

	componentDidMount() {

        var demo = [
            {
                "id": 1,
                "date": "18 de Julho de às 11:35",
                "activity": "Denúncia recebida por <a href=\"javascript:void(0)\">@MariaSantos<\/a>. <br> Criança de 12 anos sofre constantes agressões pelo seu pai, segundo informações as agressões são muito violentas.",
                "status": "primary"
            },
            {
                "id": 2,
                "date": "19 de Julho de às 11:35",
                "activity": "Início da averiguação por <a href=\"javascript:void(0)\">@Jessica<\/a>",
                "status": "warning"
            },
            {
                "id": 3,
                "date": "20 de Julho de às 11:40",
                "activity": "Emissão de parecer técnico por <a href=\"javascript:void(0)\">@Jessica<\/a>",
                "status": "warning"
            },
            {
                "id": 4,
                "date": "20 de Julho de às 12:40",
                "activity": "Denúncia encaminhada para o Ministério Público",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho de às 12:40",
                "activity": "Início da análise por <a href=\"javascript:void(0)\">@JoaoFontes<\/a>",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho de às 12:40",
                "activity": "Emissão de parecer por <a href=\"javascript:void(0)\">@JoaoFontes<\/a>",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho às 12:42",
                "activity": "Denúncia encaminhada para o Conselho Tutelar",
                "status": "warning"
            },
            {
                "id": 7,
                "date": "21 de Julho às 15:42",
                "activity": "Processo concluído",
                "status": "success"
            }
        ];

        this.setState({ 
            activities: demo,
            initialDate: '18/07/2018',
            finalDate: '30/07/2018',
            type: 'Violência doméstica',
            pid: '1235567',
         });
	}

	render() {
        const { activities } = this.state;
        const isCitizen = (!!this.props.citizen) ? this.props.citizen : false;
		return (
            
            <div className="row">
                {isCitizen === false && <EmailDetail/> }
                <RctCollapsibleCard colClasses="col-md-12">
                <div className="activity-widget">
                    <Scrollbars className="rct-scroll" autoHeight autoHeightMin={120} autoHeightMax={2440} autoHide>
                        <ul className="list-unstyled px-3">
                            {activities && activities.map((activity, key) => (
                                <li key={key}>
                                    <Badge color={activity.status} className="rounded-circle p-0">.</Badge>
                                    <span className="activity-time font-xs text-muted">{activity.date}</span>
                                    <p className="mb-0" dangerouslySetInnerHTML={{ __html: activity.activity }} />
                                </li>
                            ))}
                        </ul>
                    </Scrollbars>
                </div>
            </RctCollapsibleCard >
                {isCitizen === false && <div className="media p-20">
                    <img src={require('Assets/avatars/user-15.jpg')} alt="user profile" className="img-fluid rounded-circle mr-15" width="50" height="50" />
                    <div className="media-body card p-20">
                        <span>Clique aqui para <a href="javascript:void(0)" onClick={this.handleClickOpen}>Responder</a> ou <a href="javascript:void(0)" onClick={this.handleClickOpenSend}>Encaminhar a denúnica</a></span>
                    </div>
                </div>}
                    <Dialog open={this.state.open} onClose={this.handleClose} aria-labelledby="form-dialog-title">
                        <DialogTitle id="form-dialog-title">Responder</DialogTitle>
                        <DialogContent className="w-600">
                            <FormGroup>
                                <Label for="descricao">Descrição dos fatos</Label>
                                <textarea className="form-control" name="descricao" rows="5"></textarea>
                            </FormGroup>
                            <FormGroup>
                                <Label for="arquivos">Arquivos</Label>
                                <Input  style={{paddingLeft: 0}} type="file" multiple="true" name="arquivos" id="arquivos" bsSize="lg" />
                            </FormGroup>
                        </DialogContent>
                        <DialogActions>
                            <Button variant="raised" onClick={this.handleClose} color="primary" className="text-white">
                                Fechar
                            </Button>
                            <Button variant="raised" onClick={this.handleClose} className="btn-info text-white">
                                Enviar
                            </Button>
                        </DialogActions>
                    </Dialog>

                    <Dialog open={this.state.openSend} onClose={this.handleCloseSend} aria-labelledby="form-dialog-title">
                        <DialogTitle id="form-dialog-title">Encaminhar</DialogTitle>
                        <DialogContent className="w-600">
                            <FormGroup>
                                <Label for="sexo">Orgão</Label>
                                <Input type="select" name="orgao" id="orgao" bsSize="lg">
                                    <option value="conselho">Conselho Tutelar</option>
                                    <option value="creas">CREAS</option>
                                    <option value="mp">Ministério Público</option>
                                </Input>
                            </FormGroup>
                            <FormGroup>
                                <Label for="descricao">Descrição dos fatos</Label>
                                <textarea className="form-control" name="descricao" rows="5"></textarea>
                            </FormGroup>
                            <FormGroup>
                                <Label for="arquivos">Arquivos</Label>
                                <Input  style={{paddingLeft: 0}} type="file" multiple="true" name="arquivos" id="arquivos" bsSize="lg" />
                            </FormGroup>
                        </DialogContent>
                        <DialogActions>
                            <Button variant="raised" onClick={this.handleCloseSend} color="primary" className="text-white">
                                Fechar
                            </Button>
                            <Button variant="raised" onClick={this.handleCloseSend} className="btn-info text-white">
                                Enviar
                            </Button>
                        </DialogActions>
                    </Dialog>
            </div>

		);
	}
}

export default ComplaintViewItem;

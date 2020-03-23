/**
 * Complaint View Item
 */
import React, { Component } from 'react';
import { Scrollbars } from 'react-custom-scrollbars';
import { Badge } from 'reactstrap';
import Button from '@material-ui/core/Button';
import Chip from '@material-ui/core/Chip';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogTitle from '@material-ui/core/DialogTitle';
import {Form, FormGroup, Label, Input } from 'reactstrap';
import Anexo from './Anexo';

// rct card box
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

import api from 'Api';

class ComplaintViewItem extends Component {

    constructor(props){
        super(props);
        this.state = this.getInitialState();
    }

    getInitialState(){
        const initialState = {
            activities: [],
            institutions: [],
            institutionsLoaded: false,
            error:[],
            type: null,
            open: false,
            openSend: false,
            user: {},
            complaint: null,
            descriptionResponse: null,
            filesResponse: [],
            disableButtonResponse: false,
            descriptionForward: null,
            filesForward: [],
            placeForward: null,
            disableButtonForward: false
        }
        return initialState;
    }

    handleChange = (e, key) => {
        this.setState({ [key]: e.target.value });
    }

    handleChangeFile(e, key) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
              return;
        
        this.setState({ [key]: [] });
        Object.keys(files).map(index => {
            this.createFile(files[index], key);
        })
    }

    createFile(file, key) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.setState((prev) => ({
            [key]: [...prev[key], {name: file.name, data: e.target.result}]
          }));
        };
        reader.readAsDataURL(file);
    }

    normalizeFieldsResponse = () => {
        var date = new Date();
        var actualDate = `${date.getDate()}/${new String(date.getMonth() + 1).padStart(2,'0')}/${date.getFullYear()}`
        const fields = {
            forwards: [
                {
                    description: this.state.descriptionResponse,
                    date: actualDate,
                    files: this.state.filesResponse,
                    user: this.props.user.id,
                    institution: this.props.user.institution
                }
            ]
        }
        return fields;
    }

    normalizeFieldsForward = () => {
        var date = new Date();
        var actualDate = `${date.getDate()}/${new String(date.getMonth() + 1).padStart(2,'0')}/${date.getFullYear()}`
        const fields = {
            place: this.state.placeForward,
            forwards: [
                {
                    description: this.state.descriptionForward,
                    date: actualDate,
                    files: this.state.filesForward,
                    user: this.props.user.id,
                    institution: this.props.user.institution
                }
            ]
        }
        return fields;
    }

    setError(field, message){
        field.classList.add('is-invalid');
        field.nextSibling.style.display = 'block';
        field.nextSibling.innerHTML = message;
    }

    clearError(field){
        field.classList.remove('is-invalid');
        field.nextSibling.style.display = 'none';
        field.nextSibling.innerHTML = '';
    }

    validate(fields){
        var error = false;
        let el = (id) => document.getElementById(id);
        for (let i = 0; i < fields.length; i++) {
            const element = fields[i];
            if(el(element.field).value == ''){
                this.setError(el(element.field), element.message);
                error = true;
            }
            else{
                this.clearError(el(element.field));
            }
        }

        return !error;
    }
    
    resetStateResponse() {
        this.setState({descriptionResponse: null, filesResponse:[]});
        this.forceUpdate();
        document.getElementById('response-form').reset();
        this.handleClose();
    } 

    resetStateForward() {
        this.setState({descriptionForward: null, filesForward:[]});
        this.forceUpdate();
        document.getElementById('forward-form').reset();
        this.handleCloseSend();
    } 
    
    handleSubmitResponse() {
        let fields = [
            {field: 'descriptionResponse', message: 'Informe o ocorrido'}
        ];
       if(this.validate(fields)) {
           this.setState({ ['disableButtonResponse']: true });

            var header = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            var data = this.normalizeFieldsResponse();

            api.post(`/v1/complaint/response/${this.props.id}`, data, header)
                .then(function(response){
                    if(typeof response.data.status !== 'undefined'){
                        if(response.data.status == '1'){
                            alert(`Resposta cadastrada.`);
                            this.resetStateResponse();
                            this.loadComplaint();
                        }
                        else{
                            alert(response.data.message);
                        }
                    }
                    this.setState({ ['disableButtonResponse']: false });
                }.bind(this))
                .catch(function(error){
                    this.setState({ ['disableButtonResponse']: false });
                    switch (error.response.status) {
                        case 401:
                            alert('Sessão expirada');
                            this.props.history.push('/session/login');
                        break;
                        case 500:
                            alert('Erro ao processar a solicitação');
                        break;
                        default:
                            console.log(error);
                        break;
                    }
            }.bind(this));
        }
    }

    handleSubmitForward() {
        let fields = [
            {field: 'descriptionForward', message: 'Informe o ocorrido'},
            {field: 'placeForward', message: 'Selecione a instituição'}
        ];
       if(this.validate(fields)) {
           this.setState({ ['disableButtonForward']: true });

            var header = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            var data = this.normalizeFieldsForward();

            api.post(`/v1/complaint/forward/${this.props.id}`, data, header)
                .then(function(response){
                    if(typeof response.data.status !== 'undefined'){
                        if(response.data.status == '1'){
                            alert(`Denúncia encaminhada.`);
                            this.resetStateForward();
                            this.loadComplaint();
                        }
                        else{
                            alert(response.data.message);
                        }
                    }
                    this.setState({ ['disableButtonForward']: false });
                }.bind(this))
                .catch(function(error){
                    this.setState({ ['disableButtonForward']: false });
                    switch (error.response.status) {
                        case 401:
                            alert('Sessão expirada');
                            this.props.history.push('/session/login');
                        break;
                        case 500:
                            alert('Erro ao processar a solicitação');
                        break;
                        default:
                            console.log(error);
                        break;
                    }
            }.bind(this));
        }
    }

    handleFinalize() {
        api.post(`/v1/complaint/finalize/${this.props.id}`)
            .then(function(response){
                let data = response.data.data;
                if(response.data.status == '1'){
                    alert('Denúncia finalizada');
                    this.props.history.push(`/app/complaint/list`);
                }
                else{
                    alert('Erro ao finalizar denúncia');
                }
            }.bind(this))
            .catch(function(error){
                this.setState({institutions: []});
                switch (error.response.status) {
                    case 401:
                        alert('Sessão expirada');
                        this.props.history.push('/session/login');
                    break;
                    case 500:
                        alert('Erro ao processar a solicitação');
                    break;
                    default:
                        console.log(error);
                    break;
                }
        }.bind(this));
    }

    handleFormalize() {
        this.props.history.push(`/app/complaint/formalize/${this.props.id}`);
    }

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
    
    loadComplaint(){
        this.setState({complaint: null});
        api.get(`/v1/complaint/${this.props.id}`)
            .then(function(response){
                if(typeof response.data.status !== 'undefined'){
                    if(response.data.status == '1'){
                        let data = response.data.data;
                        let activities = data.forwards;
                        let activitiesWithStatus = [];
                        let institution = this.props.user.institution;
                        let complaintStatus = data.status;

                        activities.map((activity, key) => {
                            if(key == 0){
                                activitiesWithStatus[key]  = {...activities[key], status: 'primary'};
                            }
                            else if((key + 1) != activities.length || ((key + 1) == activities.length && complaintStatus != '9')){
                                if(institution == activity.institution){
                                    activitiesWithStatus[key]  = {...activities[key], status: 'warning'};
                                }
                                else{
                                    activitiesWithStatus[key]  = {...activities[key], status: 'secondary'};
                                }
                            }
                            else{
                                if(complaintStatus == '9'){
                                    activitiesWithStatus[key]  = {...activities[key], status: 'success'};
                                }
                            }
                        });
                        this.setState({['activities']: activitiesWithStatus});
                        this.setState({['complaint']: data});
                    }
                    else{
                        console.log(response.data);
                    }
                }
            }.bind(this))
            .catch(function(error){
                this.setState({institutions: []});
                switch (error.response.status) {
                    case 401:
                        alert('Sessão expirada');
                        this.props.history.push('/session/login');
                    break;
                    case 500:
                        alert('Erro ao processar a solicitação');
                    break;
                    default:
                        console.log(error);
                    break;
                }
        }.bind(this));
    }

    getInstitutions(){
        this.setState({institutions: []});
        api.get(`/v1/institution`)
            .then(function(response){
                this.setState({institutions: response.data});
                this.setState({institutionsLoaded: true});
            }.bind(this))
            .catch(function(error){
                this.setState({institutions: []});
                switch (error.response.status) {
                    case 401:
                        alert('Sessão expirada');
                        this.props.history.push('/session/login');
                    break;
                    case 500:
                        alert('Erro ao processar a solicitação');
                    break;
                    default:
                        console.log(error);
                    break;
                }
        }.bind(this));
    }

	componentDidMount() {
        this.loadComplaint();
        this.getInstitutions();
	}

	render() {
        const { activities, complaint } = this.state;
        const isCitizen = (!!this.props.citizen) ? this.props.citizen : false;
        if (complaint === null) {
			return (
				<RctSectionLoader />
			)
		}
		return (
            
            <div className="row">
                
                <RctCollapsibleCard colClasses="col-md-12">
                    <div className="row">
                        <div className="col-md-4">
                            <h4>Número:</h4> <p>{this.state.complaint._id}</p>
                        </div>
                        <div className="col-md-4">
                            <h4>Local:</h4> <p>{this.state.complaint.place_name}</p>
                        </div>
                        <div className="col-md-4">
                            <h4>Data Abertura:</h4> <p>{this.state.complaint.receive_date}</p>
                        </div>
                    </div>
                    {isCitizen === false && 
                        <React.Fragment>
                            <div className="row">
                                <div className="col-md-12">
                                    <h4>Endereço:</h4> <p>{`${this.state.complaint.address.street}, ${this.state.complaint.address.number}, ${this.state.complaint.address.neighborhood} - ${this.state.complaint.address.city}`}</p>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-md-4">
                                    <h4>Criança:</h4> <p>{this.state.complaint.child.name}</p>
                                </div>
                                <div className="col-md-4">
                                    <h4>Responsável:</h4> <p>{this.state.complaint.responsible}</p>
                                </div>
                                <div className="col-md-4">
                                    <h4>Agressor:</h4> <p>{this.state.complaint.aggressor}</p>
                                </div>
                            </div>
                        </React.Fragment>}
                </RctCollapsibleCard>
                <RctCollapsibleCard colClasses="col-md-12">
                <div className="col-md-12"></div>
                <div className="activity-widget">
                    <Scrollbars className="rct-scroll" autoHeight autoHeightMin={120} autoHeightMax={2440} autoHide>
                        <ul className="list-unstyled px-3">
                            {activities.length > 0 && activities.map((activity, key) => (
                                <li key={key} className="mb-30">
                                    <Badge color={activity.status} className="rounded-circle p-0">.</Badge>
                                    <h4>{activity.date}</h4>
                                    <div className="mt-20 mb-20">
                                        <Chip className="chip-outline-primary mr-10 mb-10" label={activity.institution_name} />
                                        {isCitizen === false && <Chip className="chip-outline-secondary mr-10 mb-10" label={activity.username} />}
                                    </div>
                                    {isCitizen === false && 
                                        <React.Fragment>
                                            <h4>Descrição</h4>
                                            <p className="mb-0 mt-20" dangerouslySetInnerHTML={{ __html: activity.description }} />
                                        </React.Fragment>
                                    }
                                    {isCitizen === false && <Anexo files={activity.files} /> }
                                </li>
                            ))}
                        </ul>
                    </Scrollbars>
                </div>
            </RctCollapsibleCard >
                {isCitizen === false && this.state.complaint.status == 2 && <div className="col-md-12"> <div className="media p-20">
                    <img src={require('Assets/avatars/user-15.jpg')} alt="user profile" className="img-fluid rounded-circle mr-15" width="50" height="50" />
                    <div className="media-body card p-20">
                        <span>Clique aqui para <a href="javascript:void(0)" onClick={this.handleClickOpen}>Responder</a> ou <a href="javascript:void(0)" onClick={this.handleClickOpenSend}>Encaminhar a denúncia</a></span>
                    </div>
                </div></div>}
                    <Dialog open={this.state.open} onClose={this.handleClose} aria-labelledby="form-dialog-title">
                        <DialogTitle id="form-dialog-title">Responder</DialogTitle>
                        <DialogContent className="w-600">
                            <Form id="response-form">
                                <FormGroup>
                                    <Label for="descriptionResponse">Descrição dos fatos</Label>
                                    <textarea className="form-control" name="descriptionResponse" id="descriptionResponse" autoComplete="off" value={this.state.descriptionResponse || ''} onChange={(e) => this.handleChange(e, 'descriptionResponse')} rows="5"></textarea>
                                    <div className="invalid-feedback" ></div>
                                </FormGroup>
                                <FormGroup>
                                    <Label for="filesResponse">Arquivos</Label>
                                    <Input  style={{paddingLeft: 0}} type="file" onChange={(e) => this.handleChangeFile(e, 'filesResponse')} multiple="true" name="filesResponse" id="filesResponse" bsSize="lg" />
                                    <div className="invalid-feedback" ></div>
                                </FormGroup>
                            </Form>
                        </DialogContent>
                        <DialogActions>
                            <Button variant="raised" onClick={this.handleClose} color="primary" className="text-white">
                                Fechar
                            </Button>
                            <Button variant="raised" disabled={this.state.disableButtonResponse} onClick={() => this.handleSubmitResponse()} className="btn-info text-white">
                                Enviar
                            </Button>
                        </DialogActions>
                    </Dialog>

                    <Dialog open={this.state.openSend} onClose={this.handleCloseSend} aria-labelledby="form-dialog-title">
                        <DialogTitle id="form-dialog-title">Encaminhar</DialogTitle>
                        <DialogContent className="w-600">
                            <Form id="forward-form">
                                <FormGroup>
                                    <Label for="placeForward">Orgão</Label>
                                        {this.state.openSend && <Input type="select" name="placeForward" id="placeForward" onChange={(e) => this.handleChange(e, 'placeForward')} bsSize="lg">
                                        <option value="">Selecione...</option>
                                        {this.state.institutions.map(institution => 
                                            <option value={institution._id}>{institution.name}</option>
                                        )}
                                    </Input>}
                                    <div className="invalid-feedback" ></div>
                                </FormGroup>
                                <FormGroup>
                                    <Label for="descriptionForward">Descrição dos fatos</Label>
                                    <textarea className="form-control" name="descriptionForward" id="descriptionForward" autoComplete="off" value={this.state.descriptionForward || ''} onChange={(e) => this.handleChange(e, 'descriptionForward')} rows="5"></textarea>
                                    <div className="invalid-feedback" ></div>
                                </FormGroup>
                                <FormGroup>
                                    <Label for="filesForward">Arquivos</Label>
                                    <Input  style={{paddingLeft: 0}} type="file" onChange={(e) => this.handleChangeFile(e, 'filesForward')} multiple="true" name="filesForward" id="filesForward" bsSize="lg" />
                                    <div className="invalid-feedback" ></div>
                                </FormGroup>
                        </Form>
                        </DialogContent>
                        <DialogActions>
                            <Button variant="raised" onClick={this.handleCloseSend} color="primary" className="text-white">
                                Fechar
                            </Button>
                            <Button variant="raised" onClick={this.handleCloseSend} onClick={() => this.handleSubmitForward()} className="btn-info text-white">
                                Enviar
                            </Button>
                        </DialogActions>
                    </Dialog>

                    {isCitizen === false && this.props.user.institutionType == 'CONSELHO' && this.state.complaint.status == 1 && 
                        <div className="col-md-2">
                            <Button variant="raised" color="primary"  onClick={() => this.handleFormalize()}>Formalizar Denúncia</Button>
                        </div>
                    }
                    {isCitizen === false && this.props.user.institutionType == 'CONSELHO' && this.state.complaint.status == 2 && 
                        <div className="col-md-2">
                            <Button variant="raised" color="primary"  onClick={() => this.handleFinalize()}>Finalizar Denúncia</Button>
                        </div>
                    }
            </div>

		);
	}
}

export default ComplaintViewItem;

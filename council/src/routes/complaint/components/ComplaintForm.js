/**
 * Complaint Form
 */
import React, { Component } from 'react';
import {
	Button,
	Form,
	FormGroup,
	Label,
	Input,
	Alert,
	Col
} from 'reactstrap';

import FormControlLabel from '@material-ui/core/FormControlLabel';
import FormControl from '@material-ui/core/FormControl';
import Radio from '@material-ui/core/Radio';
import RadioGroup from '@material-ui/core/RadioGroup';
import Select from 'react-select'
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import api from 'Api';
export default class ComplaintForm extends Component {

    constructor(props){
        super(props);
        this.state = this.getInitialState();
    }

    getInitialState() {
        const initialState = {
            id: null,
            child: null,
            age: null,
            sex: null,
            stage: null,
            responsible: null,
            aggressor: null,
            address: {},
            city: null,
            street: null,
            neighborhood: null,
            number: null,
            complement: null,
            type: 'maus_tratos',
            description: null,
            informer: null,
            files: [],
            forwards: [],
            error: [],
            success: [],
            disableButton: false,
            complaintLoaded: false
        };

        return initialState;
    }
    
    resetState(update={}) {
        this.setState({...this.getInitialState(), ...update});
        this.forceUpdate();
        document.forms[0].reset();
    }  

    handleChangeRadio = (e, key) => {
		this.setState({ [key]: e.target.value });
	}

    handleChange = (e, key) => {
        this.setState({ [key]: e.target.value });
    }

    handleChangeFile(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
              return;
        
        this.setState({ files: [] });
        Object.keys(files).map(key => {
            this.createFile(files[key]);
        })
    }

    createFile(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.setState((prev) => ({
            files: [...prev.files, {name: file.name, data: e.target.result}]
          }));
        };
        reader.readAsDataURL(file);
    }
    
    normalizeFields = () => {
        var date = new Date();
        var actualDate = `${date.getDate()}/${new String(date.getMonth() + 1).padStart(2,'0')}/${date.getFullYear()}`
        const fields = {
            child: {
                name: this.state.child,
                age: this.state.age,
                sex: this.state.sex,
                stage: this.state.stage
            },
            responsible: this.state.responsible,
            address: {
                city: this.state.city,
                street: this.state.street,
                number: this.state.number,
                neighborhood: this.state.neighborhood,
                complement: this.state.complement,
            },
            receive_user: sessionStorage.getItem('user'),
            type: this.state.type,
            complement_type: this.state.complement_type,
            informer: this.state.informer,
            place: sessionStorage.getItem('institution'),
            forwards: [
                {
                    description: this.state.description,
                    date: actualDate,
                    files: this.state.files,
                    user: sessionStorage.getItem('user'),
                    institution: sessionStorage.getItem('institution')
                }
            ]
    
        }

        if(this.props.action == 'update'){
            const update = {
                child: {
                    name: this.state.child,
                    age: this.state.age,
                    sex: this.state.sex,
                    stage: this.state.stage
                },
                responsible: this.state.responsible,
                address: {
                    city: this.state.city,
                    street: this.state.street,
                    number: this.state.number,
                    neighborhood: this.state.neighborhood,
                    complement: this.state.complement,
                },
                receive_user: sessionStorage.getItem('user'),
                type: this.state.type,
                complement_type: this.state.complement_type,
                informer: this.state.informer,
            }
            return update;
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

    validate(){
        var error = false;
        let el = (id) => document.getElementById(id);
        let fields = [
            {field: 'child', message: 'Informe o nome'},
            {field: 'sex', message: 'Informe o sexo'},
            {field: 'age', message: 'Informe a idade'},
            {field: 'stage', message: 'Informe o ano/série'},
            {field: 'responsible', message: 'Informe o responsável'},
            {field: 'city', message: 'Informe a cidade'},
            {field: 'street', message: 'Informe a rua'},
            {field: 'neighborhood', message: 'Informe o bairro'},
            {field: 'type', message: 'Informe o motivo'},
            {field: 'description', message: 'Informe o ocorrido'},
        ];

        if(this.props.action == 'update'){
            fields.splice(-1,1);
        }

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

    handleSubmit() {
        if(this.validate()) {
            switch(this.props.action){
                case 'update': 
                    this.update();
                break;
                case 'formalize': 
                    this.formalize(); 
                break;
                default:
                    this.create();
            }
         }
    }

    loadComplaint(){
        this.setState({complaintLoaded: false});
        api.get(`/v1/complaint/${this.props.id}`)
            .then(function(response){
                let data = response.data.data;
                this.setState({
                    city: data.address.city,
                    street: data.address.street,
                    number: data.address.number,
                    neighborhood: data.address.neighborhood,
                    complement: data.address.complement,
                    child: data.child.name,
                    age: data.child.age,
                    sex: data.child.sex,
                    stage: data.child.stage,
                    responsible: data.responsible,
                    type: data.type,
                    complement_type: (typeof data.complement_type != undefined ? data.complement_type : null),
                    complaintLoaded: true
                });
            }.bind(this))
            .catch(function(error){
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
        switch(this.props.action){
            case 'update': 
            case 'formalize': 
                this.loadComplaint();
            break;
        }
    }

    create(){
        this.setState({complaintLoaded: false});
        var header = {
            headers: {
                'Content-Type': 'application/json'
            }
        };

        var data = this.normalizeFields();
        api.post(`/v1/complaint`, data, header)
            .then(function(response){
                if(typeof response.data.status !== 'undefined'){
                    if(response.data.status == '1'){
                        let data = response.data.data;
                        alert(`Denúncia cadastrada. \nNúmero: ${data._id}`);
                        this.resetState();
                    }
                    else{
                        const errors = response.data.error;
                        const messageError = Object.keys(errors).map(key => 
                            errors[key].map(id => `${key} - ${id}`)
                        );
                        this.setState({['error']: messageError});
                    }
                }
                this.setState({complaintLoaded: !this.state.complaintLoaded});
            }.bind(this))
            .catch(function(error){
                this.setState({complaintLoaded: !this.state.complaintLoaded});
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

    update(){
        this.setState({complaintLoaded: false});

        var header = {
            headers: {
                'Content-Type': 'application/json'
            }
        };

        var data = this.normalizeFields();
        api.post(`/v1/complaint/update/${this.props.id}`, data, header)
            .then(function(response){
                if(typeof response.data.status !== 'undefined'){
                    if(response.data.status == '1'){
                        let data = response.data.data;
                        alert(`Denúncia atualizada.`);
                        this.props.history.push(`/app/complaint/list`);
                        this.resetState();
                    }
                    else{
                        const errors = response.data.error;
                        const messageError = Object.keys(errors).map(key => 
                            errors[key].map(id => `${key} - ${id}`)
                        );
                        this.setState({['error']: messageError});
                    }
                    this.setState({complaintLoaded: !this.state.complaintLoaded});
                }
            }.bind(this))
            .catch(function(error){
                this.setState({complaintLoaded: !this.state.complaintLoaded});
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

    formalize(){
        this.setState({complaintLoaded: false});

        var header = {
            headers: {
                'Content-Type': 'application/json'
            }
        };

        var data = this.normalizeFields();
        api.post(`/v1/complaint/formalize/${this.props.id}`, data, header)
            .then(function(response){
                if(typeof response.data.status !== 'undefined'){
                    if(response.data.status == '1'){
                        alert(`Denúncia formalizada.`);
                        this.props.history.push(`/app/complaint/list`);
                        this.resetState();
                    }
                    else{
                        const errors = response.data.error;
                        const messageError = Object.keys(errors).map(key => 
                            errors[key].map(id => `${key} - ${id}`)
                        );
                        this.setState({['error']: messageError});
                    }
                }
                this.setState({complaintLoaded: !this.state.complaintLoaded});
            }.bind(this))
            .catch(function(error){
                this.setState({complaintLoaded: !this.state.complaintLoaded});
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

    render(){

        const motivos = [
            { label: "Trabalho infantil - Caracteriza-se pela exploração laboral de um indivíduo menor de 14 anos de idade. O exercício de atividades laborais para os menores de 18 anos no Brasil está regulamentado em legislação específica9. Dos 14 aos 16 anos é permitido o trabalho aos adolescentes desde que na condição de aprendiz. Dos 16 aos 18 anos, o trabalho é liberado, mas não pode comprometer a frequência e aprendizado escolar, ocorrer em condições insalubres ou com jornada noturna, tão pouco impedir que o adolescente desenvolva atividades de lazer", value: 'trabalho_infantil' },
            { label: "Bullying - É um termo utilizado para definir maus-tratos ou violência interpessoal entre iguais. Sem tradução para a língua portuguesa, a expressão bullying define um fenômeno identificado a partir de três características: a intencionalidade, o prolongamento temporal e o alvo único, gerando desequilíbrio de poder físico, psicológico e social entre o violador e o violado", value: 'bullying' },
          ];

        if (this.props.action != 'create' && !this.state.complaintLoaded) {
            return (
                <RctSectionLoader />
            )
		}
        
        return (
            <div className="row justify-content-md-center">
                <div className="col-md-12">
                    <RctCollapsibleCard heading="Nova Denúncia">
                        {
                            this.state.error.length > 0 &&
                            this.state.error.map((item, index) =>
                                <Alert color="danger" id={index}> {item} </Alert>
                            )
                        }
                        {
                            this.state.success.length > 0 &&
                            this.state.success.map((item, index) =>
                                <Alert color="success" id={index}> {item} </Alert>
                            )
                        }
                        <Form>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Dados Pessoais</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-8">
                                    <FormGroup>
                                        <Label for="child">Nome da criança</Label>
                                        <Input type="text" name="child" id="child" autoComplete="off" value={this.state.child} onChange={(e) => this.handleChange(e, 'child')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-4">
                                    <FormGroup>
                                        <Label for="age">Idade</Label>
                                        <Input type="text" name="age" id="age" autoComplete="off" value={this.state.age} onChange={(e) => this.handleChange(e, 'age')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Label for="sex">Sexo</Label>
                                        <Input type="select" name="sex" id="sex" value={this.state.sex} onChange={(e) => this.handleChange(e, 'sex')} bsSize="lg">
                                            <option value="F">Feminino</option>
                                            <option value="M">Masculino</option>
                                        </Input>
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-6 col-md-6">
                                    <FormGroup>
                                        <Label for="stage">Ano/Série</Label>
                                        <Input type="text" name="stage" id="stage" autoComplete="off" value={this.state.stage} onChange={(e) => this.handleChange(e, 'stage')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-male-female zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Responsáveis</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-md-12">
                                    <FormGroup>
                                        <Label for="responsible">Nome do responsável</Label>
                                        <Input type="text" name="responsible" id="responsible" autoComplete="off" value={this.state.responsible} onChange={(e) => this.handleChange(e, 'responsible')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-pin zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Endereço</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-5">
                                    <FormGroup>
                                        <Label for="city">Cidade</Label>
                                        <Input type="text" name="city" id="city" autoComplete="off" value={this.state.city} onChange={(e) => this.handleChange(e, 'city')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-7">
                                    <FormGroup>
                                        <Label for="street">Rua/Av./Rodovia</Label>
                                        <Input type="text" name="street" id="street" autoComplete="off" value={this.state.street} onChange={(e) => this.handleChange(e, 'street')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-3">
                                    <FormGroup>
                                        <Label for="number">Número</Label>
                                        <Input type="text" name="number" id="number" autoComplete="off" value={this.state.number} onChange={(e) => this.handleChange(e, 'number')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-4">
                                    <FormGroup>
                                        <Label for="neighborhood">Bairro</Label>
                                        <Input type="text" name="neighborhood" id="neighborhood" autoComplete="off" value={this.state.neighborhood} onChange={(e) => this.handleChange(e, 'neighborhood')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                                <div className="col-sm-12 col-md-5">
                                    <FormGroup>
                                        <Label for="complement">Complemento</Label>
                                        <Input type="text" name="complement" id="complement" autoComplete="off" value={this.state.complement} onChange={(e) => this.handleChange(e, 'complement')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                                    <span className="mr-2"><i className="zmdi zmdi-collection-text zmdi-hc-lg text-primary"></i></span>
                                    <h3 className="mb-0 text-primary"> Dados da ocorrência</h3>
                                </div>
                            </div>
                            <div className="row mb-4">
                                <div className="col-sm-12 col-md-12">
                                    <span className="w-100 form-header-primary"></span>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 col-md-12">
                                    <FormControl component="fieldset">
                                        <RadioGroup row aria-label="type" name="type" id="type" value={this.state.type}
                                            onChange={(e) => this.handleChangeRadio(e, 'type')} >
                                            <FormControlLabel value="maus_tratos" control={<Radio />} label="Maus Tratos" />
                                            <FormControlLabel value="negligencia" control={<Radio />} label="Negligência" />
                                            <FormControlLabel value="violencia_fisica" control={<Radio />} label="Violência física" />
                                            <FormControlLabel value="violencia_psicologica" control={<Radio />} label="Violência psicológica" />
                                            <FormControlLabel value="violencia_sexual" control={<Radio />} label="Violência sexual" />
                                            <FormControlLabel value="outros" control={<Radio />} label="Outros" />
                                        </RadioGroup>
                                        <div className="invalid-feedback" ></div>
                                    </FormControl>
                                </div>
                            </div>

                            {this.state.type == 'outros' && <div className="row mt-40">
                                <div className="col-sm-12 col-md-6">
                                    <FormGroup>
                                        <Select placeholder="Selecione o motivo" name="complement_type" id="complement_type"  defaultValue={this.state.complement_type} onChange={(e) => this.handleChange(e, 'complement_type')} options={motivos} />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>}

                            {this.props.action != 'update' && <div className="row mt-40">
                                <div className="col-sm-12">
                                    <FormGroup>
                                        <Label for="description">Descrição dos fatos</Label>
                                        <textarea className="form-control" name="description" id="description" autoComplete="off" value={this.state.description || ''} onChange={(e) => this.handleChange(e, 'description')} rows="5"></textarea>
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>}

                            <div className="row">
                                <div className="col-md-12">
                                    <FormGroup>
                                        <Label for="informer">Noticiante</Label>
                                        <Input type="text" name="informer" id="informer" autoComplete="off" value={this.state.informer} onChange={(e) => this.handleChange(e, 'informer')} bsSize="lg" />
                                        <div className="invalid-feedback" ></div>
                                    </FormGroup>
                                </div>
                            </div>

                            {this.props.action != 'update' && <div className="row">
                                <div className="col-md-12">
                                    <FormGroup>
                                        <Label for="files">Arquivos</Label>
                                        <Input  style={{paddingLeft: 0}} type="file" onChange={(e) => this.handleChangeFile(e)} multiple="true" name="files" id="files" bsSize="lg" />
                                    </FormGroup>
                                </div>
                            </div>}
                            <Button color="primary" disabled={this.state.disableButton} onClick={() => this.handleSubmit()}>Enviar</Button>
                        </Form>
                    </RctCollapsibleCard>
                </div>
            </div>
        );
    }
}
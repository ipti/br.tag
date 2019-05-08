import React, { Component, Fragment } from 'react';
import PeopleForm from 'Components/People/PeopleForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { savePeople, getPeopleById, updatePeople } from 'Actions';


class PeopleFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getPeopleById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/people/list`);
                    break;
                default:
                    NotificationManager.error(nextProps.notification.message);
                    break;
            }
        }
    }

    initialState = () =>{
        return {
            isValid: true,
            loading: false
        }
    }

    initialErros = () =>{
        return {
            name: {valid: true, errors: []},
            birthday: {valid: true, errors: []},
            father: {valid: true, errors: []},
            mother: {valid: true, errors: []},
            nickname: {valid: true, errors: []},
            sex: {valid: true, errors: []},
            rg: {valid: true, errors: []},
            cpf: {valid: true, errors: []},
            civilStatus: {valid: true, errors: []},
            nacionality: {valid: true, errors: []},
            placeBirthday: {valid: true, errors: []},
            profession: {valid: true, errors: []},
            scholarity: {valid: true, errors: []},
            street: {valid: true, errors: []},
            number: {valid: true, errors: []},
            complement: {valid: true, errors: []},
            neighborhood: {valid: true, errors: []},
            zip: {valid: true, errors: []},
            city: {valid: true, errors: []},
            state: {valid: true, errors: []},
            country: {valid: true, errors: []},
        };
    }

    initialRules = () => {
        return {
            name: [
                { rule: Validator.notEmpty, message: "Nome não pode ser vazio"}
            ],
            birthday: [
                { rule: Validator.notEmpty, message: "Nascimento não pode ser vazio"},
                { rule: Validator.isDate, message: "Data inválida"}
            ],
            mother: [
                { rule: Validator.notEmpty, message: "Nome da mãe não pode ser vazio"}
            ]
        }
    }

    setError = (item) =>{
        this.errors = {
            ...this.errors,
            [item.field]: {
                valid: false,
                errors:[
                    ...this.errors[item.field].errors,
                    { message: item.message }
                ]
            }
        }
    }

    validate = (data) =>{
        let isValid = true;
        this.errors = this.initialErros();

        Object.keys(data).map((item) =>{
            if(this.rules[item]){
                const rulesItem = this.rules[item];

                rulesItem.map((ruleItem, key) => {
                    const result = ruleItem.rule(data[item]);
                    isValid = isValid && result;

                    if(!result){
                        this.setError({field: item, key: key, message: ruleItem.message});
                    }
                })
            }
        });

        this.setState({
            isValid: isValid
        });

        return isValid;
    }

    save = () =>{
        const data = this.props.formFields;
        if(this.validate(data)){
            const normalizedData = this.normalizeData(data);
            if(this.props.scenario === 'create'){
                this.props.savePeople(normalizedData);
            }
            else{
                this.props.updatePeople(normalizedData);
            }
        }
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                name: data.name,
                birthday: data.birthday,
                father: data.father,
                mother: data.mother,
                nickname: data.nickname,
                sex: data.sex,
                rg: data.rg,
                cpf: data.cpf,
                civilStatus: data.civilStatus,
                nacionality: data.nacionality,
                placeBirthday: data.placeBirthday,
                profession: data.profession,
                scholarity: data.scholarity,
                address:{
                    street: data.street,
                    number: data.number,
                    complement: data.complement,
                    neighborhood: data.neighborhood,
                    zip: data.zip,
                    city: data.city,
                    state: data.state,
                    country: data.country
                }
            }
        )
    }
    
    render(){
        return(
            <Fragment>
                {
                    this.props.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <Fragment>
                            <PeopleForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

PeopleFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

PeopleFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ people }) => {
    return people;
};

export default connect(mapStateToProps, {
    savePeople,
    getPeopleById,
    updatePeople
 })(PeopleFormContainer)
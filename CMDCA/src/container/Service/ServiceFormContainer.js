import React, { Component, Fragment } from 'react';
import ServiceForm from 'Components/Service/ServiceForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveService, getServiceById, updateService } from 'Actions';
import { convertToRaw, convertFromRaw } from 'draft-js';
import {stateToHTML} from 'draft-js-export-html';
import 'Assets/css/service/style.css';


class ServiceFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getServiceById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/service/list`);
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
            child: {valid: true, errors: []},
            description: {valid: true, errors: []},
        };
    }

    initialRules = () => {
        return {
            child: [
                { rule: Validator.notEmpty, message: "Pessoa não pode ser vazio"}
            ],
            description: [
                { rule: Validator.notEmpty, message: "Descrição não pode ser vazio"}
            ],
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
                this.props.saveService(normalizedData);
            }
            else{
                this.props.updateService(normalizedData);
            }
        }
    }

    toHTML = (text) => {
        const json = convertToRaw(text);
        return stateToHTML(convertFromRaw(json));
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                child: data.child,
                description: this.toHTML(data.description.getCurrentContent()),
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
                                <ServiceForm save={this.save} errors={this.errors} />
                            </Fragment>
                        )
                }
            </Fragment>

        );
    }

}

ServiceFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

ServiceFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ service }) => {
    return service;
};

export default connect(mapStateToProps, {
    saveService,
    getServiceById,
    updateService
})(ServiceFormContainer)
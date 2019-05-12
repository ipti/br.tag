import React, { Component, Fragment } from 'react';
import FactForm from 'Components/Fact/FactForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveFact, getFactById, updateFact } from 'Actions';
import { convertToRaw, convertFromRaw } from 'draft-js';
import {stateToHTML} from 'draft-js-export-html';
import 'Assets/css/fact/style.css';


class FactFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getFactById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/fact/list`);
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
            providence: {valid: true, errors: []},
        };
    }

    initialRules = () => {
        return {
            child: [
                { rule: Validator.notEmpty, message: "Criança não pode ser vazio"}
            ],
            description: [
                { rule: Validator.notEmpty, message: "Descrição não pode ser vazio"},
            ],
            providence: [
                { rule: Validator.notEmpty, message: "Providência não pode ser vazio"},
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
                this.props.saveFact(normalizedData);
            }
            else{
                this.props.updateFact(normalizedData);
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
                providence: this.toHTML(data.providence.getCurrentContent()),
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
                            <FactForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

FactFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

FactFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ fact }) => {
    return fact;
};

export default connect(mapStateToProps, {
    saveFact,
    getFactById,
    updateFact
 })(FactFormContainer)
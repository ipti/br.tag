import React, { Component, Fragment } from 'react';
import WarningForm from 'Components/Warning/WarningForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { convertToRaw } from 'draft-js';
import draftToMarkdown from 'draftjs-to-markdown';
import draftToHtml from 'draftjs-to-html';
import { connect } from 'react-redux';
import { saveWarning, getWarningById, updateWarning } from 'Actions';
import 'Assets/css/warning/style.css';


class WarningFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getWarningById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/warning/list`);
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
            personAdolescent: { valid: true, errors: [] },
            personRepresentative: {valid: true, errors: []},
            reason: { valid: true, errors: [] }
        };
    }

    initialRules = () => {
        return {
            personAdolescent: [
                { rule: Validator.notEmpty, message: "Adolescente não pode ser vazio"}
            ],
            personRepresentative: [
                { rule: Validator.notEmpty, message: "Representante não pode ser vazio" }
            ],
            reason: [
                { rule: Validator.notEmpty, message: "Motivo não pode ser vazio" }
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
        data = {
            ...data,
            reason: this.toMarkDown(data.reason)
        };
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
                this.props.saveWarning(normalizedData);
            }
            else{
                this.props.updateWarning(normalizedData);
            }
        }
    }

    toMarkDown = (state) => {
        return draftToMarkdown(convertToRaw(state.getCurrentContent())).trim();
    }

    toHTML = (state) => {
        return draftToHtml(convertToRaw(state.getCurrentContent()));
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                personAdolescent: data.personAdolescent,
                personRepresentative: data.personRepresentative,
                reason: this.toHTML(data.reason),
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
                            <WarningForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

WarningFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

WarningFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ warning }) => {
    return warning;
};

export default connect(mapStateToProps, {
    saveWarning,
    getWarningById,
    updateWarning
 })(WarningFormContainer)
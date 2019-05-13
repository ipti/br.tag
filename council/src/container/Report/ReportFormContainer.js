import React, { Component, Fragment } from 'react';
import ReportForm from 'Components/Report/ReportForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveReport, getReportById, updateReport } from 'Actions';
import { convertToRaw, convertFromRaw } from 'draft-js';
import {stateToHTML} from 'draft-js-export-html';
import 'Assets/css/report/style.css';


class ReportFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getReportById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/report/list`);
                    break;
                default:
                    NotificationManager.error(nextProps.notification.message);
                    break;
            }
        }
    }

    toHTML = (text) => {
        const json = convertToRaw(text);
        return stateToHTML(convertFromRaw(json));
    }

    initialState = () =>{
        return {
            isValid: true,
            loading: false
        }
    }

    initialErros = () =>{
        return {
            description: {valid: true, errors: []}
        };
    }

    initialRules = () => {
        return {
            description: [
                { rule: Validator.notEmpty, message: "Descrição não pode ser vazio"}
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
                this.props.saveReport(normalizedData);
            }
            else{
                this.props.updateReport(normalizedData);
            }
        }
    }

    normalizeData = (data) =>{
        return (
            {
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
                                <ReportForm save={this.save} errors={this.errors} />
                            </Fragment>
                        )
                }
            </Fragment>

        );
    }

}

ReportFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

ReportFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ report }) => {
    return report;
};

export default connect(mapStateToProps, {
    saveReport,
    getReportById,
    updateReport
})(ReportFormContainer)
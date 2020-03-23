import React, { Component, Fragment } from 'react';
import NotificationForm from 'Components/Notification/NotificationForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveNotification, getNotificationById, updateNotification } from 'Actions';
import 'Assets/css/notification/style.css';


class NotificationFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getNotificationById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps._notification.type !== 'undefined'){
            switch (nextProps._notification.type) {
                case 'success':
                    NotificationManager.success(nextProps._notification.message);
                    this.props.history.push(`/app/notification/list`);
                    break;
                default:
                    NotificationManager.error(nextProps._notification.message);
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
            notified: {valid: true, errors: []},
            date: {valid: true, errors: []},
            time: {valid: true, errors: []}
        };
    }

    initialRules = () => {
        return {
            notified: [
                { rule: Validator.notEmpty, message: "Notificado não pode ser vazio"}
            ],
            date: [
                { rule: Validator.notEmpty, message: "Data não pode ser vazio"},
                { rule: Validator.isDate, message: "Data inválida"}
            ],
            time: [
                { rule: Validator.notEmpty, message: "Data não pode ser vazio"},
                { rule: Validator.isTime, message: "Hora inválida"}
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
                this.props.saveNotification(normalizedData);
            }
            else{
                this.props.updateNotification(normalizedData);
            }
        }
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                notified: data.notified,
                date: data.date,
                time: data.time,
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
                            <NotificationForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

NotificationFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

NotificationFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ notification }) => {
    return notification;
};

export default connect(mapStateToProps, {
    saveNotification,
    getNotificationById,
    updateNotification
 })(NotificationFormContainer)
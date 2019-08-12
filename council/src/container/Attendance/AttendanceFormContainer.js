import React, { Component, Fragment } from 'react';
import AttendanceForm from 'Components/Attendance/AttendanceForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveAttendance, getAttendanceType } from 'Actions';
import 'Assets/css/attendance/style.css';


class AttendanceFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        await this.props.getAttendanceType();
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/attendance/list`);
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
            person: {valid: true, errors: []},
            type: {valid: true, errors: []},
            place: {valid: true, errors: []},
            date: {valid: true, errors: []},
            time: {valid: true, errors: []},
        };
    }

    initialRules = () => {
        return {
            date: [
                { rule: Validator.notEmpty, message: "Data não pode ser vazio"},
                { rule: Validator.isDate, message: "Data inválida"},
            ],
            time: [
                { rule: Validator.notEmpty, message: "Hora não pode ser vazio"},
                { rule: Validator.isTime, message: "Hora inválida"},
            ],
            type: [
                { rule: Validator.notEmpty, message: "Tipo não pode ser vazio"},
            ],
            place: [
                { rule: Validator.notEmpty, message: "Local não pode ser vazio"},
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
                this.props.saveAttendance(normalizedData);
            }
            else{
                // update
            }
        }
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                type: data.type,
                person: data.person,
                place: data.place,
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
                            <AttendanceForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

AttendanceFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

AttendanceFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ attendance }) => {
    return attendance;
};

export default connect(mapStateToProps, {
    saveAttendance,
    getAttendanceType,
 })(AttendanceFormContainer)
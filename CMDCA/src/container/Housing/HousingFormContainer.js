import React, { Component, Fragment } from 'react';
import HousingForm from 'Components/Housing/HousingForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveHousing, getHousingById, updateHousing } from 'Actions';
import { convertToRaw } from 'draft-js';
import draftToMarkdown from 'draftjs-to-markdown';
import draftToHtml from 'draftjs-to-html';
import 'Assets/css/housing/style.css';


class HousingFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getHousingById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/housing/list`);
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
            receiver: {valid: true, errors: []},
            sender: {valid: true, errors: []},
            motive: {valid: true, errors: []},
        };
    }

    initialRules = () => {
        return {
            child: [
                { rule: Validator.notEmpty, message: "Criança não pode ser vazio"}
            ],
            receiver: [
                { rule: Validator.notEmpty, message: "Destinatário não pode ser vazio"},
            ],
            sender: [
                { rule: Validator.notEmpty, message: "Remetente não pode ser vazio"},
            ],
            motive: [
                { rule: Validator.notEmpty, message: "Motivo não pode ser vazio"},
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
        data = {
            ...data,
            motive: this.toMarkDown(data.motive)
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
                this.props.saveHousing(normalizedData);
            }
            else{
                this.props.updateHousing(normalizedData);
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
                child: data.child,
                receiver: data.receiver,
                sender: data.sender.value,
                motive: this.toHTML(data.motive),
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
                            <HousingForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

HousingFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

HousingFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ housing }) => {
    return housing;
};

export default connect(mapStateToProps, {
    saveHousing,
    getHousingById,
    updateHousing
 })(HousingFormContainer)
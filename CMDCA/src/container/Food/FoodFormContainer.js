import React, { Component, Fragment } from 'react';
import FoodForm from 'Components/Food/FoodForm';
import Validator from 'Util/Validator';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import PropTypes from 'prop-types';
import { NotificationManager } from 'react-notifications';
import { connect } from 'react-redux';
import { saveFood, getFoodById, updateFood } from 'Actions';
import { convertToRaw } from 'draft-js';
import draftToMarkdown from 'draftjs-to-markdown';
import draftToHtml from 'draftjs-to-html';
import 'Assets/css/food/style.css';


class FoodFormContainer extends Component{

    constructor(props){
        super(props);
        this.state = this.initialState();
        this.errors = this.initialErros();
        this.rules = this.initialRules();
    }

    async componentDidMount(){
        if(this.props.scenario === 'update'){
            await this.props.getFoodById(this.props.match.params.id);
        }
    }

    componentWillReceiveProps(nextProps){
        if(typeof nextProps.notification.type !== 'undefined'){
            switch (nextProps.notification.type) {
                case 'success':
                    NotificationManager.success(nextProps.notification.message);
                    this.props.history.push(`/app/food/list`);
                    break;
                default:
                    NotificationManager.error(nextProps.notification.message);
                    break;
            }
        }
    }

    toMarkDown = (state) => {
        return draftToMarkdown(convertToRaw(state.getCurrentContent())).trim();
    }

    toHTML = (state) => {
        return draftToHtml(convertToRaw(state.getCurrentContent()));
    }

    initialState = () =>{
        return {
            isValid: true,
            loading: false
        }
    }

    initialErros = () =>{
        return {
            personApplicant: { valid: true, errors: [] },
            personRepresentative: {valid: true, errors: []},
            personRequired: { valid: true, errors: [] },
            reason: { valid: true, errors: [] }
        };
    }

    initialRules = () => {
        return {
            personApplicant: [
                { rule: Validator.notEmpty, message: "Requerente não pode ser vazio"}
            ],
            personRepresentative: [
                { rule: Validator.notEmpty, message: "Representante não pode ser vazio" }
            ],
            personRequired: [
                { rule: Validator.notEmpty, message: "Requerido não pode ser vazio" }
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
                this.props.saveFood(normalizedData);
            }
            else{
                this.props.updateFood(normalizedData);
            }
        }
    }

    normalizeData = (data) =>{
        return (
            {
                _id: data._id,
                personApplicant: data.personApplicant,
                personRepresentative: data.personRepresentative,
                personRequired: data.personRequired,
                reason: this.toHTML(data.reason),
                place: localStorage.getItem('institution'),
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
                            <FoodForm save={this.save} errors={this.errors} />
                        </Fragment>
                    )
                }
            </Fragment>
            
        );
    }

}

FoodFormContainer.propTypes = {
    scenario: PropTypes.string.isRequired,
};

FoodFormContainer.defaultProps = {
    scenario: 'create',
};

const mapStateToProps = ({ food }) => {
    return food;
};

export default connect(mapStateToProps, {
    saveFood,
    getFoodById,
    updateFood
 })(FoodFormContainer)
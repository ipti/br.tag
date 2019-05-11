import React, { Component } from 'react';
import AsyncSelect from 'react-select/lib/Async';
import Api from 'Api';
import PropTypes from 'prop-types';


class PeopleSelect extends Component{

    constructor(props){
        super(props);
        this.state={
            inputValue: '',
            people: {
                label: '',
                value:{
                    id: props.value
                }
            },
            options: []
        }
    }

    searchPeople =  (inputValue) =>{
        return (
            Api
                .get(`/v1/people/search?fields=${inputValue}`)
                .then((response) => {
                    const result = response.data.map((people) => {
                        return {
                            label: `${people.name} - ${people.birthday}`,
                            value: {
                                id: people._id
                            },
                        }
                    });
                    this.setState({options: result});
                    return result;
                })
        )
    }

    options = (inputValue) =>{
        if(inputValue.trim().length > 3){
            return(
                new Promise(resolve => {
                    resolve(this.searchPeople(inputValue));
                })
            )
        }
        return [];
    }

    handleInputChange = (newValue) => {
        const inputValue = newValue;
        this.setState({ inputValue });
        return inputValue;
    };

    handleChange = (people) => {
        this.setState({ people }, () => this.props.handleChange(people.value.id));
    };

    render(){
        return (
            <div className="people-content">
                <AsyncSelect 
                    cacheOptions 
                    defaultOptions
                    value={this.state.options.filter((people) => this.state.people.value !== '' && this.state.people.value.id === people.value.id)} 
                    loadOptions={this.options}
                    placeholder="Selecione..."
                    loadingMessage={e => `Carregando...`}
                    noOptionsMessage={() => "Nenhuma pessoa encontrada"}
                    onInputChange={this.handleInputChange}
                    onChange={this.handleChange}
                />
            </div>
        );
    }
}

PeopleSelect.propTypes = {
    handleChange: PropTypes.func.isRequired,
    value: PropTypes.string.isRequired,
};

export default PeopleSelect;
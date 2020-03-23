import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Fact extends Component{

    render(){
        return (
            <div className="fact-content">
                <Title title="Registro de Fato" />
                <List items={this.props.facts} />
            </div>
        );
    }
}

Fact.propTypes = {
    facts: PropTypes.array.isRequired,
};

export default Fact;
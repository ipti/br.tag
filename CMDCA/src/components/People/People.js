import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class People extends Component{

    render(){
        return (
            <div className="people-content">
                <Title title="Pessoas" />
                <List items={this.props.peoples} />
            </div>
        );
    }
}

People.propTypes = {
    peoples: PropTypes.array.isRequired,
};

export default People;
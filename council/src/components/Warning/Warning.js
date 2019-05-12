import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Warning extends Component{

    render(){
        return (
            <div className="warning-content">
                <Title title="Advertências" />
                <List items={this.props.warnings} />
            </div>
        );
    }
}

Warning.propTypes = {
    warnings: PropTypes.array.isRequired,
};

export default Warning;
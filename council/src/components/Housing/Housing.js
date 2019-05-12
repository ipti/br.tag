import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Housing extends Component{

    render(){
        return (
            <div className="housing-content">
                <Title title="Termo de Abrigamento" />
                <List items={this.props.housings} />
            </div>
        );
    }
}

Housing.propTypes = {
    housings: PropTypes.array.isRequired,
};

export default Housing;
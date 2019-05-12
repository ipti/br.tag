import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Service extends Component{

    render(){
        return (
            <div className="service-content">
                <Title title="Requisições de Serviço" />
                <List items={this.props.services} />
            </div>
        );
    }
}

Service.propTypes = {
    services: PropTypes.array.isRequired,
};

export default Service;
import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Food extends Component{

    render(){
        return (
            <div className="food-content">
                <Title title="Ação de alimentos" />
                <List items={this.props.foods} />
            </div>
        );
    }
}

Notification.propTypes = {
    foods: PropTypes.array.isRequired,
};

export default Food;
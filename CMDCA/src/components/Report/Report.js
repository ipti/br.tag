import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Report extends Component{

    render(){
        return (
            <div className="report-content">
                <Title title="Relatórios" />
                <List items={this.props.reports} />
            </div>
        );
    }
}

Report.propTypes = {
    reports: PropTypes.array.isRequired,
};

export default Report;
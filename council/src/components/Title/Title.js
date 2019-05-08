import React, { Component } from 'react';
import PropTypes from 'prop-types';

class Title extends Component{

    render(){
        return(
            <React.Fragment>
                <div className="page-title d-flex justify-content-between align-items-center">
                    <div className="page-title-wrap">
                        <i className="ti-angle-left"></i>
                        <h2>{this.props.title}</h2>
                    </div>
                </div>
            </React.Fragment>
        )
    }

}

Title.propTypes = {
    title: PropTypes.string.isRequired,
};


export default Title;
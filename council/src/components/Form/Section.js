import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class Section extends Component{

    render(){
        const {title, icon} = this.props;
        return(
            <Fragment>
                <div className="row mb-4">
                    <div className="col-sm-12 col-md-12 d-inline-flex align-items-center">
                        <span className="mr-2"><i className={`zmdi zmdi-${icon} zmdi-hc-lg text-primary`}></i></span>
                        <h3 className="mb-0 text-primary">{title}</h3>
                    </div>
                </div>
                <div className="row mb-4">
                    <div className="col-sm-12 col-md-12">
                        <span className="w-100 form-header-primary"></span>
                    </div>
                </div>
            </Fragment>
        );
    }
}

Section.propTypes = {
    icon: PropTypes.string.isRequired,
    title: PropTypes.string.isRequired
};

export default Section;
import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';


class PreviewDocument extends Component{

    render(){
        const Header =  this.props.header || Fragment;
        const Body = this.props.body || Fragment;
        const Footer = this.props.footer || Fragment;

        return(
            <Fragment>
                {Header}
                {Body}
                {Footer}
            </Fragment>
        )
    }
}

PreviewDocument.propTypes = {
    header: PropTypes.element,
    body: PropTypes.element.isRequired,
    footer: PropTypes.element
};


export default PreviewDocument;
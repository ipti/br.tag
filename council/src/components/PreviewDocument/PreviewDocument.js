import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class PreviewDocument extends Component{

    render(){
        const Header = this.props.header || Fragment;
        const Body = this.props.body || Fragment;
        const Footer = this.props.footer || Fragment;

        return(
            <Fragment>
                <Header />
                <Body />
                <Footer />
            </Fragment>
        )
    }
}

PreviewDocument.propTypes = {
    header: PropTypes.elementType,
    body: PropTypes.elementType.isRequired,
    footer: PropTypes.elementType
};


export default PreviewDocument;
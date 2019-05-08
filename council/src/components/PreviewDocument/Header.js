import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class Header extends Component{

    Phone = () =>{
        return(
            <Fragment>
                { this.props.phone && 
                    <span className="mr-20">
                        Fone: {this.props.phone}
                    </span>
                }
            </Fragment>
        )
    }

    Email = () =>{
        return(
            <Fragment>
                { this.props.email && 
                    <span>
                        E-mail: {this.props.email}
                    </span>
                }
            </Fragment>
        )
    }

    render(){

        return(
            <div>
                <div className="d-flex">
                    <div className="header-image">
                        <div className="d-flex justify-content-center mr-2">
                            <img width="260px" src={require('../../assets/img/conselho.jpg')} />
                        </div>
                    </div>
                    <div className="d-flex flex-column align-self-center header-text">
                        <h3 className="title-header">CONSELHO TUTELAR DOS DIREITOS DA CRIANÇA E DO ADOLESCENTE</h3>
                        <h4 className="title-header">{this.props.street}</h4>
                        <h4 className="title-header">{this.props.city}</h4>
                        <h4 className="title-header">Lei Federal nº 8.069/90</h4>
                        <h4 className="title-header">
                            <this.Phone />
                            <this.Email />
                        </h4>
                    </div>
                </div>
                <hr/>

            </div>
        )
    }
}

Header.propTypes = {
    street: PropTypes.string.isRequired,
    city: PropTypes.string.isRequired,
    phone: PropTypes.string.isRequired,
    email: PropTypes.string.isRequired,
};


export default Header;
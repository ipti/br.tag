import React, { Component } from 'react';
import PropTypes from 'prop-types';

class Card extends Component{

    redirect = () =>{
        this.props.history.push(`/app/people/form/${this.props.id}`);
    }

    render(){
        const {name, birthday} = this.props;
        return(
            <div className="col-sm-12 col-md-4 cursor-pointer" onClick={this.redirect}>
                <div className="rct-block">
                    <div className="rct-block-content">
                        <div className="row mx-0">
                            <div className="col-7 d-flex aling-items-center">
                                <div className="mr-2">
                                    <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                </div>
                                <div className="w-100">
                                    <h4 className="people-card-title text-ellipsis">{name}</h4>
                                    <span className="people-card-subtitle">Nome</span>
                                </div>
                            </div>
                            <div className="col-4 d-flex aling-items-center">
                                <div className="mr-2">
                                    <img width="26px" src={require('../../assets/img/icons/calendar-heart.png')} />
                                </div>
                                <div>
                                    <h4 className="people-card-title">{birthday}</h4>
                                    <span className="people-card-subtitle">Nascimento</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

Card.propTypes = {
    id: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    birthday: PropTypes.string.isRequired,
    history: PropTypes.object.isRequired,
};


export default Card;
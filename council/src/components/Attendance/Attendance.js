import React, { Component } from 'react';
import PropTypes from 'prop-types';


class Attendance extends Component{

    render(){
        return (
            <div className="attendance-content">
                <div className="row justify-content-center">
                    <div className="col-sm-12 col-md-4 attendance-count">
                        <h2 className="title">
                            Atendimentos
                        </h2>
                        <div className="divider"></div>
                        <h4 className="subtitle d-flex align-items-center justify-content-center">
                            {this.props.attendances[0]}
                        </h4>
                    </div>
                </div>
            </div>
        );
    }
}

Attendance.propTypes = {
    attendances: PropTypes.array.isRequired,
};

export default Attendance;
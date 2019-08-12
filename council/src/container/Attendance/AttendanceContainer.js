import React, { Component, Fragment } from 'react';
import Attendance from 'Components/Attendance/Attendance';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getAttendance } from 'Actions';
import 'Assets/css/attendance/style.css';


class AttendanceContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            attendances: [],
            loading: true
        }
    }

    componentDidMount = async () =>{
        await this.props.getAttendance();
    }
    
    render(){
        const attendances = [this.props.attendances] || [0];
        return(
            <Fragment>
                {
                    this.props.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <Fragment>
                            <Attendance attendances={attendances} />
                            {
                                attendances.length === 0 && <Alert color="danger"> Nenhum item para exibir </Alert>
                            }
                        </Fragment>
                    )
                }
            </Fragment>
        );
    }

}

const mapStateToProps = ({ attendance }) => {
    return attendance;
 };

export default connect(mapStateToProps, {
    getAttendance
 })(AttendanceContainer)

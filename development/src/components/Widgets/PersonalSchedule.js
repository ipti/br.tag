/**
 * Personal Schedule Component
 */
import React, { Fragment, Component } from 'react';
import { Media } from 'reactstrap';
import Button from '@material-ui/core/Button';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import Snackbar from '@material-ui/core/Snackbar';
import { Scrollbars } from 'react-custom-scrollbars';
import { Modal, ModalHeader, ModalBody, ModalFooter, Form, FormGroup, Label, Input } from 'reactstrap';

// api
import api from 'Api';

//Helper
import { getTheDate, convertDateToTimeStamp } from 'Helpers/helpers';

//rct card component
import { RctCardFooter } from "Components/RctCard";

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class PersonalSchedule extends Component {

    state = {
        sectionReload: false,
        modal: false,
        schedulesData: null,
        newSchedule: {
            title: '',
            message: '',
            date: null
        },
    }

    componentDidMount() {
        this.getPersonalSchedules();
    }

    /**
     * Get Personal Schedules
     */
    getPersonalSchedules() {
        this.setState({ sectionReload: true });
        api.get('personalSchedule.js')
            .then((response) => {
                this.setState({ schedulesData: response.data, sectionReload: false });
            })
            .catch(error => {
                console.log(error)
            })
    }

    //ADD new Schedule
    addNewSchedule() {
        if (this.state.newSchedule.title !== '' && this.state.newSchedule.date) {
            let schedules = this.state.schedulesData;
            let newSchedule = {
                title: this.state.newSchedule.title,
                message: this.state.newSchedule.message,
                date: this.state.newSchedule.date,
            }
            schedules.push(newSchedule);
            this.setState({ sectionReload: true, modal: false });
            let self = this;
            setTimeout(() => {
                self.setState({
                    schedulesData: schedules,
                    modal: false,
                    sectionReload: false,
                    snackbar: true,
                    snackbarMessage: 'Schedule Added Successfully!',
                    newSchedule: {
                        title: '',
                        message: '',
                        date: null
                    }
                });
            }, 1500);
        }
    }

    //Open Modal add new Schedule dailog
    openModal() {
        this.setState({ modal: true });
    }

    // handle close add new Schedule dailog
    handleClose() {
        this.setState({ modal: false });
    }

    // on change schedule
    onChangeSchedule(e) {
        let timestamp = convertDateToTimeStamp(e.target.value);
        this.setState({ newSchedule: { ...this.state.newSchedule, date: timestamp } })
    }

    render() {
        const { sectionReload, schedulesData } = this.state;
        return (
            <Fragment>
                {sectionReload && <RctSectionLoader />}
                <div className="personal-schedule-wrap">
                    <div className="rct-block-title border-0 d-flex justify-content-between align-items-center">
                        <h4 className="mb-0"><IntlMessages id="widgets.personalSchedule" /></h4>
                        <Button variant="raised" className="bg-warning text-white btn-xs" onClick={() => this.openModal()}>
                            <IntlMessages id="widgets.addNew" />
                        </Button>
                    </div>
                    <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={320} autoHide >
                        <List className="list-unstyled p-0">
                            {schedulesData !== null && schedulesData.map((schedule, key) => (
                                <ListItem key={key} className="border-bottom border-warning d-flex align-items-center px-20 py-10">
                                    <Media>
                                        <Media className="text-white bg-warning mr-20 p-10">
                                            <h3 className="mb-0">{getTheDate(schedule.date, 'DD')}<span className="d-block fs-14">{getTheDate(schedule.date, 'MMM')}</span></h3>
                                        </Media>
                                        <Media body>
                                            <Media heading>
                                                {schedule.title}
                                            </Media>
                                            <p className="fs-12 mb-0 text-muted">{schedule.message}</p>
                                        </Media>
                                    </Media>
                                </ListItem>
                            ))}
                        </List>
                    </Scrollbars>
                    <RctCardFooter customClasses="d-flex border-0 justify-content-between bg-light-yellow rounded-bottom align-items-center">
                        <Button variant="raised" className="bg-warning text-white btn-xs">
                            <IntlMessages id="button.viewAll" />
                        </Button>
                        <p className="fs-12 mb-0 text-base">
                            <span><i className="mr-5 zmdi zmdi-refresh"></i></span>
                            <IntlMessages id="widgets.updated10Minago" />
                        </p>
                    </RctCardFooter>
                </div>
                <Modal
                    isOpen={this.state.modal}
                >
                    <ModalHeader>
                        Add New Schedule
                    </ModalHeader>
                    <ModalBody>
                        <Form>
                            <FormGroup>
                                <Label for="scheduleTitle">Schedule Title</Label>
                                <Input
                                    type="text"
                                    name="title"
                                    id="scheduleTitle"
                                    onChange={(e) => this.setState({ newSchedule: { ...this.state.newSchedule, title: e.target.value } })}
                                    value={this.state.newSchedule.title}
                                />
                            </FormGroup>
                            <FormGroup>
                                <Label for="scheduleBody">Schedule Body</Label>
                                <Input
                                    type="textarea"
                                    name="text"
                                    id="scheduleBody"
                                    onChange={(e) => this.setState({ newSchedule: { ...this.state.newSchedule, message: e.target.value } })}
                                    value={this.state.newSchedule.message}
                                />
                            </FormGroup>
                            <FormGroup>
                                <Label for="scheduleDate">Schedule Date</Label>
                                <Input
                                    type="date"
                                    name="date"
                                    id="scheduleDate"
                                    onChange={(e) => this.onChangeSchedule(e)}
                                />
                            </FormGroup>
                        </Form>
                    </ModalBody>
                    <ModalFooter>
                        <Button variant="raised" onClick={() => this.addNewSchedule()} color="primary" className="text-white">
                            <span>Add</span>
                        </Button>
                        <Button variant="raised" onClick={() => this.handleClose()} className="btn-danger text-white">
                            <span><IntlMessages id="button.cancel" /></span>
                        </Button>
                    </ModalFooter>
                </Modal>
                <Snackbar
                    anchorOrigin={{
                        vertical: 'top',
                        horizontal: 'center',
                    }}
                    open={this.state.snackbar}
                    onClose={() => this.setState({ snackbar: false })}
                    autoHideDuration={2000}
                    message={<span id="message-id">{this.state.snackbarMessage}</span>}
                />
            </Fragment>
        )
    }
}

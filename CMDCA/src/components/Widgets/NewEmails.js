/**
 * New Emails Widget
 */
import React, { Component, Fragment } from 'react';
import { Media } from 'reactstrap';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogContentText from '@material-ui/core/DialogContentText';
import DialogTitle from '@material-ui/core/DialogTitle';
import Snackbar from '@material-ui/core/Snackbar';
import { InputGroup, InputGroupAddon, Input } from 'reactstrap';
import update from 'react-addons-update';
import { Scrollbars } from 'react-custom-scrollbars';
import Avatar from '@material-ui/core/Avatar';
import { withRouter } from 'react-router-dom';

// api
import api from 'Api';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

class NewEmails extends Component {

  state = {
    sectionReload: false,
    newEmails: null,
    openConfirmationAlert: false,
    selectedDeletedEmail: null,
    snackbar: false,
    snackbarMessage: '',
    replyTextBox: false,
    selectedEmail: null,
    viewEmailDialog: false
  }

  componentDidMount() {
    api.get('newEmails.js')
      .then((response) => {
        this.setState({ newEmails: response.data });
      })
      .catch(error => {
        console.log(error);
      })
  }

  // on delete email open confirmation
  onDeleteEmail(email) {
    this.setState({ openConfirmationAlert: true, selectedDeletedEmail: email });
  }

  // close confirmation dailog
  handleCloseConfirmationAlert = () => {
    this.setState({ openConfirmationAlert: false, viewEmailDialog: false });
  }

  // delete email if confirmation true
  deleteEmail() {
    this.setState({ openConfirmationAlert: false, sectionReload: true });
    let emails = this.state.newEmails;
    let deletedEmailIndex = emails.indexOf(this.state.selectedDeletedEmail);
    emails.splice(deletedEmailIndex, 1);
    let self = this;
    setTimeout(() => {
      self.setState({ sectionReload: false, newEmails: emails, snackbar: true, snackbarMessage: 'Email Deleted Successfully!' });
    }, 1500);
  }

  // show reply text box
  showReplyTextBox(email) {
    let indexOfEmail = this.state.newEmails.indexOf(email);
    this.setState({
      newEmails: update(this.state.newEmails,
        {
          [indexOfEmail]: {
            replyTextBox: { $set: true }
          }
        }
      )
    });
  }

  // reply email
  replyEmail(email) {
    let indexOfEmail = this.state.newEmails.indexOf(email);
    this.setState({ sectionReload: true });
    this.setState({
      newEmails: update(this.state.newEmails,
        {
          [indexOfEmail]: {
            replyTextBox: { $set: false }
          }
        }
      )
    });
    let self = this;
    setTimeout(() => {
      self.setState({ sectionReload: false, snackbar: true, snackbarMessage: 'Reply Sent Successfully!' });
    }, 1500);
  }

  /**
   * On View Email
   */
  onViewEmal(email) {
    this.setState({ selectedEmail: email, viewEmailDialog: true });
  }

  render() {
    const { newEmails, selectedEmail, sectionReload } = this.state;
    return (
      <Fragment>
        {sectionReload &&
          <RctSectionLoader />
        }
        <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={400} autoHide>
          <ul className="new-mail mb-0 list-unstyled">
            {newEmails && newEmails.map((email, key) => (
              <li key={key}>
                <div className="d-flex justify-content-between">
                  <Media className="mb-10">
                    {email.sender_avatar === '' ?
                      <Avatar className="mr-15">{email.sender_name.charAt(0)}</Avatar>
                      : <Media object src={email.sender_avatar} alt="User Profile 1" className="rounded-circle mr-15" width="40" height="40" />
                    }
                    <Media body>
                      <h5 className="m-0 pt-5 fs-14">{email.sender_name}</h5>
                      <span className="fs-12 align-self-center">{email.from}</span>
                    </Media>
                  </Media>
                  <span className="small align-self-center">19 Mar 2017</span>
                </div>
                <div className="d-flex justify-content-between">
                  <div className="text-justify">
                    <p className="subject">{email.subject}</p>
                    <p className="message">{email.message}</p>
                    {email.replyTextBox &&
                      <div className="task-foot d-flex justify-content-between">
                        <InputGroup>
                          <Input />
                          <InputGroupAddon addonType="append">
                            <Button variant="raised" color="primary" className="text-white" onClick={() => this.replyEmail(email)}>
                              <IntlMessages id="button.reply" />
                            </Button>
                          </InputGroupAddon>
                        </InputGroup>
                      </div>
                    }
                  </div>
                  <div className="hover-action text-right w-25 align-self-center">
                    <Button color="primary" className="text-white mr-5 mb-5" variant="fab" mini onClick={() => this.onViewEmal(email)}><i className="zmdi zmdi-eye"></i></Button>
                    <Button className="btn-danger text-white mr-5 mb-5" variant="fab" mini onClick={() => this.onDeleteEmail(email)}><i className="zmdi zmdi-delete"></i></Button>
                    <Button className="btn-success text-white mr-5 mb-5" variant="fab" mini onClick={() => this.showReplyTextBox(email)}><i className="zmdi zmdi-mail-reply"></i></Button>
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </Scrollbars>
        <Dialog
          open={this.state.openConfirmationAlert}
          onClose={this.handleCloseConfirmationAlert}
          aria-labelledby="alert-dialog-title"
          aria-describedby="alert-dialog-description"
        >
          <DialogTitle id="alert-dialog-title">{"Are You Sure Want To Delete?"}</DialogTitle>
          <DialogContent>
            <DialogContentText id="alert-dialog-description">
              This will delete the email permanently from your emails.
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button variant="raised" className="btn-danger text-white" onClick={this.handleCloseConfirmationAlert}>
              <IntlMessages id="button.cancel" />
            </Button>
            <Button variant="raised" color="primary" className="text-white" onClick={() => this.deleteEmail()}>
              <IntlMessages id="button.delete" />
            </Button>
          </DialogActions>
        </Dialog>
        <Dialog
          open={this.state.viewEmailDialog}
          onClose={this.handleCloseConfirmationAlert}
          aria-labelledby="alert-dialog-title"
          aria-describedby="alert-dialog-description"
        >
          <DialogContent>
            {selectedEmail !== null &&
              <div>
                <div className="d-flex justify-content-between">
                  <Media className="mb-10">
                    {selectedEmail.sender_avatar === '' ?
                      <Avatar className="mr-15">{selectedEmail.sender_name.charAt(0)}</Avatar>
                      : <Media object src={selectedEmail.sender_avatar} alt="User Profile 1" className="rounded-circle mr-15" width="40" height="40" />
                    }
                    <Media body>
                      <h5 className="m-0 pt-5 fs-14">{selectedEmail.sender_name}</h5>
                      <span className="fs-12 align-self-center">{selectedEmail.from}</span>
                    </Media>
                  </Media>
                  <span className="small align-self-center">19 Mar 2017</span>
                </div>
                <div className="d-flex justify-content-between">
                  <div className="text-justify">
                    <p className="subject">{selectedEmail.subject}</p>
                    <p className="message">{selectedEmail.message}</p>
                  </div>
                </div>
              </div>
            }
          </DialogContent>
        </Dialog>
        <Snackbar
          anchorOrigin={{
            vertical: 'top',
            horizontal: 'center',
          }}
          open={this.state.snackbar}
          onClose={() => this.setState({ snackbar: false })}
          autoHideDuration={2000}
          snackbarcontentprops={{
            'aria-describedby': 'message-id',
          }}
          message={<span id="message-id">{this.state.snackbarMessage}</span>}
        />
      </Fragment>
    );
  }
}

export default withRouter(NewEmails);

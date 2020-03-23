/**
 * Email Listing Header
 */
import React, { Component } from 'react';
import Checkbox from '@material-ui/core/Checkbox';
import { connect } from 'react-redux';
import Tooltip from '@material-ui/core/Tooltip';
import IconButton from '@material-ui/core/IconButton';
import Menu from '@material-ui/core/Menu';
import MenuItem from '@material-ui/core/MenuItem';

// redux action
import {
    updateSearchEmail,
    searchEmail,
    onDeleteEmail,
    onEmailMoveToFolder,
    selectAllEMails,
    getUnselectedAllEMails,
    addLabelsIntoEmail
} from 'Actions';

import IntlMessage from 'Util/IntlMessages';

class EmailListingHeader extends Component {

    state = {
        anchorEl: null,
        folderMenu: false,
        labelMenu: false
    }

    /**
     * On Delete Email
     */
    deleteEmail() {
        this.props.onDeleteEmail();
    }

    /**
     * Opn Folder Menu
     */
    openFolderMenuOption(e) {
        this.setState({
            folderMenu: true,
            anchorEl: e.currentTarget
        });
    }

    /**
     * On Folder Menu Item Select
     */
    onFolderMenuItemSelect(id) {
        this.props.onEmailMoveToFolder(id);
        this.setState({ anchorEl: null });
    }

    /**
     * Hanlde Request Close
     */
    handleRequestClose = () => {
        this.setState({ anchorEl: null, labelMenu: false, folderMenu: false });
    }

    /**
     * Open Add Labels Menus
     */
    openAddLabelsMenu(e) {
        this.setState({ labelMenu: true, anchorEl: e.currentTarget });
    }

    /**
     * Add Labels Into Mail
     */
    addLabelsIntoMail(label) {
        this.setState({ anchorEl: null, labelMenu: false });
        this.props.addLabelsIntoEmail(label);
    }

    /**
     * Get Email Actions
     */
    getEMailActions() {
        const { folders, labels } = this.props;
        return (
            <div>
                <Tooltip id="tooltip-move-to" title="Move To" placement="top-start">
                    <IconButton onClick={(e) => this.openFolderMenuOption(e)}>
                        <i className="zmdi zmdi-folder" />
                    </IconButton>
                </Tooltip>
                <Tooltip id="tooltip-delete" title="Delete" placement="top-start">
                    <IconButton onClick={() => this.deleteEmail()}>
                        <i className="zmdi zmdi-delete" />
                    </IconButton>
                </Tooltip>
                <Tooltip id="tooltip-add-label" title="Add Labels" placement="top-start">
                    <IconButton onClick={(e) => this.openAddLabelsMenu(e)}>
                        <i className="zmdi zmdi-label-alt" />
                    </IconButton>
                </Tooltip>
                <Menu id="folder-menu"
                    anchorEl={this.state.anchorEl}
                    open={this.state.folderMenu}
                    onClose={this.handleRequestClose}
                    MenuListProps={{
                        style: {
                            width: 150,
                        },
                    }}>
                    {folders.map(folder =>
                        <MenuItem key={folder.id}
                            onClick={() => this.onFolderMenuItemSelect(folder.id)}>
                            <IntlMessage id={folder.title} />
                        </MenuItem>
                    )}
                </Menu>
                <Menu id="label-menu"
                    anchorEl={this.state.anchorEl}
                    open={this.state.labelMenu}
                    onClose={this.handleRequestClose}
                    MenuListProps={{
                        style: {
                            width: 150,
                        },
                    }}>
                    {labels.map((label, key) => (
                        <MenuItem key={key} onClick={() => this.addLabelsIntoMail(label)}>
                            <IntlMessage id={label.name} />
                        </MenuItem>
                    ))}
                </Menu>
            </div>
        );
    }

    /**
     * On Search Email
     */
    onSearchEmail(e) {
        this.props.updateSearchEmail(e.target.value);
        this.props.searchEmail(e.target.value);
    }

    /**
     * On All Email Select
     */
    onAllEMailSelect(e) {
        const selectAll = this.props.selectedEmails < this.props.emails.length;
        if (selectAll) {
            this.props.selectAllEMails();
        } else {
            this.props.getUnselectedAllEMails();
        }
    }

    render() {
        const { emails, selectedEmails } = this.props;
        return (
            <div className="top-head">
                <div className="d-flex justify-content-start">
                    <Checkbox color="primary"
                        indeterminate={selectedEmails > 0 && selectedEmails < emails.length}
                        checked={selectedEmails > 0}
                        onChange={(e) => this.onAllEMailSelect(e)}
                        value="SelectMail"
                    />
                    {(selectedEmails > 0) && this.getEMailActions()}
                </div>
            </div>
        );
    }
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
    return emailApp;
}

export default connect(mapStateToProps, {
    updateSearchEmail,
    searchEmail,
    onDeleteEmail,
    onEmailMoveToFolder,
    selectAllEMails,
    getUnselectedAllEMails,
    addLabelsIntoEmail
})(EmailListingHeader);

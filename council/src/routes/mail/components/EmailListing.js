/**
 * Email Listing
 */
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import classnames from 'classnames';

// redux action
import { readEmail, onSelectEmail, markAsStarEmail } from 'Actions';

// component
import EmailListItem from './EmailListItem';

//Intl Message
import IntlMessages from 'Util/IntlMessages';

class EmailListing extends Component {

    /**
     * Read Email
     */
    readEmail(email) {
        const { match, history } = this.props;
        this.props.readEmail(email);
        history.push(`${match.url}/${email.id}`);
    }

    /**
     * On Select Email
     */
    onSelectEmail(e, email) {
        e.stopPropagation();
        this.props.onSelectEmail(email);
    }

    /**
     * Handle Mark As Star Email
     */
    handleMarkAsStar(e, email) {
        e.stopPropagation();
        this.props.markAsStarEmail(email);
    }

    /**
     * Function to return task label name
     */
    getTaskLabelNames = (taskLabels) => {
        let elements = [];
        const { labels } = this.props;
        for (const taskLabel of taskLabels) {
            for (const label of labels) {
                if (label.value === taskLabel) {
                    let ele = <span key={label.value}
                        className={classnames('badge badge-pill', { 'badge-success': label.value === 1, 'badge-primary': label.value === 2, 'badge-info': label.value === 3, 'badge-danger': label.value === 4 })}
                    >
                        <IntlMessages id={label.name} />
                    </span>;
                    elements.push(ele);
                }
            }
        }
        return elements;
    }

    render() {
        const { emails } = this.props;
        return (
            <ul className="list-unstyled pr-sm-15 pr-md-0 mb-70">
                {(emails && emails.length > 0 && emails !== null) ? emails.map((email, key) => (
                    <EmailListItem
                        email={email}
                        handleMarkAsStar={(e) => this.handleMarkAsStar(e, email)}
                        key={key}
                        onSelectEmail={(e) => this.onSelectEmail(e, email)}
                        onReadEmail={() => this.readEmail(email)}
                        getTaskLabelNames={() => this.getTaskLabelNames(email.email_labels)}
                    />
                ))
                    :
                    <div className="d-flex justify-content-center align-items-center email-blank-wrap">
                        <h4>No Emails Found In Selected Folder</h4>
                    </div>
                }
            </ul>
        );
    }
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
    return emailApp;
}

export default withRouter(connect(mapStateToProps, {
    readEmail,
    onSelectEmail,
    markAsStarEmail
})(EmailListing));

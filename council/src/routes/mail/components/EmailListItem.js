/**
 * Email List Item
 */
import React from 'react';
import IconButton from '@material-ui/core/IconButton';
import classnames from 'classnames';
import Checkbox from '@material-ui/core/Checkbox';
import Avatar from '@material-ui/core/Avatar';

// helpers functions
import { textTruncate } from 'Helpers/helpers';

const EmailListItem = ({ email, onSelectEmail, handleMarkAsStar, onReadEmail, getTaskLabelNames }) => (
    <li className="d-flex justify-content-between align-items-center" onClick={onReadEmail}>
        <div className="d-flex align-items-center w-90">
            <Checkbox
                checked={email.selected}
                onClick={onSelectEmail}
            />
            <IconButton onClick={handleMarkAsStar} className="mr-20 d-none d-sm-block">
                <i className={classnames('ti-star', { 'text-warning': email.starred })}></i>
            </IconButton>
            <div className="emails media">
                {email.from.avatar !== '' ?
                    <img src={email.from.avatar} alt="mail user" className="rounded-circle mr-15 align-self-center" width="40" height="40" />
                    : <Avatar className="mr-15 align-self-center">{email.from.name.charAt(0)}</Avatar>
                }
                <div className="media-body">
                    <p className="mb-5 heading">{email.email_subject}</p>
                    <p className="text-muted fs-12 mb-10">{textTruncate(email.email_content, 80)}</p>
                    <div className="emails-labels d-flex jsutify-content-center">
                        {getTaskLabelNames()}
                    </div>
                </div>
            </div>
        </div>
        <div className="small text-muted">{email.received_time}</div>
    </li>
);

export default EmailListItem;

/**
 * Email App Reducer
 */
import update from 'react-addons-update';
import { NotificationManager } from 'react-notifications';

// action types
import {
    GET_EMAILS,
    GET_EMAIL_SUCCESS,
    GET_EMAIL_FAILURE,
    SET_EMAIL_AS_STAR,
    READ_EMAIL,
    HIDE_LOADING_INDICATOR,
    FETCH_EMAILS,
    ON_SELECT_EMAIL,
    UPDATE_EMAIL_SEARCH,
    SEARCH_EMAIL,
    ON_DELETE_MAIL,
    ON_BACK_PRESS_NAVIGATE_TO_EMAIL_LISTING,
    GET_SENT_EMAILS,
    GET_INBOX,
    GET_DRAFTS_EMAILS,
    GET_SPAM_EMAILS,
    GET_TRASH_EMAILS,
    ON_EMAIL_MOVE_TO_FOLDER,
    UNSELECT_ALL_EMAILS,
    SELECT_ALL_EMAILS,
    ON_SEND_EMAIL,
    EMAIL_SENT_SUCCESSFULLY,
    FILTER_EMAILS_WITH_LABELS,
    ADD_LABELS_INTO_EMAILS
} from 'Actions/types';

// email data
import folders from 'Assets/data/email-app/folders';
import labels from 'Assets/data/email-app/labels';

const INITIAL_STATE = {
    allEmail: null,
    emails: null,
    labels,
    currentEmail: null,
    selectedEmails: 0,
    folders,
    selectedFolder: 0,
    folderMails: [],
    searchEmailText: '',
    noFoundMessage: '',
    selectedMails: 0
};

export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        // get emails
        case GET_EMAILS:
            return { ...state, allEmail: null, emails: null };

        // get emails success
        case GET_EMAIL_SUCCESS:
            return { ...state, allEmail: action.payload, emails: action.payload };

        // get emails failure
        case GET_EMAIL_FAILURE:
            return { ...state, allEmail: null, emails: null };

        // set email as starred
        case SET_EMAIL_AS_STAR:
            let index = state.emails.indexOf(action.payload);
            return update(state, {
                emails: {
                    [index]: {
                        starred: { $set: !action.payload.starred }
                    }
                }
            });

        // read email
        case READ_EMAIL:
            let indexOfReadEmail = state.emails.indexOf(action.payload);
            return update(state, {
                emails: {
                    [indexOfReadEmail]: {
                        selected: { $set: true }
                    }
                },
                loading: { $set: true },
                currentEmail: { $set: action.payload }
            });

        // hide loading indicator
        case HIDE_LOADING_INDICATOR:
            return { ...state, loading: false };

        // fetch emails
        case FETCH_EMAILS:
            const allEmails = state.allEmail && state.allEmail.filter(email => !email.deleted);
            return { ...state, emails: allEmails };

        // on select email
        case ON_SELECT_EMAIL:
            action.payload.selected = !action.payload.selected;
            let selectedEmails = 0;
            const emails = state.emails.map(email => {
                if (email.selected) {
                    selectedEmails++;
                }
                if (email.id === action.payload.id) {
                    if (email.selected) {
                        selectedEmails++;
                    }
                    return action.payload;
                } else {
                    return email;
                }
            }
            );
            return {
                ...state,
                selectedEmails,
                emails
            }

        // update email search
        case UPDATE_EMAIL_SEARCH:
            return { ...state, searchEmailText: action.payload };

        // search emails
        case SEARCH_EMAIL:
            if (action.payload === '') {
                return { ...state, emails: state.allEmail.filter((email) => !email.deleted) };
            } else {
                const searchEmails = state.allEmail.filter((email) =>
                    !email.deleted && email.email_subject.toLowerCase().indexOf(action.payload.toLowerCase()) > -1);
                return { ...state, emails: searchEmails }
            }

        // delete email
        case ON_DELETE_MAIL:
            const mails = state.emails.map(mail => {
                if (mail.selected) {
                    mail.folder = 4;
                    mail.selected = false;
                    mail.deleted = true;
                    return mail;
                } else {
                    return mail;
                }
            });
            NotificationManager.success('Email has been moved to trash!');
            return {
                ...state,
                selectedEmails: 0,
                currentEmail: null,
                loading: true,
                emails: mails.filter(mail => mail.folder === state.selectedFolder)
            }

        // navigate toemail listing page
        case ON_BACK_PRESS_NAVIGATE_TO_EMAIL_LISTING:
            const allmails = state.emails.map(mail => {
                if (mail.selected) {
                    mail.selected = false;
                    return mail;
                } else {
                    return mail;
                }
            });
            return { ...state, currentEmail: null, emails: allmails };

        // get sent emails
        case GET_SENT_EMAILS:
            const sentEmails = state.allEmail && state.allEmail.filter((email) => !email.deleted && email.folder === 1);
            return { ...state, emails: sentEmails, selectedFolder: 1 };

        // get inbox
        case GET_INBOX:
            const inbox = state.allEmail && state.allEmail.filter((email) => !email.deleted && email.folder === 0);
            return { ...state, emails: inbox, selectedFolder: 0 };

        // get drafts
        case GET_DRAFTS_EMAILS:
            const drafts = state.allEmail && state.allEmail.filter((email) => !email.deleted && email.folder === 2);
            return { ...state, emails: drafts, selectedFolder: 2 };

        // spam emails
        case GET_SPAM_EMAILS:
            const spamEmails = state.allEmail && state.allEmail.filter((email) => !email.deleted && email.folder === 3);
            return { ...state, emails: spamEmails, selectedFolder: 3 };

        // trash emails
        case GET_TRASH_EMAILS:
            const trashEmails = state.allEmail && state.allEmail.filter((email) => email.folder === 4);
            return { ...state, emails: trashEmails, selectedFolder: 4 };

        // on email move to selected folder
        case ON_EMAIL_MOVE_TO_FOLDER:
            const folderMails = state.emails.map(mail => {
                if (mail.selected) {
                    mail.folder = action.payload;
                    mail.selected = false
                    return mail;
                } else {
                    return mail;
                }
            });
            NotificationManager.success('Email has been moved successfully!');
            return {
                ...state,
                selectedEmails: 0,
                emails: folderMails.filter(mail => mail.folder === state.selectedFolder)
            };

        // unselect all emails
        case UNSELECT_ALL_EMAILS:
            const unselectedEmails = state.emails.map(mail => {
                mail.selected = false
                return mail;
            });
            return {
                ...state,
                selectedEmails: 0,
                emails: unselectedEmails
            };

        // select all emails
        case SELECT_ALL_EMAILS:
            const selectAllEmails = state.emails.map(mail => {
                mail.selected = true
                return mail
            });
            return {
                ...state,
                selectedEmails: selectAllEmails.length,
                emails: selectAllEmails
            }

        // on send email
        case ON_SEND_EMAIL:
            return { ...state, sendingEmail: true };

        // on email sent successfully
        case EMAIL_SENT_SUCCESSFULLY:
            NotificationManager.success('Email has been sent successfully!');
            return { ...state, sendingEmail: false };

        // filter emails with labels
        case FILTER_EMAILS_WITH_LABELS:
            let filterEmails = state.allEmail.filter((email) => email.email_labels.includes(action.payload.value));
            return { ...state, emails: filterEmails };

        // add lables into email
        case ADD_LABELS_INTO_EMAILS:
            const labelsEmails = state.emails.map(email => {
                if (email.selected) {
                    if (email.email_labels.includes(action.payload.value)) {
                        email.email_labels.splice(email.email_labels.indexOf(action.payload.value), 1);
                        return { ...email, email_labels: email.email_labels };
                    } else {
                        return { ...email, email_labels: email.email_labels.concat(action.payload.value) };
                    }
                } else {
                    return email;
                }
            }
            );
            return {
                ...state,
                emails: labelsEmails
            }

        default: return { ...state };
    }
}
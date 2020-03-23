/**
 * Language Provider Helper Component
 * Used to Display Localised Strings
 */
import React from 'react';
import { FormattedMessage, injectIntl } from 'react-intl';

// Injected message
const InjectMassage = props => <FormattedMessage {...props} />;

export default injectIntl(InjectMassage, {
    withRef: false
});

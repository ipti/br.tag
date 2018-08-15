/**
 * Agency Dashboard
 */

import React, { Component } from 'react'

// intl messages
import IntlMessages from 'Util/IntlMessages';

// page title bar
import PageTitleBar from 'Components/PageTitleBar/PageTitleBar';

export default class AgencyDashboard extends Component {
   render() {
      const { match } = this.props;
      return (
         <div className="agency-dashboard-wrapper">
            <PageTitleBar title={<IntlMessages id="sidebar.agency" />} match={match} />
         </div>
      )
   }
}

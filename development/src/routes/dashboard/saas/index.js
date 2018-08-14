/**
 * SAAS Dashboard
 */
import React, { Component } from 'react'

// intl messages
import IntlMessages from 'Util/IntlMessages';

// page title bar
import PageTitleBar from 'Components/PageTitleBar/PageTitleBar';

export default class saasDashbaord extends Component {
   render() {
      const { match } = this.props;
      return (
         <div className="saas-dashboard">
            <PageTitleBar title={<IntlMessages id="sidebar.saas" />} match={match} />
         </div>
      )
   }
}

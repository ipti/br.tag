/**
 * Home Page
 */

import React, { Component } from 'react'

// intl messages
import IntlMessages from 'Util/IntlMessages';

// page title bar
import PageTitleBar from 'Components/PageTitleBar/PageTitleBar';


export default class Home extends Component {
   render() {
      const { match } = this.props;
      const drawer = (
        <div className="mail-sidebar-wrap">
            <div className="user-mail d-flex justify-content-between p-10">
                <div className="media align-items-center">
                    <img
                        src={require('Assets/avatars/user-15.jpg')}
                        alt="user prof"
                        className="img-fluid rounded-circle mr-10"
                        width="40"
                        height="40"
                    />
                    <div className="media-body mt-1">
                        <h4 className="mb-0">Braxton Hudson</h4>
                        <p className="text-muted mb-0">braxton@example.com</p>
                    </div>
                </div>
            </div>
            <div className="p-20">
                <Button
                    component={NavLink}
                    to={`${match.url}/compose`}
                    raised="true"
                    size="large"
                    className="btn-danger text-white btn-block btn-lg">
                    <i className="zmdi zmdi-border-color mr-20"></i>
                    <IntlMessages id="widgets.composeMail" />
                </Button>
            </div>
            <EmailAppSidebar />
        </div>
    );
      return (
          
         <div className="ecom-dashboard-wrapper">
            <PageTitleBar title={<IntlMessages id="sidebar.ecommerce" />} match={match} />
         </div>
      )
   }
}

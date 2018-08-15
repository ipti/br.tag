/**
 * Agency App
 */
import React, { Component } from 'react';
import { Route, withRouter, Redirect } from 'react-router-dom';

// Agency layout
import RctAgencyLayout from 'Components/RctAgencyLayout';

// router service
import routerService from '../services/_routerService';

class RctAgencyApp extends Component {
   render() {
      const { match, location } = this.props;
      if (location.pathname === '/agency') {
         return (<Redirect to={'/agency/dashboard/ecommerce'} />);
      }
      return (
         <RctAgencyLayout>
            {routerService && routerService.map((route,key)=>
					<Route key={key} path={`${match.url}/${route.path}`} component={route.component} />
				)}
         </RctAgencyLayout>
      );
   }
}

export default withRouter(RctAgencyApp);

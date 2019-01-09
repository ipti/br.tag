/**
 * Pricing Component
 */
import React from 'react';
import { Button } from 'reactstrap';

// component
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

// intl messages
import IntlMessages from 'Util/IntlMessages'

const PricingBlockV1 = ({ planType, type, description, price, users, features, color, buttonText }) => (
   <RctCollapsibleCard customClasses="text-center" colClasses="col-md-4">
      <div className="pricing-icon mb-40">
         <img src={require('Assets/img/pricing-icon.png')} alt="pricing icon" className="img-fluid" width="" height="" />
      </div>
      <h2 className={`text-${color} pricing-title`}><IntlMessages id={type} /></h2>
      <p>{description}</p>
      {planType !== 'free' && <span className="text-muted mb-5 d-block small">Starting at just</span>}
      <div className="mb-25">
         {planType === 'free' ?
            <h2 className="amount-title"><IntlMessages id={price} /></h2>
            : <h2 className="amount-title">${price}<sub>/mo</sub></h2>
         }
         <span className="text-muted small">For {users} user</span>
      </div>
      <ul className="price-detail list-unstyled">
         {features.map((feature, key) => (
            <li key={key}>{feature}</li>
         ))}
      </ul>
      <Button color={color} className='btn-block btn-lg'>
         <IntlMessages id={buttonText} />
      </Button>
   </RctCollapsibleCard>
);

export default PricingBlockV1;

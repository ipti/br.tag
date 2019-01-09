/**
 * Pricing Block V2
 */
import React from 'react';
import IntlMessages from 'Util/IntlMessages';
import ReactTooltip from 'react-tooltip';
import { Button } from 'reactstrap';

const PricingBlockV2 = ({ type, responses, color, features }) => (
   <div className="pricing-box">
      <div className="pricing-head">
         <h2 className={`text-${color} pricing-title mb-0`}>
            <IntlMessages id={type} />
         </h2>
      </div>
      <div className="plan-info">
         <span>{responses}</span>
      </div>
      <div className="pricing-body">
         <ul className="list-unstyled plan-info-listing">
            {features.map((feature, key) => (
               <li className="d-flex justify-align-start" key={key}>
                  <i className="ti-check-box"></i>
                  <a data-tip>{feature}</a>
                  <ReactTooltip place="right" effect="solid" className="rct-tooltip">
                     <span>{feature}</span>
                  </ReactTooltip>
               </li>
            ))}
         </ul>
         <Button color={color} className='btn-block btn-lg'>
            <IntlMessages id="widgets.startToBasic" />
         </Button>
      </div>
   </div>
);

export default PricingBlockV2;

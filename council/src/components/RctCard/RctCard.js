/**
 * Rct Card
 */
import React from 'react';

// rct card heading
import { RctCardHeading } from './RctCardHeading'

const RctCard = ({ children, customClasses, heading, headingCustomClasses, colClasses }) => (
    <div className={colClasses && colClasses}>
        <div className={`rct-block ${customClasses ? customClasses : ''}`}>
            {heading &&
                <RctCardHeading
                    title={heading}
                    customClasses={headingCustomClasses}
                />
            }
            {children}
        </div>
    </div>
);

export { RctCard };

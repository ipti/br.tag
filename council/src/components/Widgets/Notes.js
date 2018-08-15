/**
 * Notes Widget 
 */
/* eslint-disable */
import React from 'react';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const Notes = () => (
  <div className="lazy-up">
    <div className="card pt-30 mb-20">
      <span className="text-pink d-block mb-5"><IntlMessages id="widgets.note" /></span>
      <p className="fs-14 mb-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
      Lorem Ipsum has text ever since the 1500.</p>
    </div>
  </div>
);

export default Notes;

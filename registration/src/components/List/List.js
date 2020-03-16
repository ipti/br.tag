import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

const List = ({ items, children }) => {

  if(!items.length){
    return children;
  }

  return (
    <Fragment>
      {
        items.map((item, key) =>
          <Fragment key={key}>
            {item}
          </Fragment >
        )
      }
    </Fragment>
  )

}

List.propTypes = {
  items: PropTypes.array.isRequired,
};

List.defaultProps = {
  items: []
};


export default List;
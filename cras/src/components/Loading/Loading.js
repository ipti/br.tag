import React from 'react';
import CircularProgress from '@material-ui/core/CircularProgress';

import { styles } from './styles';

const Loading = ({ top = '30%', left = '40%' }) => {
  const classes = styles({ top, left });

  return (
    <div className={classes.root}>
      <CircularProgress color="primary" />
    </div>
  );
};

export default Loading;

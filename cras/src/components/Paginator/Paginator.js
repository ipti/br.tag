import React, { useState } from 'react';
import Pagination from '@material-ui/lab/Pagination';
import { ThemeProvider } from '@material-ui/core/styles';

import { theme } from './styles';

const Paginator = (props) => {
  const [page, setPage] = useState(1);
  const handleChange = (event, value) => {
    setPage(value);
    props.handlePage(value);
  };

  return (
    <ThemeProvider theme={theme}>
      <Pagination count={props.count} color="primary" page={page} onChange={handleChange} />
    </ThemeProvider>
  );
};

export default Paginator;

/**
 * Rct Section Loader
 */
import React from 'react';
import CircularProgress from '@material-ui/core/CircularProgress';

const RctSectionLoader = () => (
    <div className="d-flex justify-content-center loader-overlay">
        <CircularProgress />
    </div>
);

export default RctSectionLoader;

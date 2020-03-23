/**
 * App Bar
 */

import React from 'react';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import { Link } from 'react-router-dom';

const SimpleBar = () => (
    <div>
        <AppBar position="static" color="primary">
            <Toolbar>
                <div className="site-logo">
                    <Link to="/" className="logo-normal">
                        <img src={require('Assets/img/appLogoText.png')} className="img-fluid" alt="site-logo" width="67" height="17" />
                    </Link>
                </div>
            </Toolbar>
        </AppBar>
    </div>
);

export default SimpleBar;

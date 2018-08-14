/**
 * Horizontal Menu
 */
import React, { Component } from 'react';

import IntlMessages from 'Util/IntlMessages';

import navLinks from './NavLinks';

import NavMenuItem from './NavMenuItem';

class HorizontalMenu extends Component {
    render() {
        return (
            <div className="horizontal-menu">
                <ul className="list-unstyled nav">
                    <li className="nav-item">
                        <a href="javascript:void(0);" className="nav-link">
                            <i className="zmdi zmdi-view-dashboard"></i>
                            <span className="menu-title"><IntlMessages id="sidebar.general" /></span>
                        </a>
                        <ul className="list-unstyled sub-menu">
                            {navLinks.category1.map((menu, key) => (
                                <NavMenuItem
                                    menu={menu}
                                    key={key}
                                />
                            ))}
                        </ul>
                    </li>
                </ul>
            </div>
        );
    }
}

export default HorizontalMenu;

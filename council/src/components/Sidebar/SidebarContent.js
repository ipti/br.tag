/**
 * Sidebar Content
 */
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListSubheader from '@material-ui/core/ListSubheader';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';

import IntlMessages from 'Util/IntlMessages';

import NavMenuItem from './NavMenuItem';

// redux actions
import { onToggleMenu } from 'Actions';

class SidebarContent extends Component {

    toggleMenu(menu, stateCategory) {
        let data = {
            menu,
            stateCategory
        }
        this.props.onToggleMenu(data);
    }

    render() {
        const { sidebarMenus } = this.props.sidebar;
        return (
            <div className="rct-sidebar-nav">
                <nav className="navigation">
                    <List
                        className="rct-mainMenu p-0 m-0 list-unstyled"
                        subheader={
                            <ListSubheader className="side-title" component="li">
                                <IntlMessages id="sidebar.general" />
                            </ListSubheader>}
                    >
                        {sidebarMenus.complaint.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'complaint')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.notification.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'notification')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.food.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'food')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.fact.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'fact')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.housing.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'housing')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.warning.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'warning')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.report.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'report')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.service.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'service')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.ficai.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'ficai')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.people.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'people')}
                            />
                        ))}
                    </List>
                    
                </nav>
            </div>
        );
    }
}

// map state to props
const mapStateToProps = ({ sidebar }) => {
    return { sidebar };
};

export default withRouter(connect(mapStateToProps, {
    onToggleMenu
})(SidebarContent));

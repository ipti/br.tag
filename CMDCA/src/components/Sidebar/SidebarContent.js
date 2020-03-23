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
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.schedule.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'schedule')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.record.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'record')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.advisor.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'advisor')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.notice.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'notice')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.finances.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'finances')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.news.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'news')}
                            />
                        ))}
                    </List>
                    <List className="rct-mainMenu p-0 m-0 list-unstyled" >
                        {sidebarMenus.resolution.map((menu, key) => (
                            <NavMenuItem
                                menu={menu}
                                key={key}
                                onToggleMenu={() => this.toggleMenu(menu, 'resolution')}
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

/**
 * Rct Horizontal Menu Layout
 */
import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import { Scrollbars } from 'react-custom-scrollbars';

// Components
import Header from 'Components/Header/Header';
import Footer from 'Components/Footer/Footer';
import HorizontalMenu from 'Components/HorizontalMenu/HorizontalMenu';
import ThemeOptions from 'Components/ThemeOptions/ThemeOptions';

class RctHorizontalLayout extends Component {

    renderPage() {
        const { pathname } = this.props.location;
        const { children, match } = this.props;
        if (pathname === `${match.url}/chat` || pathname.startsWith(`${match.url}/mail`) || pathname === `${match.url}/todo`) {
            return (
                <div className="rct-page-content p-0">
                    {children}
                </div>
            );
        }
        return (
            <Scrollbars
                className="rct-scroll"
                autoHide
                autoHideDuration={100}
                style={{ height: 'calc(100vh - 100px)' }}
            >
                <div className="rct-page-content">
                    {children}
                    <Footer />
                </div>
            </Scrollbars>
        );
    }

    render() {
        return (
            <div className="app-horizontal collapsed-sidebar">
                <div className="app-container">
                    <div className="rct-page-wrapper">
                        <div className="rct-app-content">
                            <div className="app-header">
                                <Header horizontalMenu />
                            </div>
                            <div className="rct-page">
                                <HorizontalMenu />
                                {this.renderPage()}
                            </div>
                            <ThemeOptions />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default withRouter(RctHorizontalLayout);

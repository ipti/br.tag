/**
 * Social Feeds Widget
 */
import React from 'react';

const SocialFeedsWidget = ({ type, friendsCount, feedsCount, icon }) => (
    <div className="social-card">
        <span className={`rounded-circle social-icon ${type}`}><i className={`${type} ${icon}`}></i></span>
        <span>
            <span className="font-weight-bold">{friendsCount}</span>
            <span className="fs-14">Friends</span>
        </span>
        <span>
            <span className="font-weight-bold">{feedsCount}</span>
            <span className="fs-14">Feeds</span>
        </span>
    </div>
);

export default SocialFeedsWidget;

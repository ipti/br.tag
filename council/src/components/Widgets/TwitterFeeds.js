/**
 * Twitter Feeds Widget
 */
import React, { Component } from 'react';
import { Timeline } from 'react-twitter-widgets'

export default class TwitterFeed extends Component {
    render() {
        return (
            <Timeline
                dataSource={{
                    sourceType: 'profile',
                    screenName: 'envato'
                }}
                options={{
                    username: 'envato',
                    height: '400'
                }}
            />
        );
    }
}

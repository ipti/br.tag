/**
 * Online Visitor
 */
import React from 'react';
import { VectorMap } from 'react-jvectormap';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// chart config
import ChartConfig from 'Constants/chart-config';

const OnlineVisitorsWidget = ({ data }) => (
    <div className="card">
        <h4 className="card-title"><span className="text-info">{data.totalVisitors} </span>
            <IntlMessages id="widgets.onlineVistors" />
        </h4>
        <div style={{ width: '100%', height: 170 }}>
            <VectorMap map={'world_mill'}
                backgroundColor={ChartConfig.color.white}
                containerStyle={{
                    width: '100%',
                    height: '100%'
                }}
                regionStyle={{
                    initial: {
                        fill: ChartConfig.color.default
                    }
                }}
                markerStyle={{
                    initial: {
                        fill: ChartConfig.color.info,
                        stroke: ChartConfig.color.white
                    }
                }}
                zoomButtons={false}
                zoomOnScroll={false}
                containerClassName="map"
                markers={data.markers}
            />
        </div>
    </div>
);

export default OnlineVisitorsWidget;

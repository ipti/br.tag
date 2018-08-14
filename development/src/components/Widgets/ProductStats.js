/**
 * Product Stats Widget
 */
import React, { Component, Fragment } from 'react';

// chart
import ProductStatsChart from 'Components/Charts/ProductStatsChart';

class ProductStats extends Component {
    render() {
        const { data } = this.props;
        return (
            <Fragment>
                <div className="chart-top mb-4">
                    {data.customLegends.map((legend, key) => (
                        <Fragment key={key}>
                            <span className={`${legend.class} ladgend mr-10`}>&nbsp;</span>
                            <span className="fs-12 mr-10">{legend.name}</span>
                        </Fragment>
                    ))}
                </div>
                <ProductStatsChart
                    labels={data.labels}
                    datasets={data.datasets}
                />
            </Fragment>
        );
    }
}

export default ProductStats;

/**
 * Ratings Stats
 */
import React, { Component } from 'react';
import { Progress } from 'reactstrap';
import { Link } from 'react-router-dom';
import Button from '@material-ui/core/Button';
import StarRatingComponent from 'react-star-rating-component';

// rct card
import { RctCard, RctCardContent, RctCardFooter } from '../RctCard';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// app config
import AppConfig from 'Constants/AppConfig';

class RatingsStats extends Component {

    state = {
        rating: 4.5
    }

    onStarClick(nextValue, prevValue, name) {
        this.setState({ rating: nextValue });
    }

    render() {
        const { rating } = this.state;
        return (
            <RctCard
                customClasses="overflow-hidden"
                heading={<IntlMessages id="widgets.ratings" />}
            >
                <RctCardContent noPadding>
                    <div className="p-20 d-flex justify-content-between">
                        <span className="fs-14">Average Ratings</span>
                        <StarRatingComponent
                            name="rate2"
                            starCount={5}
                            value={rating}
                            starColor={AppConfig.themeColors.warning}
                            emptyStarColor={AppConfig.themeColors.dark}
                            onStarClick={this.onStarClick.bind(this)}
                            renderStarIcon={() => <i className="fa-star-o fa font-lg mr-5"></i>}
                            renderStarIconHalf={() => <i className="fa-star-half-o fa font-lg mr-5"></i>}
                        />
                        <span className="fs-14">4.89 out of 122 Ratings</span>
                    </div>
                    <h2 className="report-title">Rating Count</h2>
                    <div className="table-responsive">
                        <table className="table table-borderless table-middle mb-0">
                            <tbody>
                                <tr>
                                    <td>5 Star</td>
                                    <td className="w-50"><Progress color="yellow" value={85} /></td>
                                    <td>7.14%</td>
                                </tr>
                                <tr>
                                    <td>4 Star</td>
                                    <td className="w-50"><Progress color="yellow" value={55} /></td>
                                    <td>6.14%</td>
                                </tr>
                                <tr>
                                    <td>3 Star</td>
                                    <td className="w-50"><Progress color="yellow" value={75} /></td>
                                    <td>3.14%</td>
                                </tr>
                                <tr>
                                    <td>2 Star</td>
                                    <td className="w-50"><Progress color="yellow" value={65} /></td>
                                    <td>2%</td>
                                </tr>
                                <tr>
                                    <td>1 Star</td>
                                    <td className="w-50"><Progress color="yellow" value={25} /></td>
                                    <td>1%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </RctCardContent>
                <RctCardFooter>
                    <Button component={Link} to="/app/pages/report" variant="raised" color="primary" className="bg-primary">
                        View All
                    </Button>
                </RctCardFooter>
            </RctCard>
        );
    }
}

export default RatingsStats;

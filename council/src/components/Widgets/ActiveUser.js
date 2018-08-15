/**
 * Active User Component
 */
import React, { Component, Fragment } from 'react';
import List from '@material-ui/core/List'
import ListItem from '@material-ui/core/ListItem'
import { Scrollbars } from 'react-custom-scrollbars';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const data = [
    {
        id: 1,
        flag: 'icons8-usa',
        countryName: 'United States',
        userCount: 150,
        userPercent: 20,
        status: 1
    },
    {
        id: 2,
        flag: 'icons8-hungary',
        countryName: 'Hungary',
        userCount: 180,
        userPercent: -5,
        status: 0
    },
    {
        id: 3,
        flag: 'icons8-france',
        countryName: 'France',
        userCount: 86,
        userPercent: 20,
        status: 1
    },
    {
        id: 4,
        flag: 'icons8-japan',
        countryName: 'Japan',
        userCount: 243,
        userPercent: 20,
        status: 1
    },
    {
        id: 5,
        flag: 'icons8-china',
        countryName: 'China',
        userCount: 155,
        userPercent: 20,
        status: 0
    },
    {
        id: 6,
        flag: 'ru',
        countryName: 'Russia',
        userCount: 155,
        userPercent: 20,
        status: 0
    }
];

export default class ActiveUser extends Component {
    render() {
        return (
            <Fragment>
                <div className="rct-block-title border-0 text-white bg-primary">
                    <h3 className="d-flex justify-content-between mb-0 font-weight-light">
                        <span><IntlMessages id="widgets.activeUsers" /></span>
                        <span>250</span>
                    </h3>
                    <p className="fs-12 mb-0 font-weight-light"><IntlMessages id="widgets.updated10Minago" /></p>
                </div>
                <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={380} autoHide>
                    <List className="list-unstyled p-0">
                        {(data && data.length > 0) && data.map((data, key) => (
                            <ListItem key={key} className="border-bottom d-flex justify-content-between align-items-center p-20">
                                <div className="w-60 d-flex">
                                    <div className="flag-img mr-30">
                                        <img src={require(`Assets/flag-icons/${data.flag}.png`)} alt="flag-img" className="img-fluid" width="44" height="30" />
                                    </div>
                                    <span>{data.countryName}</span>
                                </div>
                                <div className="w-40 d-flex justify-content-between">
                                    <span>{data.userCount}</span>
                                    <div>
                                        {data.status === 1 ?
                                            (<i className="ti-arrow-up mr-10 text-success"></i>)
                                            :
                                            (<i className="ti-arrow-down mr-10 text-danger"></i>)
                                        }
                                        <span>{data.userPercent}%</span>
                                    </div>
                                </div>
                            </ListItem>
                        ))}
                    </List>
                </Scrollbars>
            </Fragment>
        )
    }
}

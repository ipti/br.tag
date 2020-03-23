/**
 * Social Share Widget
 */
import React, { Component } from 'react';

// inlt messages
import IntlMessages from 'Util/IntlMessages';

export default class ShareFriends extends Component {
  render() {
    return (
      <div className="share-friend d-flex justify-content-between bg-dark rct-block py-40 px-30">
        <div className="w-70">
          <h3 className="text-white border-0 mb-3"><IntlMessages id="widgets.ShareWithFriends" /></h3>
          <p className="mb-3 text-white"><IntlMessages id="widgets.helpToShareText" /></p>
          <ul className="social-share list-inline">
            <li className="list-inline-item"><a href="javascript:void(0)" className="bg-facebook"><i className="ti-facebook"></i></a></li>
            <li className="list-inline-item"><a href="javascript:void(0)" className="bg-twitter"><i className="ti-twitter-alt"></i></a></li>
            <li className="list-inline-item"><a href="javascript:void(0)" className="bg-instagram"><i className="ti-instagram"></i></a></li>
            <li className="list-inline-item"> <a href="javascript:void(0)" className="bg-google"><i className="ti-google"></i></a></li>
          </ul>
        </div>
        <div className="py-20 d-xs-none">
          <img className="img-fluid" alt="share" src={require('Assets/img/share.png')} />
        </div>
      </div>
    )
  }
};

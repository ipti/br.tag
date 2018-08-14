//Twitter Feeds v2 widget

import React, { Component, Fragment } from 'react';
import { Badge } from 'reactstrap';
import Slider from "react-slick";

const twitterFeeds = [
   {
      id: 1,
      userAvatar: require("Assets/img/user-1.jpg"),
      tweets: "Contrary to popular belief,<a href=\"javascript:void(0)\">#LoremIpsum<\/a> is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney <a href=\"javascript:void(0)\">t.co/Milksed/t.co/43jffse3<\/a>"
   },
   {
      id: 2,
      userAvatar: require("Assets/img/user-2.jpg"),
      tweets: "Contrary to popular belief,<a href=\"javascript:void(0)\">#LoremIpsum<\/a> is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney <a href=\"javascript:void(0)\">t.co/Milksed/t.co/43jffse3<\/a>"
   },
   {
      id: 3,
      userAvatar: require("Assets/img/user-3.jpg"),
      tweets: "Contrary to popular belief,<a href=\"javascript:void(0)\">#LoremIpsum<\/a> is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney <a href=\"javascript:void(0)\">t.co/Milksed/t.co/43jffse3<\/a>"
   }
]

export default class TwitterFeedsV2 extends Component {
   render() {
      const settings = {
         dots: false,
         infinite: true,
         speed: 500,
         arrows:false,
         slidesToShow: 1,
         slidesToScroll: 1,
         autoplay: false,
      };
      return (
         <Fragment>
            <Slider {...settings}>
               {twitterFeeds && twitterFeeds.map((twitter, key) => (
                  <div className="twitter-content" key={twitter.id}>
                     <div className="avatar-wrap d-flex align-itmes-start justify-content-center">
                        <div className="overlay-wrap">
                           <img src={twitter.userAvatar} alt="user-avatar" height="80" width="80" className="img-fluid rounded-circle" />
                           <div className="overlay-content">
                              <Badge color="primary" className="p-0 rounded-circle d-flex align-items-center justify-content-center">
                                 <i className="zmdi zmdi-twitter"></i>
                              </Badge>
                           </div>
                        </div>
                     </div>
                     <div className="text-center px-20">
                        <p className="mb-0" dangerouslySetInnerHTML={{ __html: twitter.tweets }} />
                     </div>
                  </div>
               ))}
            </Slider>
         </Fragment>
      )
   }
}

//Top Authors widget

import React, { Component } from 'react'
import Slider from "react-slick";

const topAuthors = [
   {
      id: 1,
      name: "Natasha Knight",
      avatarSrc:require("Assets/img/user-1.jpg"),
      phone: "+01 2345 67890",
      email: "natasha@example.com",
      address: "E-51 Phase-1 Mohali",
      articles:200,
      followers: 1400,
      likes:580
   },
   {
      id: 2,
      name: "Lisa Roy",
      avatarSrc: require("Assets/img/user-2.jpg"),
      phone: "+01 2345 67890",
      email: "lisa@example.com",
      address: "London United Kingdom",
      articles: 50,
      followers: 400,
      likes: 200
   },
   {
      id: 3,
      name: "Andre Hicks",
      avatarSrc: require("Assets/img/user-3.jpg"),
      phone: "+01 2345 67890",
      email: "hicksandre@example.com",
      address: "778 Nicole Station Suite 903",
      articles: 75,
      followers: 1700,
      likes: 2000
   },
   {
      id: 4,
      name: "Jhon Smith",
      avatarSrc: require("Assets/img/user-4.jpg"),
      phone: "+01 2345 67890",
      email: "jhon@example.com",
      address: "E-51 Phase-1 Mohali",
      articles: 175,
      followers: 1200,
      likes: 1800
   }
]

export default class TopAuthors extends Component {
   render() {
      const settings = {
         dots: false,
         infinite: true,
         speed: 500,
         slidesToShow: 1,
         slidesToScroll: 1,
         autoplay: true,
      };
      return (
         <div className="top-author-wrap rct-block">
            <div className="bg-primary text-white pt-4 rounded-top">
               <h4 className="mb-0 text-center">Top Authors</h4>
            </div>
            <Slider {...settings}>
               {topAuthors && topAuthors.map((author, key) => (
                  <div key={author.id} className="author-detail-wrap d-flex justify-content-between flex-column">
				         <div className="author-avatar bg-primary overlay-wrap mb-5">
                        <div className="avatar-img">
                           <img src={author.avatarSrc} width="80" height="80" className="img-fluid mx-auto rounded-circle" alt="avtar" />
                        </div>
                     </div>
                     <div className="p-3 authors-info">
                        <h5>{author.name}</h5>
                        <ul className="list-unstyled author-contact-info mb-2">
                           <li>
                              <span className="mr-3"><i className="zmdi zmdi-phone-msg"></i></span>
                              <a href="tel:123456" className="font-xs text-muted">{author.phone}</a>
                           </li>
                           <li>
                              <span className="mr-3"><i className="zmdi zmdi-email"></i></span>
                              <a href="mailto:joan_parisian@gmail.com" className="font-xs text-muted">{author.email}</a>
                           </li>
                           <li>
                              <span className="mr-3"><i className="zmdi zmdi-pin"></i></span>
                              <span className="font-xs text-muted">{author.address}</span>
                           </li>
                        </ul>
                        <ul className="d-flex social-info list-unstyled">
                           <li><a className="facebook" href="javascript:void(0)"><i className="zmdi zmdi-facebook-box"></i></a></li>
                           <li><a className="twitter" href="javascript:void(0)"><i className="zmdi zmdi-twitter-box"></i></a></li>
                           <li><a className="linkedin" href="javascript:void(0)"><i className="zmdi zmdi-linkedin-box"></i></a></li>
                           <li><a className="instagram" href="javascript:void(0)"><i className="zmdi zmdi-instagram"></i></a></li>
                        </ul>
                     </div>
                     <ul className="d-flex list-unstyled footer-content text-center w-100 border-top mb-0">
                        <li>
                           <h5 className="mb-0">{author.articles}</h5>
                           <span className="font-xs text-muted">Articles</span>
                        </li>
                        <li>
                           <h5 className="mb-0">{author.followers}</h5>
                           <span className="font-xs text-muted">Followers</span>
                        </li>
                        <li>
                           <h5 className="mb-0">{author.likes}</h5>
                           <span className="font-xs text-muted">Likes</span>
                        </li>
                     </ul>
                  </div>
               ))}
            </Slider>
         </div>
      )
   }
}

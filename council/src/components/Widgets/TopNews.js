//Top News Widget

import React, { Component } from 'react'
import Slider from "react-slick";

//Helper
import { textTruncate} from "Helpers/helpers";


const newsData = [
   {
      id: 1,
      newsSrc:require("Assets/img/news-slide-1.jpg"),
      newsTitle: "#WorldPopulationDay: India Likely to Overtake China by 2022",
      newsContent: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
      likes: "1k",
      views: "3k",
      comments:"200"
   },
   {
      id: 2,
      newsSrc: require("Assets/img/news-slide-2.jpg"),
      newsTitle: "Check status of Mumbai local, long-distance trains as rains continue",
      newsContent: "when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries",
      likes: "2k",
      views: "5k",
      comments: "600"
   },
   {
      id: 3,
      newsSrc: require("Assets/img/news-slide-3.jpg"),
      newsTitle: "Croatia lowest ranked team in history to reach World Cup final",
      newsContent: "It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, ",
      likes: "500",
      views: "1k",
      comments: "300"
   },
   {
      id: 4,
      newsSrc: require("Assets/img/news-slide-4.jpg"),
      newsTitle: "Telecom Commission approves net neutrality, new telecom policy",
      newsContent: "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old",
      likes: "4k",
      views: "8k",
      comments: "1k"
   },
   {
      id: 5,
      newsSrc: require("Assets/img/news-slide-5.jpg"),
      newsTitle: "Vistara orders Boeing, Airbus jets worth $3.1 billion",
      newsContent: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
      likes: "100",
      views: "500",
      comments: "50"
   }
]

export default class TopNews extends Component {
   state = {
      settings1: undefined,
      settings2: undefined
   }
   componentDidMount() {
      this.setState({
         settings1: this.settings1,
         settings2: this.settings2
      })
   }
   render() {
      const settings1 = {
         slidesToShow: 1,
         slidesToScroll: 1,
         arrows: false,
         fade: true,
         adaptiveHeight: true,
         ref: (slider) => (this.settings1 = slider),
         asNavFor: this.state.settings2,
      };
      const settings2 = {
         slidesToShow: 4,
         slidesToScroll: 1,
         dots: false,
         autoplay: true,
         speed: 2000,
         infinite: true,
         cssEase: "linear",
         focusOnSelect: true,
         ref: (slider) => (this.settings2 = slider),
         asNavFor: this.state.settings1,
         rtl: false,
         adaptiveHeight: true,
         responsive: [
            {
               breakpoint: 1200,
               settings: {
                  slidesToShow: 3,
                  slidesToScroll: 1,
               }
            },
            {
               breakpoint: 991,
               settings: {
                  slidesToShow: 4,
                  slidesToScroll: 1,
               }
            }
         ]
      };
      return (
         <div className="top-news-wrap rct-block">
            <Slider {...settings1} className="main-slider-wrap">
               {newsData && newsData.map((news, key) => (
                  <div key={news.id} className="main-slider-item overlay-wrap">
                     <img src={news.newsSrc} alt="top-news" height="300" width="700" className="img-fluid  rounded-top"/>
                     <div className="overlay-content d-flex justify-content-end align-items-end rounded-top">
                        <div className="overlay-holder p-3 mb-50 w-50">
                           <h4 className="mb-2 text-white">{news.newsTitle}</h4>
                           <div className="meta-info d-flex align-items-center font-xs text-white">
                              <span className="mx-2">
                                 <i className="zmdi zmdi-thumb-up mr-2"></i>
                                 <span>{news.likes}</span>
                              </span>
                              <span className="mx-2">
                                 <i className="zmdi zmdi-eye mr-2"></i>
                                 <span>{news.views}</span>
                              </span>
                              <span className="mx-2">
                                 <i className="zmdi zmdi-comment-text mr-2"></i>
                                 <span>{news.comments}</span>
                              </span>
                           </div>
                           <p className="mb-0 mt-2 font-xs text-white">{textTruncate(news.newsContent,100)}</p>
                        </div>
                     </div>
                  </div>
               ))}
            </Slider>
            <Slider {...settings2} className="slider-btn-wrap">
               {newsData && newsData.map((news, key) => (
                  <div key={news.id} className="slider-btn my-2">
                     <img src={news.newsSrc} alt="top-news" height="90" width="175" className="img-fluid mx-auto" />
                  </div>
               ))}
            </Slider>
         </div>
      )
   }
}

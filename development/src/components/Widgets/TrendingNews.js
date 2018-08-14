//Trending News Widget

import React, { Component } from 'react'
import Slider from "react-slick";


const trendingData = [
   {
      id: 1,
      date: "01",
      month: "July",
      title: "Telecom Commission approved new telecom policy.",
      body: "Dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat…"
   },
   {
      id: 2,
      date: "05",
      month: "July",
      title: "Telecom Commission ",
      body: "Dolor sit amet,consectetuer edipiscing elit"
   },
   {
      id: 3,
      date: "07",
      month: "July",
      title: "Approved new telecom policy.",
      body: "Sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat…"
   },
   {
      id: 4,
      date: "10",
      month: "July",
      title: "Telecom Commission approved new telecom policy.",
      body: "Dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat…"
   },
   {
      id: 5,
      date: "12",
      month:"July",
      title: "Telecom Commission ",
      body: "Dolor sit amet,consectetuer edipiscing elit"
   },
]

export default class TrendingNews extends Component {
   render() {
      const settings = {
         dots: false,
         infinite: true,
         speed: 500,
         slidesToShow: 1,
         slidesToScroll: 1,
         autoplay: true,
         vertical: true,
         responsive: [
            {
               breakpoint: 600,
               settings: {
                  arrows:false
               }
            }
         ]
      };
      return (
         <div className="trending-news-widegt d-flex align-items-center">
            <div className="d-flex align-items-center trending-block">
               <h2 className="mb-0 text-white">Trending</h2>
               <i className="zmdi zmdi-flash ml-3 zmdi-hc-2x text-white"></i>
            </div>
            <Slider {...settings} className="p-4">
               {trendingData && trendingData.map((news, key) => (
                  <div className="slider-content" key={news.id}>
                     <div className="d-flex align-items-center ">
                        <div className="month-wrap text-white mr-4 text-center">
                           <span className="date d-block font-2x fw-bold">{news.date}</span>
                           <span className="month font-xs d-block">{news.month}</span>
                        </div>
                        <div className="slider-text-wrap">
                           <h4 className="mb-0 text-white">{news.title}</h4>
                           <p className="mb-0 font-xs text-white">{news.body}</p>
                        </div>
                     </div>
                  </div>
               ))}
            </Slider>
         </div>
      )
   }
}

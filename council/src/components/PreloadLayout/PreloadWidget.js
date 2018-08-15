/**
 * Preload Widget
 */
import React from 'react';
import ContentLoader from 'react-content-loader';

const PreloadWidget = () => (
   <div className="rounded preload-widget mb-30">
      <div className="widget-headings border-bottom">
         <ContentLoader
            speed={1}
            width={520}
            height={61}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <rect x="15" y="18" rx="0" ry="0" width="160" height="35" />
            <rect x="380" y="18" rx="0" ry="0" width="35" height="35" />
            <circle cx="441" cy="35" r="20" />
            <rect x="470" y="18" rx="0" ry="0" width="35" height="35" />
         </ContentLoader>
      </div>
      <div>
         <ContentLoader
            speed={1}
            width={520}
            height={310}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <rect x="15" y="30" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="60" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="90" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="120" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="150" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="180" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="210" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="240" rx="0" ry="0" width="490" height="16" />
            <rect x="15" y="270" rx="0" ry="0" width="490" height="16" />
         </ContentLoader>
      </div>
      <div className="widget-headings border-top">
         <ContentLoader
            speed={1}
            width={520}
            height={61}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <rect x="15" y="18" rx="0" ry="0" width="160" height="35" />
            <rect x="320" y="18" rx="0" ry="0" width="189" height="35" />
         </ContentLoader>
      </div>
   </div>
);

export default PreloadWidget;

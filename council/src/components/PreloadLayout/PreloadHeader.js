/**
 * Preload Header
 */
import React from 'react';
import ContentLoader from 'react-content-loader';

const PreloadHeader = () => (
   <div className="px-md-4 px-3 preload-header">
      <div className="d-md-block d-none">
         <ContentLoader
            speed={1}
            width={1760}
            height={64}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <circle cx="25" cy="35" r="26" />
            <rect x="58" y="14" rx="0" ry="0" width="140" height="42" />
            <rect x="210" y="14" rx="0" ry="0" width="500" height="42" />

            <rect x="1225" y="14" rx="0" ry="0" width="45" height="42" />
            <rect x="1280" y="14" rx="0" ry="0" width="120" height="42" />
            <rect x="1410" y="14" rx="0" ry="0" width="140" height="42" />
            <circle cx="1580" cy="35" r="23" />
            <circle cx="1630" cy="35" r="23" />
            <rect x="1660" y="14" rx="0" ry="0" width="45" height="42" />
            <rect x="1715" y="14" rx="0" ry="0" width="45" height="42" />
         </ContentLoader>
      </div>
      <div className="d-md-none d-block py-2 py-md-0">
         <ContentLoader
            speed={1}
            width={750}
            height={50}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <circle cx="23" cy="27" r="23" />
            <circle cx="80" cy="27" r="23" />

            <rect x="415" y="6" rx="0" ry="0" width="40" height="40" />
            <rect x="465" y="6" rx="0" ry="0" width="80" height="40" />
            <rect x="555" y="6" rx="0" ry="0" width="60" height="40" />
            <circle cx="640" cy="25" r="21" />
            <circle cx="685" cy="25" r="21" />
            <rect x="710" y="6" rx="0" ry="0" width="40" height="40" />
         </ContentLoader>
      </div>
   </div>
);

export default PreloadHeader;
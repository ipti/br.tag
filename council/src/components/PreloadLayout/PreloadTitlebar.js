/**
 * Preload Title Bar
 */
import React from 'react';
import ContentLoader from 'react-content-loader';

const PreloadTitlebar = () => (
   <div className="mb-30">
      <ContentLoader
         speed={1}
         width={1760}
         height={75}
         primaryColor="rgba(0,0,0,0.05)"
         secondaryColor="rgba(0,0,0,0.04)"
      >
         <rect x="0" y="30" rx="0" ry="0" width="160" height="60" />
         <rect x="1540" y="30" rx="0" ry="0" width="220" height="60" />
      </ContentLoader>
   </div>
);

export default PreloadTitlebar;
/**
 * Preload Sidebar
 */
import React from 'react';
import ContentLoader from 'react-content-loader';

const PreloadSidebar = () => (
   <div className="rct-sidebar preloader-bg">
      <div className="sidebar-top px-4 py-3">
         <ContentLoader
            speed={1}
            width={260}
            height={150}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <rect x="0" y="0" rx="0" ry="0" width="260" height="50" />
            <circle cx="37" cy="110" r="35" />
            <rect x="90" y="95" rx="0" ry="0" width="170" height="30" />
         </ContentLoader>
      </div>
      <div className="sidebar-nav px-4">
         <ContentLoader
            speed={1}
            width={260}
            height={930}
            primaryColor="rgba(0,0,0,0.05)"
            secondaryColor="rgba(0,0,0,0.04)"
         >
            <rect x="0" y="0" rx="0" ry="0" width="90" height="12" />

            <circle cx="20" cy="50" r="15" />
            <rect x="50" y="44" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="90" r="15" />
            <rect x="50" y="84" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="130" r="15" />
            <rect x="50" y="124" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="170" r="15" />
            <rect x="50" y="164" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="210" r="15" />
            <rect x="50" y="204" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="250" r="15" />
            <rect x="50" y="244" rx="0" ry="0" width="180" height="15" />

            <rect x="0" y="290" rx="0" ry="0" width="90" height="12" />

            <circle cx="20" cy="335" r="15" />
            <rect x="50" y="329" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="375" r="15" />
            <rect x="50" y="369" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="415" r="15" />
            <rect x="50" y="409" rx="0" ry="0" width="180" height="15" />

            <rect x="0" y="455" rx="0" ry="0" width="90" height="12" />

            <circle cx="20" cy="500" r="15" />
            <rect x="50" y="494" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="540" r="15" />
            <rect x="50" y="534" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="580" r="15" />
            <rect x="50" y="574" rx="0" ry="0" width="180" height="15" />

            <rect x="0" y="620" rx="0" ry="0" width="90" height="12" />

            <circle cx="20" cy="665" r="15" />
            <rect x="50" y="659" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="705" r="15" />
            <rect x="50" y="699" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="745" r="15" />
            <rect x="50" y="739" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="785" r="15" />
            <rect x="50" y="779" rx="0" ry="0" width="180" height="15" />

            <rect x="0" y="825" rx="0" ry="0" width="90" height="12" />

            <circle cx="20" cy="870" r="15" />
            <rect x="50" y="864" rx="0" ry="0" width="180" height="15" />
            <circle cx="20" cy="910" r="15" />
            <rect x="50" y="904" rx="0" ry="0" width="180" height="15" />

         </ContentLoader>
      </div>
   </div>
);
export default PreloadSidebar;
import React from 'react';
import { colors } from '../../styles';

const IconHealthWhite = () => {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="27.77"
      height="24.193"
      viewBox="0 0 27.77 24.193"
    >
      <defs>
        <style>
          {`.cls-health-white {
          fill: none;
          stroke: ${colors.white};
          stroke-linecap: round;
          stroke-linejoin: round;
          stroke-width: 2px;
        }`}
        </style>
      </defs>
      <path
        id="health"
        className="cls-health-white"
        d="M941.85,557.281c-3.586-6.355-11.78-4.693-12.756,1.135-1.065,6.36,6.309,13.165,12.756,16.978,6.446-3.813,14.052-10.589,12.756-16.978-1.176-5.791-9.169-7.49-12.756-1.135"
        transform="translate(-927.987 -552.201)"
      />
    </svg>
  );
};

export default IconHealthWhite;

const styles = {
  transition: {
    animationName: 'fadeInLeft',
    animationDuration: '2s'
  },
  '@keyframes fadeInLeft': {
    '0%': {
      opacity: 0,
      transform: 'translateX(-20px)'
    },
    '100%': {
      opacity: 1,
      transform: 'translateX(0)'
    }
  }
};

export default styles;

/**
* Change all chart colors
*/
import AppConfig from './AppConfig';

const { primary, info, danger, success, warning, purple, secondary, yellow, white, greyLighten, grey } = AppConfig.themeColors;

const ChartConfig = {
  color: {
    'primary': primary,
    'info': info,
    'warning': warning,
    'danger': danger,
    'success': success,
    'default': '#DEE4E8',
    'purple': purple,
    'secondary': secondary,
    'yellow': yellow,
    'white': '#FFFFFF',
    'dark': white,
    'greyLighten': greyLighten,
    'grey': grey
  },
  legendFontColor: '#AAAEB3', // only works on react chart js 2
  chartGridColor: '#EAEAEA',
  axesColor: '#657786',
  shadowColor: 'rgba(0,0,0,0.6)'
}

// Tooltip Styles
export const tooltipStyle = {
  backgroundColor: 'rgba(0,0,0,0.6)',
  border: '1px solid rgba(0,0,0,0.6)',
  borderRadius: '5px'
}

export const tooltipTextStyle = {
  color: '#FFF',
  fontSize: '12px',
  paddingTop: '5px',
  paddingBottom: '5px',
  lineHeight: '1'
}

export default ChartConfig;

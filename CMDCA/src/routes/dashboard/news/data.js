//Agency Data

// chart config
import ChartConfig from 'Constants/chart-config';
import { hexToRgbA } from 'Helpers/helpers';

// News Visitor Data
export const newsVisitorsData = {
   chartLabels: ['0', '2', '3', '4', '5', '6', '7','8','9','10','11','12'],
   chartDatasets: [
      {
         label: 'Series A',
         backgroundColor: ChartConfig.color.primary,
         borderColor: ChartConfig.color.primary,
         borderWidth: 1,
         hoverBackgroundColor: ChartConfig.color.primary,
         hoverBorderColor: ChartConfig.color.primary,
         data: [5,20,40,15,8,50,30,20,35,30,30,50]
      },
      {
         label: 'Series B',
         backgroundColor: ChartConfig.color.default,
         borderColor: ChartConfig.color.default,
         borderWidth: 1,
         hoverBackgroundColor: ChartConfig.color.default,
         hoverBorderColor: ChartConfig.color.default,
         data: [20,70,60,50,10,55,65,60,65,40,67,60]
      }
   ]
}

//Newslater campaign Data
export const newslaterCampaignData = {
   chartLabels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
   chartDatasets: [
      {
         label: 'Campaign 1',
         lineTension:0,
         backgroundColor: hexToRgbA(ChartConfig.color.default, 0.4),
         borderColor: ChartConfig.color.info,
         borderWidth: 3,
         pointBorderWidth: 0,
         pointRadius: 0,
         data: [50,45,22,18,25,5,35,20,45,22,30,70,40]
      },
      {
         label: 'Campaign 2',
         lineTension: 0,
         backgroundColor: hexToRgbA(ChartConfig.color.primary, 0.4),
         borderColor: ChartConfig.color.primary,
         borderWidth: 3,
         pointBorderWidth:0,
         pointRadius:0,
         data: [40,30,60,30,35,50,10,30,25,28,55,65,80]
      }
   ]
}
import axios from 'axios';
import config from '../constants/AppConfig';

export default
   axios.create({
      baseURL: config.baseUrlApi,
      timeout: 100000
   });
import axios from 'axios';
import config from '../constants/AppConfig';

export default
   axios.create({
      baseURL: config.baseUrl,
      timeout: 2000
   });
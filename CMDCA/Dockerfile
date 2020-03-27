FROM node:8.11.3
RUN mkdir -p /home/node/app && chown -R node:node /home/node/app
WORKDIR /home/node/app
# The base node image sets a very verbose log level.
ENV NPM_CONFIG_LOGLEVEL warn
RUN chown -R node:node /usr/local/lib
RUN npm install -g serve
CMD serve -p 80 -s dist 
EXPOSE 80
COPY package*.json ./
RUN npm cache clean --force && npm install
COPY --chown=node:node . .
RUN npm run build

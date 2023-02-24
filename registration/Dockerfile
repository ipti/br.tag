FROM node:8.11.3 as build-deps
WORKDIR /usr/src/app
COPY package.json ./
RUN npm install
COPY . ./
RUN npm run build

# Stage 2 - the production environment
FROM nginx:1.12-alpine
COPY --from=build-deps /usr/src/app/build /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

# FROM node:8.11.3 as dev
# WORKDIR /usr/src/app
# COPY package.json ./
# RUN npm install
# COPY . ./
# RUN npm start 



#FROM node:8.11.3
#RUN mkdir -p /home/node/app && chown -R node:node /home/node/app
#WORKDIR /home/node/app
# The base node image sets a very verbose log level.
#ENV NPM_CONFIG_LOGLEVEL warn
#RUN chown -R node:node /usr/local/lib
#RUN npm install -g serve
#CMD serve -p 80 -s dist 
#EXPOSE 80
#COPY package*.json ./
#RUN npm cache clean --force && npm install
#COPY --chown=node:node . .
#RUN npm run build

# develop stage
FROM node:18
WORKDIR /app
COPY ./frontendVue/package*.json ./
RUN npm install --legacy-peer-deps
COPY ./frontendVue .

# start app
CMD ["npm", "run", "serve"]

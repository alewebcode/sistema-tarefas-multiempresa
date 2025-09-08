FROM node:18

WORKDIR /app

# copiar só package.json para cache de dependências
COPY package*.json ./

RUN npm install

# copiar todo o código
COPY . .

# rodar o dev server (Vue CLI)
CMD ["npm", "run", "serve", "--", "--host", "0.0.0.0"]

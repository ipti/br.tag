# Reactify

> A material design reactjs admin template

## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:3000
npm start

# build for production with minification
npm run build
```

For a detailed explanation on how things work, check out the [guide](https://github.com/facebook/create-react-app).


# Configuração Inicial

> Para o correto funcionamento da aplicação (Client) executar os seguintes passos descritos abaixo.

## Instituições

Efetuar o cadastro das instituições envolvidas, preferencialmente via API realizando uma requisição POST para URL_API/v1/institution.
Para os conselhos tutelares o campo type de ser igual a 'CONSELHO' para as demais instituições não há resalvas.

## Usuário Cidadão

Para que o cidadão possa utilizar a aplicação deve-se criar um usuário para representar todos os cidadões e o mesmo deve está vinculado a uma instituição do typo 'CONSELHO' pois todas as denúncias originadas de cidadões serão recebidas pelo Conselho Tutelar.

Efetuar a criação dos usuários preferencialmente via API realizando uma requisição POST para URL_API/v1/user.

Obs: o campo credential possui o seguinte formato {username: '', password: '', access_token: ''}, o token de acesso é gerado no momento que o usuário realiza o login.

Em src/constants/AppConfig.js informar os seguintes dados do usuário cidadão: id, access_token e institution.

## API


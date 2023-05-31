# About Waspito

## Environment

Framework: **Laravel v10.12**
Server: **Laragon v6.0 Apache 2.4**
PHP: **Php v8.1.10**
Node: **Node v16.16**
DB: **MySQL Maria**

## Installation

- Download and install the latest version of [Laragon](https://laragon.org/download/)
- Go to the root directory of laragon (**www** by default), create a new folder, then give it the name **waspito**
- Open the new folder in your text editor, and pull our repo
- Execute the following code in your terminal
    > composer update
    > npm install
- Once the above step is complete run, duplicate the **.env.example** file and rename it to **.env**
- Set your DATABASE credentials, then run the code below in your terminal
    > php artisan migrate:fresh --seed

## Workflow

### Intro

To create a post and test app functionalities make sure you login

### Auth Credentials

Default user credentials

- email: **<admin@admin.com>**
- password: **password**

### API Resources

The api url */api/users/list* accepts 2 inputs for filtering

- type: string (Beginner, Top Fan, Super Fan)
- points: int

## Key Features
* Application have two groups of users: Authenticated users and Guests.
* Guests
  - Guests can only browse funds by name,WKN and ISIN.
  - Guests also can search but only by fields above.
* Authenticate users:
  - Can browse funds like guests but additionally with Category name, Sub Category name.
  - Authenticate users can search funds by name,WKN,ISIN.
  - Authenticate users can filter funds by Category, Sub Category.
  - Download funds in PDF, XLSX, XML or in ZIP with all three files.
  - Authenticate user can view all his/her favorites funds.
  - Remove favorite funds.
  - Add funds to favorite.
  - Also download favorite fund/funds.

## How To Use

Below are commands and Tech Stack that are used in this project.

* Versions:
  - Laravel Framework 10.29.0
  - PHP 8.2.12
  - Docker Desktop 4.25.0

Install WSL2, Docker desktop 
* enable wsl in Docker Desktop

Run Docker Desktop

Open command prompt(or Terminal) and type command: 
```bash
wsl
```
After successful command, now you can create new project with laravel commands below.

Create new project with laravel command:
```bash
curl -s "https://laravel.build/fin-tech?with=mysql,redis" | bash
```

Position in root folder of new project
```bash
cd fin-tech
```


*After that its time to get project from github:
```bash
git init
```

*Set remote url:
```bash
git remote add origin https://github.com/arsicaleksandar/fin-tech-app.git
```
*Get all changes from remote branch into local with overidding local files:
```bash
git fetch --all
git reset --hard origin/master
```

*After that launch command and start project:
```bash
./vendor/bin/sail up
```

*After successfull start, opet new terminal and type commands:
```bash
wsl
```

*Access container where application is running, ALL COMMANDS BELOW ARE RUN IN THIS CONTAINER:
```bash
docker container exec -it fin-tech-laravel bash
```

*Install all packages neccessary for projects
```bash
composer install
```

*Run migrations and seeders
```bash
php artisan migrate:fresh --seed
```

* After that we need to re-run over application:
* Go on terminal tab where we run command ./vendor/bin/sail up, press ctrl + C
* After that go on Docker Desktop and delete all Containers in Containers tab

*Now run again command:
```bash
./vendor/bin/sail up
```

*Access application on url: 

[localhost](http://localhost/)



> **Note**
>if there is problem with Vite manifest package run following commands in docker container:
>```bash
>npm install
>```
>You need to run this command all the time if this error shows up:
>```bash
>npm run dev 
>```

* To login like user you need to access phpmyadmin:
  - url: [localhost:8001](http://localhost:8001/)
  - user: sail
  - pw: password


* On login page in application you need to use email from database:
  - In database fun_tech you will find table users
  - Use any email from users

> **Note**
> PASSWORD for application is : password


## Screenshots

* View of guest

![image](https://github.com/arsicaleksandar/fin-tech-app/assets/33933095/8fa6c22b-84e0-404d-96b9-e36f6adf6285)

* On Top right corner clicking on Log in form pops up

![image](https://github.com/arsicaleksandar/fin-tech-app/assets/33933095/b8a6dcd4-3fa3-4c09-b09c-500d75727220)

* After successfull login, authorized user can browse/download Funds or download zip pressing the button Download All under table of data

  ![image](https://github.com/arsicaleksandar/fin-tech-app/assets/33933095/28bc0a54-d30b-4c3d-8f0e-dee3ea4ad01a)

* On top right corner user can see My funds and browse/download or remove them from favorites
![image](https://github.com/arsicaleksandar/fin-tech-app/assets/33933095/74124c22-7265-409b-921d-f3dadbe3adac)

* On button Add new fund, all funds that are not user's favorites shows
  ![image](https://github.com/arsicaleksandar/fin-tech-app/assets/33933095/c05c8df2-16d6-42fc-bc4f-8cb840e74159)






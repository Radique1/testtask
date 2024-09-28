## README
Build application
```bash
1. docker-compose up -d --build
```
Enter php container
```bash
2. docker exec -it testtask_php bash
```
Install dependencies
```bash
3. composer install
```
Create database
```bash
4. bin/console doctrine:database:create
```
Execute migrations
```bash
5. bin/console doctrine:migrations:migrate
```
Access API interface
```bash
6. bin/console api:interface
```

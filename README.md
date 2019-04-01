# Yii 2 simple REST API (with command-line) 

This project demonstrates very simple realization closed API on Yii 2. 

Main API function splitting array of numbers by simple rule.  
Rule code you can find in `\app\models\Calculation::calculateSplitPoint()` function.

API located in a separate module named `api`.  
Module contains controller, named `TaskController`, and provide 2 actions (`index` - for calculating, and `list` for listing previous calculations)

Command `task` give you available make this calculations without setting-up web environment.

## Install
- Clone this repository
- Install all Сomposer dependencies by `composer install`

## Database
For work with project you must create databases, and configurate `config/db.php` file. I used standard database name, and MySQL user `root` with empty password on my local machine.  

Databases in my case:
- Production - `yii2basic` (in `config/db.php`)
- For Codeception - `yii2basic_test` (in `config/test_db.php`)  

After this manipulations, you can run migrations:
- Production - `php yii migrate/up`
- Production - `php ./tests/bin/yii migrate/up`

Migrations seeds two users to database after create table.

## Deployment
You can use any method to deploy it.  

I used `php yii serve` command, for easily working with this project on local machine.

## REST API
All methods need Basic Auth header for authorize user. You can use `demo:demo` or `admin:admin` users (certainly you can add your user to database).  

Available 2 API methods:
- `/api/task` (POST) - make calculation, and get answer
- `/api/task/list` (GET) - view list of your calculations  

You can use [Postman](https://www.getpostman.com/downloads/) for easily make requests

### /api/task
It's main method, only POST request allowed.
This request must contain `Authorization: Basic ***` header with user credentials.
Request body must contain JSON object with 2 fields:
- `number` (integer) - number for splitting rule
- `data` (array of integers) - array for splitting

Sample JSON object in request:
```json
{
  "number": 5,
  "data": [5,5,1,7,2,3,5]
}
```

Succesfully response contains index of item before which must split array, or `-1` if splitting impossible.  

Sample JSON object in response: 
```json
{
  "result": 4
}
```

On fail you get standard HttpException object with errors list in 'message' property.

### /api/task/list
It's method for view all of your requests, only GET request allowed.  
This request must contain `Authorization: Basic ***` header with user credentials.  

Response contains JSON array of objects, each object present you calculated early data (number, array, index of splitting, timestamp of calculation)

## Command-line tools
You can run command `php yii task` with arguments:
- _`number`_* - number for splitting rule (e.g.: `5`)  
- _`data`_* - comma separated digits string (e.g.: `5,5,7,1,32,5`)  

and options:
- `uid` - user id from database, for bind calculation to the user (e.g.: `--uid=1`)

Sample commands:  
- `php yii task 5 5,5,1,7,2,3,5` returns `4` to console, write data to DB, with `user_id` been set to `null`
- `php yii task 5 5,5,1,7,2,3,5 --uid=1` returns `4` to console, write data to DB, with `user_id` been set to `1` (`admin` user).

## Testing
For testing you can run this command:
- `./vendor/bin/codecept run`
You can specify this for run only unit tests (without functional):
- `./vendor/bin/codecept run unit`

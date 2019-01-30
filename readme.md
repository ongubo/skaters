# Incentro challange ReadMe

I developed this challange using Laravel 5. The app can run on sqlite or mysql depending on the settings. I set mine to SQLite


# Running the application
To run the application migrate the db.
```sh
$ php artisan migrate 
```
 
 
 # API requests
 I have set the api requests as follows

-/api/users/create
this url creates a user. the following parameters are needed
[firstName,lastName,email,password]

-/api/tricks/create
this creates tricks in the database. The endpoint accepts an assosiative array of tricks and the user id. Places can be provided as a string with comma separated values. One can upload as many tricks as possible as long as the assosiative array is numbered.

[user_id,tricks[0][name], tricks[0][description], tricks[0][places]]

-/api/tricks

This get request fetches all the list of tricks

-api/tricks/favourite

This endpoint marks a trick as favourite or unfavourite. it required two parameters and a boolean to state whether the trick is favourite or not

[is_favourite, user_id, trick_id]
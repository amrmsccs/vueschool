

## About This Repo

This Repository is dummy interview question for vueschool.

# Preface

1. start your timer
2. create a new laravel project and git repository
3. add timezone field to the users table (any string would do)
4. seed 20 users to the database, randomly assign one of these timezones: CET, CST, GMT+1
5. create an artisan command that updates user's firstname, lastname, and timezone to new random ones


# Introduction

We use a third-party API that has the following limits: You can make up to 50 requests per hour for batch endpoints and 3,600 individual requests per hour for other API endpoints. Each batch request accommodates up to 1,000 records in the payload for a total of 50,000 updates per hour.

We want to keep the user attributes up to date with the provider. We only need to make calls for the user whose attributes are changing. This is about 40000 calls per hour.

The batch api accepts this array of changes. The `email` is used as the key. 

```jsx
{
    "batches": [{
      "subscribers": [
        {
          "email": "alex@acme.com",
          "time_zone": "Europe/Amsterdam"
        },
        {
          "email": "hellen@acme.com",
          "name": "Hellen",
          "time_zone": "America/Los_Angeles",
          }
        }
      ]
    }]
  }
```

**Create a branch for this feature.**


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Testing using PEST

- RUN ./vendor/bin/pest
- 

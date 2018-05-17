Receipt scanner is small SPA/REST API combo for consuming receipts and itemizing them for a weekly expense report.

-   Uploaded receipts are processed using [TAGGUN](https://www.taggun.io/), to automatically grab the purchase amount, date, store name, etc.
-   The user can add notes, adjust information about the receipt, and categorize it.
-   The receipts table can be sorted and filtered in multiple ways, and exported to a CSV for download.

# Running It Locally

You must have docker installed - [Docker](https://docs.docker.com/install/)  

Start the dev server for local development:
```bash
docker-compose up --build
```
Once it's finished loaded and it's waiting for you to do something, open up another terminal, navigate to the same root directory and run this to do migrations:
```bash
docker exec -i scanner_web php artisan migrate --seed
```

Then do it with this one to import the oauth tokens (you can re-generate if you want, but you'll have to rebuild the SPA client):
```bash
cat docker/dump.sql | docker exec -i scanner_db /usr/bin/mysql -u homestead --password=secret restapi
```
Once it's finished, open up your browser to [localhost:8080](http://localhost:8080) to view the SPA. Login with `admin:password123`. 

# Overview
This demo is broken up into 4 docker containers:

 - `web`
     - Uses [Lumen](https://lumen.laravel.com/) for REST API
     - Handles storage of uploaded receipts
     - Uploaded receipts are queued for processing via redis
     - Sits in `./service`
 - `client`
     - The Vue.js SPA, it retrieves the receipts, uploads/edits them, and a table for sorting/filtering/exporting to CSV.
     - Uses Vuex for state management, following a strict one-way data flow starting with the store
     - Is updated in realtime via websockets once an uploaded receipt has been processed
     - Sits in `./client`
- `redis`
     - Used as a queue, and for pushing data that will get sent via websockets to the client
- `mysql`
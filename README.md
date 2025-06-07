# Piattaforma per la distribuzione di biglietti gratuiti per eventi

## Prerequisiti
- Docker (https://docs.docker.com/get-started/get-docker/)
- Docker Compose (https://docs.docker.com/compose/install/)

## Installazione
Copiare il file `./docker/.env.dist` in `./docker/.env` e modificare le variabili:

```ini 
PROJECT_ROOT=/PATH/TO/PROJECT
PHP_PEPPER=RANDOM_32BYTES_HEX_STRING
PHP_SECRET=RANDOM_32BYTES_HEX_STRING
```

`/PATH/TO/PROJECT` deve essere il percorso assoluto della directory del progetto.

Per generare una stringa _random_ di 32 Bytes si può usare il comando:

```shell
openssl rand -hex 32
```

> [!TIP]
> Problema di permessi su MacOS
> 
> Per evitare problemi di permessi causati da UID e GID diversi tra l'host 
> e i _container_, utilizzare la versione **-dev-macos** dell'immagine PHP (rimuovere il commento dalla
> variabile **PHP_TAG** nel file .env) dove l'utente predefinito wodby ha UID/GID 501:20 che 
> corrisponde all'utente predefinito di MacOS.

Avviare i _container_:

```shell
cd docker
docker compose up -d
```

## Configurazione
Durante il primo avvio del _container_ `mariadb`, il database viene popolato con i dati presenti nella directory `./mariadb-init`.
In particolare, vengono creati due account:

- `admin@example.com`, con privilegi di amministratore
- `user@example.com`, con privilegi utente

Per impostare una password a **TUTTI** gli utenti presenti nel db, usare il comando:

```shell
docker exec -ti tickets_cs_project_php php scripts/change_default_passwords.php PASSWORD
```

Dopo aver impostato la password, collegarsi al sito https://localhost .
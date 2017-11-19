
Esempi di uso di OAuth 2.0 e OpenID Connect
==========================

Struttura del repository
------------------------

Sono presenti due cartelle:

* oauth-client: contiene l'esempio del client oauth
* oauth-server: contiene l'esempio di server oauth
* oidc-client: contiene l'esempio di client OpenID Connect 
* oidc-server: contiene l'esempio server OpenID Connect

 
Setup del client OAuth 2.0
--------------------------

Per utilizzare il client è necessario avere installato sulla propria macchina:

* PHP 5.6
* [composer](https://getcomposer.org/) 

Di seguito gli step per eseguire il progetto. 

Entrare tramite riga di comando nella cartella del client del progetto.

Ripristinare le dipendenze del progetto:

```
composer install
```

L'applicazione verrà  eseguita localmente lanciando il comando:

```
php artisan serve
```

e rimarrà in esecuzione sull'indirizzo:

* http://localhost:8000/


Registrare il progetto sulle console di google e facebook ai seguenti indirizzi:

* https://console.developers.google.com/
* https://developers.facebook.com/

Indicando come indirizzo di redirect rispettivamente:

* http://localhost:8000/login/google/callback
* http://localhost:8000/login/fb/callback

A valle del processo di registrazione sarà necessario inserire nel file di configurazione del client i parametri per comunicare con google e facebook generati nelle console. 

Il file di configurazione deve essere creato nella cartella _oauth-client_ con nome _.env_ , E' possibile prendere come traccia il file _.env.example_.

I valore da configurare sono:

* GOOGLE\_CLIENT\_ID: dalla console di google
* GOOGLE\_CLIENT\_SECRET: dalla console di goole
* GOOGLE\_CLIENT\_REDIRECT: indirizzo indicato configurato precedentemente http://localhost:8000/login/google/callback/
* FB\_CLIENT\_ID: dalla console si facebook
* FB\_CLIENT\_SECRET: dalla console di facebook
* FB\_CLIENT\_REDIRECT: indirizzo indicato configurato precedentemente http://localhost:8000/login/fb/callback/

A questo punto la configurazione è completa ed è possibile utilizzare il client per Facebook e Google.


 
Setup del server OAuth 2.0 e Client Passport
------------------

Per utilizzare il server è necessario avere installato sulla propria macchina:

* PHP 5.6
* [composer](https://getcomposer.org/) 
* mysql 

Tutti i file del server sono presenti nella cartella _oauth-server_.

Entrare tramite riga di comando nella cartella del server del progetto.

Ripristinare le dipendenze del progetto:

```
composer install
```

Come primo passo è necessario creare la configurazione del database attraverso il file _.env_ in particolare le impostazione del database nella sezione DB, E' possibile utilizzare come punto di partenza il file _.env.example_.

Il database impostato deve esistere già all'interno del server. 

Una volta completata la configurazione è possibile creare le tabelle del server con il comando:

```
php artisan migrate
```

e inizializzare _passport_:

```
php artisan passport:install
```

A questo punto il server è pronto per essere utilizzato. Per eseguire il server (sulla porta 8010) E' possibile usare il comando:

```
php artisan serve --port 8010
```

###Configurazione cliente di test

Per testare il funzionamento del server E' necessario configurare l'applicazione client oauth per parlare anche questo autentication server.

Per configurare un client E' necessario anzitutto creare un utente sul server, questo E' possibile utilizzando un form accessibile dall'interfaccia web all'indirizzo:

* http://localhost:8010/register

Create l'utente E' necessario verificare sul database l'id dell'utente appena creato (tabella _users_), normalmente il primo utente ha id: 1.

A questo punto è possibile generare la configurazione del client, questo è possibile usando il comando:

```
php artisan passport:client
```

Il wizard richiede le seguenti informazioni:

* id dell'utente owner del client: indicare l'id dello user registato nel passo precedente
* nome del cliente: nome con cui uil sistema riconosce il client
* url di redirect: indirizzo della client app che gestisce il callback della chiamata oauth, nell'esempio: http://localhost:8000/login/passport/callback/

Inseriti i dati _passport_ configura il client e fornisce le configurazioni da inserire nel client, ad esempio:

* Client ID: 3
* Client secret: ZE8NVI3pNEj6eYft4Bzl5cB5BpdFdNbn323e2max
* http://localhost:8000/login/passport/callback/

Una volta ottenuti questi dati è necessario configurare il client nel file _oauth-client/.env_. In particolare i parametri di riferimento sono:

* PASSPORT\_CLIENT\_ID
* PASSPORT\_CLIENT\_SECRET
* PASSPORT\_CLIENT\_REDIRECT=http://localhost:8000/login/passport/callback/
* PASSPORT\_HOST=http://localhost:8010/

A questo punto anche il client è pronto per comunicare con il server.

Per testare il funzionamento: dalla home page del client (http://localhost:8000/) premere il pulsante _Login with passport_.


Setup del client OpenID Connect
--------------------------

Per utilizzare il client è necessario avere installato sulla propria macchina:

* PHP 5.6
* [composer](https://getcomposer.org/) 

Di seguito gli step per eseguire il progetto. 

Entrare tramite riga di comando nella cartella del client del progetto.

Ripristinare le dipendenze del progetto:

```
composer install
```

L'applicazione verrà  eseguita localmente lanciando il comando:

```
php artisan serve
```

e rimarrà in esecuzione sull'indirizzo:

* http://localhost:8000/


Registrare il client su uno OpenID Connect provider, ad esempio Auth0 indicando come indirizzo di redirect rispettivamente:

* http://localhost:8000/login/occ/oauth0/redirect/
* http://localhost:8000/login/oidc/oauth0/redirect/

A valle del processo di registrazione sarà necessario inserire nel file di configurazione del client _client id_ e _client secret_.

Il file di configurazione deve essere creato nella cartella _oidc-client_ con nome _.env_ , E' possibile prendere come traccia il file _.env.example_.

I valore da configurare sono:

* OIDC\_URL: https://xxxxx.eu.auth0.com/
* OIDC\_CLIENT_ID: client id Auht0
* OIDC\_CLIENT_SECRET: client id Auht0


Setup del server OAuth 2.0 e Client Passport
------------------

Per utilizzare il server è necessario avere installato sulla propria macchina:

* PHP 5.6
* [composer](https://getcomposer.org/) 
* mysql 

Tutti i file del server sono presenti nella cartella _oauth-server_.

Entrare tramite riga di comando nella cartella del server del progetto.

Ripristinare le dipendenze del progetto:

```
composer install
```

Come primo passo è necessario creare la configurazione del database attraverso il file _.env_ in particolare le impostazione del database nella sezione DB, E' possibile utilizzare come punto di partenza il file _.env.example_.

Il database impostato deve esistere già all'interno del server. 

Una volta completata la configurazione è possibile creare le tabelle del server con il comando:

```
php artisan migrate
```

e inizializzare i dati del client di test con:

```
php artisan db:seed
```

A questo punto il server è pronto per essere utilizzato. Per eseguire il server (sulla porta 8010) E' possibile usare il comando:

```
php artisan serve --port 8010
```


Per testare il funzionamento: dalla home page del client (http://localhost:8000/) premere il pulsante _Login with local server_.


Note
=====

In caso di comportamenti anomali sia nel client che nel server potrebbe essere necessario svuotare la cache di laravel eseguendo da riga di comando sul client e/o sul server le seguenti istruzioni:

```
php artisan config:clear
php artisan cache:clear
```

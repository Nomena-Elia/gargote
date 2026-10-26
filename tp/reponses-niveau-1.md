# Niveau 1

## A. Base de donnees

### 1.1 Script de migration
1. On choisit date_jour comme cle primaire. Cela garantit qu'il y a une seule ligne par jour.
2. 
- On peut avoir deux plats avec est_plat_du_jour TRUE
- On n'aurait pas les informations sur les plats du jour des jours precedents

### 1.2 Donnees de test
3. 
- Plat a 0 Ar:
```
ERROR:  new row for relation "plat" violates check constraint "positive_prix"
DETAIL:  Failing row contains (16, Tsy mety, 0, 4, t, null).

```
- Plat dont le nom existe
```
ERROR:  duplicate key value violates unique constraint "unique_nom"
DETAIL:  Key (nom)=(mokary) already exists.
```

### 1.3 Le modele PlatModel

### 1.4 Filtre les plats disponibles
CURL pour tester:
```bash
curl -iX GET 'http://localhost:8080/api/plats?disponible=1'
```

### 1.5 Le plat du jour
CURLs:
- GET
```bash
curl -iX GET 'http://localhost:8080/api/plat-du-jour'
```
- Historique
```bash
curl -iX GET 'http://localhost:8080/api/plat-du-jour/historique'
```

- Plat inexistant
```bash
curl -iX PUT 'http://localhost:8080/api/plat-du-jour' -H 'Content-Type: application/json' -d '{"id_plat": 444}'
```

- Plat epuise
```bash
curl -iX PUT 'http://localhost:8080/api/plat-du-jour' -H 'Content-Type: application/json' -d '{"id_plat": 15}'
```

- Mety
```bash
curl -iX PUT 'http://localhost:8080/api/plat-du-jour' -H 'Content-Type: application/json' -d '{"id_plat": 3}'
```

4. R2 renvoie 409 car il peut avoir un conflit de donnees. 422 indique que les donnees sont invalides. Pourtant, les donnees saisies peuvent bien etre valides, mais elles peuvent causer de conflit: Plat epuise dans le plat du jour.
5. Si bao marque comme epuise le plat qui est deja plat du jour, on marque juste epuise sur le plat du jour, et Bao peut le changer apres. Il n'y a pas d'erreur car c'est vraiment possible. Et en plus, si on supprime tout de suite le plat du jour lorsqu'il est marque epuise, cela va apporter des confusions a Bao. Donc, le comportement que je propose c'est juste donner un avertissement a Bao que le plat du jour est epuise.

### 1.6 Validations
- CURL mety
```bash
curl -X POST http://localhost:8080/api/plats \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Poulet Rôti",
    "prix": 15000,
    "id_categorie": 1,
    "description": "Délicieux poulet rôti maison avec des épices locales.",
    "disponible": true
  }'
```

- CURL tsy mety
```bash
curl -X POST http://localhost:8080/api/plats \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "A",
    "prix": 200000,
    "description": "Test d un plat invalide",
    "disponible": "oui"
  }'
```

### 1.7 Jeu de tests
- POST /api/plats (Nom existant)
```bash
curl -iX POST http://localhost:8080/api/plats \
  -H "Content-Type: application/json" \
  -d '{"nom":"Café","prix":1000,"id_categorie":3}'
```
    - Reponse:
  ```
  HTTP/1.1 422 Unprocessable Content
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:36:57 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011017.583011
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011017.583011

{
    "message": "Données invalides.",
    "errors": {
        "nom": "Un plat avec ce nom existe deja.",
        "disponible": "La valeur doit etre un booleen."
    }
}
  ```

- PUT /api/plats/4
```bash
curl -iX PUT http://localhost:8080/api/plats/4 \
  -H "Content-Type: application/json" \
  -d '{"nom":"Romazava","prix":7500}'
```

  - Reponses:
```
HTTP/1.1 200 OK
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:37:47 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011067.392116
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011067.392116

{
    "id": 4,
    "nom": "Romazava",
    "prix": 7500,
    "id_categorie": 1,
    "disponible": true,
    "description": null
}  
```   

- POST /api/plats (Prix 0)
```bash
curl -iX POST http://localhost:8080/api/plats \
  -H "Content-Type: application/json" \
  -d '{"nom":"Thé","prix":0,"id_categorie":3}'
```
    - Reponses
```
HTTP/1.1 422 Unprocessable Content
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:39:16 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011156.709374
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011156.709374

{
    "message": "Données invalides.",
    "errors": {
        "prix": "Le prix doit etre positif"
    }
}
```

- POST api/plats (id_categorie)
```bash
curl -iX POST http://localhost:8080/api/plats \
  -H "Content-Type: application/json" \
  -d '{"nom":"Thé","prix":500,"id_categorie":99}'
```
    - Reponses
```
HTTP/1.1 422 Unprocessable Content
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:40:34 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011234.887660
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011234.887660

{
    "message": "Données invalides.",
    "errors": {
        "id_categorie": "Cette categorie est inexistante"
    }
}
```

- PUT /api/plats/7 (422)
```bash
curl -iX PUT http://localhost:8080/api/plats/7 \
  -H "Content-Type: application/json" \
  -d '{"disponible":"oui"}'
```

    - Reponses
```
HTTP/1.1 422 Unprocessable Content
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:41:12 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011272.070061
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011272.070061

{
    "message": "Données invalides.",
    "errors": {
        "disponible": "La valeur doit etre un booleen."
    }
}
```

- PUT /api/plats/7
```bash
curl -iX PUT http://localhost:8080/api/plats/7 \
  -H "Content-Type: application/json" \
  -d '{"disponible":false}'
```
    - Reponses
```
HTTP/1.1 200 OK
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:45:13 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011513.538772
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011513.538772

{
    "id": 7,
    "nom": "Mi sao",
    "prix": 6500,
    "id_categorie": 2,
    "disponible": false,
    "description": null
}
```

- PUT /api/plat-du-jour
```bash
curl -iX PUT http://localhost:8080/api/plat-du-jour \
  -H "Content-Type: application/json" \
  -d '{"id_plat":7}'
```
    - Reponses
```
HTTP/1.1 409 Conflict
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:45:57 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011557.076997
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011557.076997

{
    "message": "Plat epuise",
    "errors": {
        "id_plat": "Ce plat est deja epuise"
    }
}
```

- GET /api/plats?categorie=2&disponible=1
```bash
curl -iX GET 'http://localhost:8080/api/plats?categorie=2&disponible=1' 
```
    - Reponses
```
HTTP/1.1 200 OK
Host: localhost:8080
Date: Mon, 26 Oct 2026 10:46:31 GMT
Connection: close
X-Powered-By: PHP/8.4.27
Cache-Control: no-store, max-age=0, no-cache
Content-Type: application/json; charset=UTF-8
Debugbar-Time: 1793011591.899734
Debugbar-Link: http://localhost:8080/index.php?debugbar_time=1793011591.899734

[
    {
        "id": 6,
        "nom": "Soupe chinoise",
        "prix": 6000,
        "id_categorie": 2,
        "disponible": true,
        "description": null,
        "categorie": "Soupe"
    }
]
```

6. Avant is_not_unique, c'est postgres qui detextait l'erreur. C'est un probleme de laisser PostgreSQL la detecter seule car on devait donc gerer l'affichage de l'erreur manuellement.
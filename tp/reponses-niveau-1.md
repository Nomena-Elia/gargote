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
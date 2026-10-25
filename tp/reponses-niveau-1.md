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
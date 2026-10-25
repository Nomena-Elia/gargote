# Niveau 0

## 0.2 Lire le schema relationnel
1. Schema
categorie (**id**, libelle)

plat (**id**, nom, prix, #id_categorie)

commande (**id**, date_commande, numero_table)

commande_ligne (**id**, #id_commande, #id_plat, quantite, prix_unitaire)

2. La table commande_ligne contient prix_unitaire parce que ce prix_unitaire etait le prix du plat au moment ou cette ligne de commande a ete faite. Mais, le prix dans le plat c'est le prix actuel du plat qu'on utilisera dans les prochaines commandes.

Par exemple, avant-hier, le prix du cafe etait 800 Ar, qui est stocke dans la colonne prix du plat. Un client a fait un commande, on enregistre ce 800 Ar dans le prix_unitaire de commande_ligne. Puis, hier, le prix a augmente a 1000 Ar. On met a jour le prix dans la table plat, mais dans la table commande_ligne, la commande d'avant-hier reste 800 Ar.

3. Lorsqu'on supprime une commande, les lignes de commande_ligne qui ont l'id_commande de la commande supprimee seront supprimees aussi. L'instruction responsable est le ```ON DELETE CASCADE```

4.  
- On peut inserer des prix negatifs
- On peut inserer un numero de table negatif
- On peut inserer des quantites negatives

## 0.3 Interroger l'API a la main
5. La requete b consiste a creer un PLAT. Le code explicite pour indiquer la reussite c'est 201: created.

6. C'est postgres + PHP qui bloque la suppression.
   
Le postgres gere les relations foreign key.

Php transforme l'erreur de foreign key en 409.
```php
return $this->erreur('Ce plat figure déjà dans des commandes, il ne peut pas être supprimé.', 409);
```

7. Commande curl: table 6, deux Romazava et un Café
```bash
curl -iX POST 'http://localhost:8080/api/commandes' -H 'Content-Type: application/json' -d '{"numero_table":"6","lignes":[{"id_plat":"4","quantite":"2"},{"id_plat":"10","quantite":"1"}]}'

```

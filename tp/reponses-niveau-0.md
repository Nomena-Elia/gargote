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
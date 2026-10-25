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

## 0.4
| # | Étape | Fichier | Fonction / ligne |
|---|---|---|---|
| 1 | Le formulaire est soumis | `components/PlatForm.vue` | `enregistrer()` |
| 2 | La requête HTTP est construite | `api.js` | `requete()` |
| 3 | Vite transmet `/api/...` au port 8080 | `vite.config.js` | `/api: http://localhost:8080` |
| 4 | CodeIgniter choisit le contrôleur | `app/Config/Routes.php` | `$routes->post('plats', 'Plats::create');` |
| 5 | Le contrôleur lit le JSON | `app/Controllers/Api/Plats.php` | `$donnees = $this->donneesJson();` |
| 6 | Les règles de validation sont vérifiées | `app/Models/PlatModel.php` + Le controller precedent | `$validationRules = ...` + `function insert du modele` |
| 7 | La ligne est insérée | `app/Controllers/Api/Plats.php` | `return $this->respondCreated($this->plats->find($id));` |
| 8 | Le formulaire prévient l’écran parent | `components/PlatForm.vue` | `emit(…)` |
| 9 | L’écran recharge la liste | `views/CarteView.vue` | `surEnregistrement()` |

8. Le code metier de la creation d'une commande est dans Services/CommandeService.php. Il n'est pas dans le controleur parce que le controleur gere les requetes et les reponses HTTP, tandis que le service fait la logique metier.

## 0.5 Reperer les notions de Vue
| Notion | À quoi ça sert | Votre exemple |
|---|---|---|
| `ref()` | une variable dont le changement met l’écran à jour | Fichier `views/CarteView.vue`, ligne `const plats = ref([])`. On met une liste vide dans une variable reactive que Vue detectera a chaque changement. |
| `computed()` | une valeur calculée à partir d’autres, recalculée automatiquement | Fichier `views/CommandeView.vue`, ligne `const groupes = computed(() => parCategorie(plats.value))`. A chaque fois que plats.value change, on recalcule le regroupement par categorie. |
| `v-for` | répéter un élément pour chaque élément d’une liste | Fichier `views/CommandeView.vue`, ligne `<li v-for="plat in liste" :key="plat.id">`. On boucle sur liste |
| `v-if` / `v-else` | afficher un élément seulement si une condition est vraie | Fichier `views/CommandeView.vue`, Ligne `<small v-if="erreurs.numero_table" class="erreur">{{ erreurs.numero_table }}</small>`. Si erreurs.numero_table existe, on l'affiche |
| `v-model` | lier un champ de formulaire à une variable, dans les deux sens | Ficheir `views/CommandeView.vue`, ligne `<input v-model.number="numeroTable" type="number" min="1" />`. On lie ce champ a la variable `numeroTable` |
| `defineProps` | recevoir des données du composant parent | `components/CommandeDetail.vue`, ligne `defineProps({commande: { type: Object, required: true },})`. On definit l'attribut commande. |
| `defineEmits` / `emit` | envoyer un événement au composant parent | Fichier `components/PlatForm.vue`, ligne `const emit = defineEmits(['enregistre', 'annule'])`. On definit l'evenement avec le nom `enregistre` |
| `onMounted` | exécuter du code quand le composant apparaît | Fichier `views/CarteView.vue`, ligne `onMounted(charger)`. Lorsque ce composant apparait, executer la fonction `charger` |

9. quantite est un attribut d'un props. Le composant ne modifie pas le props lui meme, car les props sont des donnees recues du parent. Donc, il emet des evenements `plus` et `moins`, et c'est le parent qui ecoute cet evenement qui passe les donnees (donc la quantite) par props.

10. Le watch sert a surveiller le changement de la variable props.plat, et change les donnees de la formulaire pour la faire correspondre a la valeur nouvelle de props.plat a chaque changement. Si on clique sur modifier d'un autre plat  sans watch, la formulaire ne s'actualise pas. 

## 0.6 Premieres modifications
11. 
- Le composant concerne est le PlatForm.vue ( + CarteView.vue)
- Dans le CarteView.vue, il y a la fonction charger, qui recupere la liste des plats et des categories.
- Cette fonction charger est appele lorsque le composant apparait (onMounted)
- Cette fonction appelle categories.lister dans api.js
- L'api.js renvoie une requete vers /api/categories
- Vite transmet la requete vers l'origin localhost:8080
- L'api /api/categories arrive dans le controller backend Categories.php, qui recupere les donnees et les renvoie au format JSON.
- Lorsque les donnees sont arrivees dans CarteView.vue, le composant transmet les categories dans PlatForm par props.

Donc la categorie apparait dans la liste car la route suivie recupere les donnees en base de donnees.
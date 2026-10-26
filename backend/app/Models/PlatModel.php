<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatModel extends Model
{
    protected $table      = 'plat';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    // PostgreSQL renvoie les nombres sous forme de texte : on les convertit.
    protected array $casts = [
        'id'           => 'int',
        'prix'         => 'int',
        'id_categorie' => 'int',
        'disponible' => 'boolean'
    ];

    // Seuls ces champs peuvent être écrits par insert() / update().
    // Un champ ajouté dans la table doit AUSSI être ajouté ici.
    protected $allowedFields = ['nom', 'prix', 'id_categorie', 'description', 'disponible'];

    // Règles de validation : vérifiées automatiquement avant insert() / update().
    // Liste des règles disponibles :
    // https://codeigniter.com/user_guide/libraries/validation.html#available-rules
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'nom'          => 'required|max_length[100]|min_length[2]|is_unique[plat.nom,id,{id}]',
        'prix'         => 'required|integer|greater_than[0]|less_than_equal_to[100000]',
        'id_categorie' => 'required|integer|is_not_unique[categorie.id]',
        'description' => 'permit_empty|max_length[255]',
        'disponible' => 'is_boolean|permit_empty'
    ];

    protected $validationMessages = [
        'nom' => [
            'required'   => 'Le nom du plat est obligatoire.',
            'max_length' => 'Le nom ne doit pas dépasser 100 caractères.',
            'min_length' => 'Le nom doit avoir au moins 2 caracteres.',
            'is_unique' => 'Un plat avec ce nom existe deja.'
        ],
        'prix' => [
            'required' => 'Le prix est obligatoire.',
            'integer'  => 'Le prix doit être un nombre entier (en Ariary).',
            'greater_than' => 'Le prix doit etre positif',
            'less_than_equal_to' => 'Le prix doit etre inferieur ou egal a 100000 Ar.'
        ],
        'id_categorie' => [
            'required' => 'Choisissez une catégorie.',
            'is_not_unique' => 'Cette categorie est inexistante'
        ],
    ];

    /**
     * Liste des plats avec le libellé de leur catégorie.
     * Si $idCategorie est fourni, ne garde que cette catégorie.
     */
    public function listerAvecCategorie(?int $idCategorie = null, ?bool $dispo = null): array
    {
        $this->select('plat.*, categorie.libelle AS categorie')
            ->join('categorie', 'categorie.id = plat.id_categorie')
            ->orderBy('categorie.libelle')
            ->orderBy('plat.nom');

        if ($idCategorie !== null) {
            $this->where('plat.id_categorie', $idCategorie);
        }

        if($dispo !== null) {
            $this->where('plat.disponible', $dispo);
        }

        return $this->findAll();
    }
}

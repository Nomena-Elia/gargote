<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class PlatDuJourModel extends Model {
        protected $table = 'plat_du_jour';
        protected $primaryKey = 'date_jour';
        protected $allowedFields = ['date_jour', 'id_plat'];
        protected $useAutoIncrement = false;

        protected $validationRules = [
            'id_plat' => 'required'
        ];

        public function getPlatDuJourLib() {
            // $today = date('Y-m-d');
            $this->select('plat_du_jour.date_jour, p.*')
            ->join('plat p', 'p.id = plat_du_jour.id_plat')
            ->where('plat_du_jour.date_jour = CURRENT_DATE');
            return $this->first();
        }

        public function insertOrUpdate($idPlat) {
            $sql = "INSERT INTO plat_du_jour (date_jour, id_plat) VALUES (CURRENT_DATE, ?) ON CONFLICT (date_jour) DO UPDATE SET id_plat = EXCLUDED.id_plat";
            $this->db->query($sql, [$idPlat]);
        }

        public function getHistory($limit = 7) {
            $limit = (int) $limit;
            // $today = date('Y-m-d');
            $this->select('plat_du_jour.date_jour, p.*')
            ->join('plat p', 'p.id = plat_du_jour.id_plat')
            ->orderBy('plat_du_jour.date_jour', 'DESC')
            ->where('plat_du_jour.date_jour <= CURRENT_DATE')
            ->where('plat_du_jour.date_jour >= CURRENT_DATE - '.$limit, null, false);
            return $this->findAll();
        }

    }

?>
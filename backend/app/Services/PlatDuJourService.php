<?php

    namespace App\Services;

    use App\Models\PlatDuJourModel;
    use App\Models\PlatModel;
    use App\Exceptions\RegleMetierException;

    class PlatDuJourService {
        public function createPlatDuJour($data) {
            $idPlat = $data['id_plat'] ?? null;
            if($idPlat == null) {
                throw new RegleMetierException('Plat non fourni', [
                    'id_plat' => 'Choisissez un plat existant'
                ]);
            }
            $platModel = model(PlatModel::class);
            $plat = $platModel->find($idPlat);
            if(!$plat) {
                throw new RegleMetierException('Plat inexistant', [
                    'id_plat' => 'Choisissez un plat existant'
                ]);
            }
            if(!$plat['disponible']) {
                throw new RegleMetierException('Plat epuise', ['id_plat' => 'Ce plat est deja epuise'], 409);
            }
            $model = model(PlatDuJourModel::class);
            $model->insertOrUpdate($idPlat);
        }
    }

?>
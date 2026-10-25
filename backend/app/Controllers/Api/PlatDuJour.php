<?php

    namespace App\Controllers\Api;
    use App\Models\PlatDuJourModel;
    use App\Services\PlatDuJourService;
    use App\Exceptions\RegleMetierException;

    class PlatDuJour extends ApiController {
        private PlatDuJourModel $model;
        private PlatDuJourService $service;

        public function __construct() {
            $this->model = model(PlatDuJourModel::class);
            $this->service = new PlatDuJourService();
        }

        public function index() {
            // $date = date('Y-m-d');
            $data = $this->model->getPlatDuJourLib();
            return $this->respond($data);
        }

        public function update() {
            $data = $this->donneesJson();
            try {
                $this->service->createPlatDuJour($data);
                return $this->respond($this->model->getPlatDuJourLib(), 200);
            } catch(RegleMetierException $e) {
                return $this->erreurMetier($e);
            }
        }

        public function history() {
            return $this->respond($this->model->getHistory());
        }

    }

?>
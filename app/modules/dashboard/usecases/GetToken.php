<?php
    Yii::import('application.modules.dashboard.services.*');
  /**
    * @property GetToken $GetToken
    */
    class GetToken
    {
        /**
         * Summary of dashboardService
         * @var DashboardService $dashboardService
         */
        private $dashboardService;

        public function __construct($dashboardService = null){
            $this->dashboardService = $dashboardService ?? new DashboardService();
        }
        public function exec($groupId, $reportId){
            return $this->dashboardService->embedReport($groupId, $reportId);
        }
    }

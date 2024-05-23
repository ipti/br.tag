Observe esse codigo



private function mapRecommendations($itemRecommendation)
    {
        $resultRecommendation = array();

        foreach ($itemRecommendation as $recommendationItem) {
            $foodInventoryItem = FoodInventory::model()->findByAttributes(array('food_fk' => $recommendationItem->item_codigo));

            if ($foodInventoryItem !== null && ($foodInventoryItem->status === 'Disponivel' || $foodInventoryItem->status === 'Acabando')) {
                $item = array(
                    // 'escola'=>$recommendationItem->
                    'codigo' => $recommendationItem->item_codigo,
                    'item_nome' => $recommendationItem->item_nome,
                    'score' => $recommendationItem->score,
                    'normalized_score' => $recommendationItem->normalized_score,
                    'semaforo' => $recommendationItem->traffic_light_color,
                );

                $resultRecommendation[] = $item;
            }
        }

        return $resultRecommendation;
    }


Preciso verificar se agora também filtrar por escola, se $escola = yii::app()->user->school for igual a school_fk mostrar o school_fk nessa PARTE            if ($foodInventoryItem !== null && ($foodInventoryItem->status === 'Disponivel' || $foodInventoryItem->status === 'Acabando')) {

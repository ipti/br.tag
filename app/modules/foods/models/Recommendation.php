public function actionFetchRecommendations()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $criteria = new \yii\db\Query();
    $results = $criteria->select(['id', 'item_reference_id', 'item_codigo', 'item_nome', 'score', 'normalized_score', 'traffic_light_color'])
                        ->from('recommendations')
                        ->all();
    return $results;
}

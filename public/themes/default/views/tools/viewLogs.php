<h1>Logs da Aplicação</h1>

<div style="overflow-y: scroll; max-height: 75vh; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
    <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_logItem', // Arquivo de visualização para cada item
        ));
    ?>
</div>

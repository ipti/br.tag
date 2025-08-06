<?php
/** @var $this ToolsController */
/** @var $tools array */

$this->setPageTitle('TAG - Ferramentas');
$this->breadcrumbs = [
    'Ferramentas',
];

?>

<div id="mainPage" class="main">
    <div class="row">
        <h1>Ferramentas Disponíveis</h1>
    </div>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <div class="tool-list">
                    <?php foreach ($tools as $tool): ?>
                        <div class="tool-item">
                            <h3><?= CHtml::encode($tool['name']); ?></h3>
                            <div class="tool-actions">
                            <a href="<?php echo CHtml::encode($tool['url']); ?>" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tool-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .tool-item {
        border: 1px solid #ddd;
        padding: 20px;
        width: 30%;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .tool-item h3 {
        margin: 0 0 10px;
    }

    .tool-actions {
        margin-top: 15px;
    }

    .tool-actions .btn {
        margin-right: 10px;
    }
</style>

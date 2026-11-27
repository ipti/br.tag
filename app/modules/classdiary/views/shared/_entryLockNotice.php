<?php
$noticeScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.classdiary.resources') . '/entry-lock-notice.js');
Yii::app()->clientScript->registerScriptFile($noticeScript . '?v=' . TAG_VERSION, CClientScript::POS_END);
?>
<div class="diary-lock-notice" role="status" aria-live="polite" hidden
     data-entry-lock-url="<?= CHtml::encode(Yii::app()->createUrl('classdiary/classes/entryLockStatus')) ?>"
     data-entry-lock-types="<?= CHtml::encode($entryLockTypes) ?>"
     data-entry-lock-classroom="<?= CHtml::encode($entryLockClassroom ?? '') ?>">
    <span class="diary-lock-notice__icon" aria-hidden="true">!</span>
    <div><strong class="diary-lock-notice__title"></strong><p class="diary-lock-notice__message"></p></div>
</div>

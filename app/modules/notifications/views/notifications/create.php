<?php
/** @var Notification $model */
$this->pageTitle = 'Nova Notificação';

$schools = CHtml::listData(
    Yii::app()->user->usersSchools,
    'inep_id',
    'name'
);
?>

<div class="main">
    <div class="row">
        <div class="column is-two-thirds">
            <h1>
                <i class="fa fa-plus-circle"></i> Nova Notificação
            </h1>

            <form method="POST" id="notification-form">
                <!-- Título -->
                <div class="t-field-text">
                    <label class="t-field-text__label t-field-text__label--required" for="notification-title">Título</label>
                    <input type="text" id="notification-title" name="Notification[title]"
                           class="t-field-text__input" required maxlength="255"
                           placeholder="Ex: Atualização do sistema" />
                </div>

                <!-- Mensagem -->
                <div class="t-field-tarea">
                    <label class="t-field-tarea__label t-field-tarea__label--required" for="notification-body">Mensagem</label>
                    <textarea id="notification-body" name="Notification[body]"
                              class="t-field-tarea__input large" required
                              placeholder="Descreva a notificação..."></textarea>
                </div>

                <!-- Tipo -->
                <div class="t-field-select column is-half no-grow clearleft">
                    <label class="t-field-select__label" for="notification-type">Tipo</label>
                    <select id="notification-type" name="Notification[type]" class="t-field-select__input">
                        <option value="info">ℹ️ Informação</option>
                        <option value="success">✅ Sucesso</option>
                        <option value="warning">⚠️ Aviso</option>
                        <option value="error">❌ Erro/Urgente</option>
                    </select>
                </div>

                <!-- Destinatários (Roles) -->
                <div class="t-field-checkbox-group">
                    <label class="t-field-checkbox-group__label">Enviar para (roles)</label>
                    <p class="text-color--ink" style="font-size: 12px; margin-bottom: 8px;">
                        Deixe vazio para enviar a todos os usuários.
                    </p>
                    <div class="row wrap no-gap">
                        <?php foreach (TRole::cases() as $role): ?>
                            <div class="t-field-checkbox" style="margin-top: 4px; width: auto; margin-right: 20px;">
                                <input type="checkbox" name="targetRoles[]" id="role-<?= $role->value ?>"
                                       value="<?= $role->value ?>" class="t-field-checkbox__input" />
                                <label class="t-field-checkbox__label" for="role-<?= $role->value ?>">
                                    <?= ucfirst($role->value) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Escola -->
                <div class="t-field-select column is-half no-grow clearleft t-margin-medium--top">
                    <label class="t-field-select__label" for="notification-school">Escola</label>
                    <select id="notification-school" name="Notification[school_fk]" class="t-field-select__input">
                        <option value="">Todas as escolas</option>
                        <?php foreach ($schools as $id => $name): ?>
                            <option value="<?= $id ?>"><?= CHtml::encode($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Expiração -->
                <div class="t-field-number column is-one-fifth no-grow clearleft">
                    <label class="t-field-number__label" for="notification-expiration">Expira em (dias)</label>
                    <input type="number" id="notification-expiration" name="expirationDays"
                           class="t-field-number__input" min="0" value="30"
                           placeholder="30" />
                    <span class="text-color--ink" style="font-size: 12px;">0 = nunca expira</span>
                </div>

                <!-- Botões -->
                <div class="t-buttons-container auto-width t-margin-large--top">
                    <button type="submit" class="t-button-primary">
                        <i class="fa fa-paper-plane"></i> Enviar Notificação
                    </button>
                    <a href="<?= Yii::app()->createUrl('notifications/notifications/index') ?>"
                       class="t-button-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

/**
 * Enum de eventos do sistema que podem gerar notificações automáticas.
 * Usar em vez de strings mágicas para referenciar source_event.
 *
 * Uso:
 *   Yii::app()->notifier->notify($userId, 'Título', 'Corpo', [
 *       'sourceEvent' => TNotificationEvent::ENROLLMENT_APPROVED,
 *   ]);
 */
enum TNotificationEvent: string
{
    // MATRÍCULAS
    case ENROLLMENT_CREATED   = 'enrollment.created';
    case ENROLLMENT_APPROVED  = 'enrollment.approved';
    case ENROLLMENT_REJECTED  = 'enrollment.rejected';
    case ENROLLMENT_TRANSFER  = 'enrollment.transfer';

    // TURMAS
    case CLASSROOM_CREATED    = 'classroom.created';
    case CLASSROOM_UPDATED    = 'classroom.updated';

    // ALMOXARIFADO
    case INVENTORY_REQUEST    = 'inventory.request';
    case INVENTORY_TRANSFER   = 'inventory.transfer';
    case INVENTORY_LOW_STOCK  = 'inventory.low_stock';

    // MERENDA
    case FOODS_MENU_PUBLISHED = 'foods.menu_published';

    // PROFISSIONAIS
    case PROFESSIONAL_ALLOCATED   = 'professional.allocated';
    case PROFESSIONAL_DEALLOCATED = 'professional.deallocated';

    // SISTEMA
    case SYSTEM_MAINTENANCE   = 'system.maintenance';
    case SYSTEM_UPDATE        = 'system.update';
}

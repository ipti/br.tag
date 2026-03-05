/**
 * Notifications — Polling AJAX 60s + Dropdown UI
 *
 * Inclui este script no fullmenu.php. Requer jQuery.
 * Endpoint JSON: /notifications/inbox/unreadCount
 * Endpoint recentes: /notifications/inbox/recent
 */
(function ($) {
    'use strict';

    let POLL_INTERVAL = 60000; // 60 segundos
    let $badge = null;
    let $dropdown = null;
    let $bell = null;
    let isDropdownOpen = false;
    let pollTimer = null;

    function init() {
        $bell = $('#notification-bell-trigger');
        $badge = $('#notification-badge');
        $dropdown = $('#notification-dropdown');

        if (!$bell.length) return;

        // Toggle dropdown ao clicar no sino
        $bell.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (isDropdownOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // Fechar ao clicar fora
        $(document).on('click', function (e) {
            if (isDropdownOpen && !$(e.target).closest('#notification-bell').length) {
                closeDropdown();
            }
        });

        // Marcar como lida via AJAX
        $(document).on('click', '.js-notif-mark-read', function (e) {
            e.preventDefault();
            let $btn = $(this);
            let id = $btn.data('id');
            $.post(
                '/?r=notifications/inbox/markRead&id=' + id,
                function () {
                    $btn.closest('.notif-dropdown-item').removeClass('notif-dropdown-item--unread');
                    $btn.remove();
                    fetchUnreadCount();
                }
            );
        });

        // Primeira busca imediata
        fetchUnreadCount();

        // Polling
        pollTimer = setInterval(fetchUnreadCount, POLL_INTERVAL);
    }

    function fetchUnreadCount() {
        $.getJSON('/?r=notifications/inbox/unreadCount', function (data) {
            updateBadge(data.unread || 0);
        });
    }

    function updateBadge(count) {
        if (count > 0) {
            $badge.text(count > 99 ? '99+' : count).show();
        } else {
            $badge.hide();
        }
    }

    function openDropdown() {
        $dropdown.html(
            '<div style="padding: 24px; text-align: center; color: #888;">' +
            '<i class="fa fa-spinner fa-spin"></i> Carregando...' +
            '</div>'
        );
        $dropdown.show();
        isDropdownOpen = true;

        $.getJSON('/?r=notifications/inbox/recent', function (data) {
            renderDropdown(data);
        });
    }

    function closeDropdown() {
        $dropdown.hide();
        isDropdownOpen = false;
    }

    function renderDropdown(data) {
        let html = '';

        html += '<div class="notif-dropdown-header">';
        html += '<strong>Notificações</strong>';
        if (data.unread > 0) {
            html += '<span class="notif-dropdown-badge">' + data.unread + ' não lida' + (data.unread > 1 ? 's' : '') + '</span>';
        }
        html += '</div>';

        if (!data.items || data.items.length === 0) {
            html += '<div style="padding: 32px; text-align: center; color: #888;">';
            html += '<i class="fa fa-bell-slash" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>';
            html += 'Nenhuma notificação';
            html += '</div>';
        } else {
            let typeColors = {
                info: '#3498db',
                warning: '#f39c12',
                error: '#e74c3c',
                success: '#2ecc71'
            };

            for (var i = 0; i < data.items.length; i++) {
                let item = data.items[i];
                let color = typeColors[item.type] || typeColors.info;
                let unreadClass = !item.is_read ? 'notif-dropdown-item--unread' : '';

                html += '<div class="notif-dropdown-item ' + unreadClass + '" style="border-left-color: ' + color + ';">';
                html += '<div class="notif-dropdown-item__title">' + escapeHtml(item.title) + '</div>';
                html += '<div class="notif-dropdown-item__body">' + escapeHtml(item.body) + '</div>';
                html += '<div class="notif-dropdown-item__meta">';
                html += '<span>' + formatDate(item.created_at) + '</span>';
                if (!item.is_read) {
                    html += '<a href="#" class="js-notif-mark-read" data-id="' + item.id + '">Marcar como lida</a>';
                }
                html += '</div>';
                html += '</div>';
            }
        }

        html += '<div class="notif-dropdown-footer">';
        html += '<a href="/?r=notifications/inbox/index">Ver todas as notificações</a>';
        html += '</div>';

        $dropdown.html(html);

        updateBadge(data.unread || 0);
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        let now = new Date();
        let date = new Date(dateStr.replace(' ', 'T'));
        let diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'agora';
        if (diff < 3600) return Math.floor(diff / 60) + ' min';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h';
        return date.toLocaleDateString('pt-BR');
    }

    function escapeHtml(text) {
        let el = document.createElement('div');
        el.textContent = text;
        return el.innerHTML;
    }

    // Init quando DOM pronto
    $(document).ready(init);

})(jQuery);

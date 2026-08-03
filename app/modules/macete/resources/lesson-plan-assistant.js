(function ($) {
    'use strict';

    function appendMessage($container, label, content) {
        var $message = $('<div>', { 'class': 't-margin-small--bottom' });
        $message.append($('<strong>').text(label + ': '));
        $message.append($('<span>').text(content));
        $container.append($message);
    }

    function appendProposals($container, proposals) {
        if (!$.isArray(proposals) || proposals.length === 0) {
            return;
        }

        var $list = $('<ul>');
        $.each(proposals, function (_, proposal) {
            var content = proposal && (proposal.rationale || proposal.value || proposal.title);
            if (content) {
                $list.append($('<li>').text(content));
            }
        });

        if ($list.children().length > 0) {
            $container.append($('<strong>').text('Sugestões para avaliar:'));
            $container.append($list);
        }
    }

    function appendSources($container, sources) {
        if (!$.isArray(sources) || sources.length === 0) {
            return;
        }

        var $list = $('<ul>');
        $.each(sources, function (_, source) {
            if (!source) {
                return;
            }

            var label = source.title || source.source_title || 'Referência consultada';
            if (source.section) {
                label += ' — ' + source.section;
            }
            if (source.page) {
                label += ' (p. ' + source.page + ')';
            }

            var $item = $('<li>');
            if (typeof source.source_url === 'string' && /^https?:\/\//i.test(source.source_url)) {
                $item.append($('<a>', {
                    href: source.source_url,
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }).text(label));
            } else {
                $item.text(label);
            }
            $list.append($item);
        });

        if ($list.children().length > 0) {
            $container.append($('<strong>').text('Fontes:'));
            $container.append($list);
        }
    }

    function sendRequest($panel, url, payload) {
        payload[$panel.data('csrf-name')] = $panel.data('csrf-token');

        return $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: payload
        });
    }

    function assistantErrorCode(xhr) {
        return xhr && xhr.responseJSON ? xhr.responseJSON.code : null;
    }

    $(function () {
        var $panel = $('.js-macete-assistant');
        if ($panel.length === 0 || $panel.data('enabled') !== true) {
            return;
        }

        var conversationId = null;
        var $message = $panel.find('.js-macete-assistant-input');
        var $send = $panel.find('.js-macete-assistant-send');
        var $messages = $panel.find('.js-macete-assistant-messages');
        var $status = $panel.find('.js-macete-assistant-status');

        $send.on('click', function () {
            var userMessage = $.trim($message.val());
            if (!userMessage) {
                $status.text('Escreva uma mensagem para continuar.');
                $message.trigger('focus');
                return;
            }

            $send.prop('disabled', true);
            $status.text('Gerando sugestão...');

            function requestReply(forceNewConversation) {
                if (forceNewConversation) {
                    conversationId = null;
                }

                var startRequest = conversationId
                    ? $.Deferred().resolve({ conversation_id: conversationId }).promise()
                    : sendRequest($panel, $panel.data('start-url'), {
                        lesson_plan_id: $panel.data('lesson-plan-id')
                    });

                return startRequest.then(function (conversation) {
                    conversationId = conversation.conversation_id;
                    return sendRequest($panel, $panel.data('message-url'), {
                        lesson_plan_id: $panel.data('lesson-plan-id'),
                        conversation_id: conversationId,
                        message: userMessage
                    });
                });
            }

            function showReply(reply) {
                appendMessage($messages, 'Você', userMessage);
                appendMessage($messages, 'Assistente', reply.message || 'Não foi possível gerar uma resposta.');
                appendProposals($messages, reply.proposals);
                appendSources($messages, reply.sources);

                if ($.isArray(reply.warnings) && reply.warnings.length > 0) {
                    $status.text(reply.warnings.join(' '));
                } else {
                    $status.text('Sugestão recebida. Revise-a antes de aplicar ao plano.');
                }
                $message.val('');
            }

            requestReply(false).done(showReply).fail(function (xhr) {
                if (assistantErrorCode(xhr) === 'assistant_conversation_context_missing') {
                    $status.text('Atualizando o contexto do plano...');
                    requestReply(true).done(showReply).fail(function () {
                        $status.text('Não foi possível consultar o assistente agora. Tente novamente em instantes.');
                    }).always(function () {
                        $send.prop('disabled', false);
                    });
                    return;
                }
                $status.text('Não foi possível consultar o assistente agora. Tente novamente em instantes.');
                $send.prop('disabled', false);
            }).done(function () {
                $send.prop('disabled', false);
            });
        });
    });
}(jQuery));

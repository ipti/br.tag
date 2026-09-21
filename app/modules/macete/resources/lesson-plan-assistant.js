(function ($) {
    'use strict';

    function scrollMessages($container) {
        $container.stop(true).animate({ scrollTop: $container.prop('scrollHeight') }, 160);
    }

    function appendMessage($container, label, content, allowHtml) {
        var messageClass = 'macete-assistant__message';
        if (label === 'Assistente') {
            messageClass += ' macete-assistant__message--assistant';
        }
        var $message = $('<div>', { 'class': messageClass });
        $message.append($('<strong>').text(label + ': '));
        var $content = $('<div>');
        if (allowHtml) {
            $content.html(safeAssistantHtml(content));
        } else {
            $content.text(content);
        }
        $message.append($content);
        $container.append($message);
        scrollMessages($container);
    }

    function proposalTarget(field) {
        var section = /^sections\.([A-Z0-9_]+)\.(stage_\d+|general)$/.exec(field || '');
        if (section) {
            return $('[name="sections[' + section[1] + '][' + section[2] + ']"]').first();
        }

        var resource = /^resources\.([A-Z0-9_]+)$/.exec(field || '');
        if (resource) {
            return $('[name="resources[' + resource[1] + ']"]').first();
        }

        var modelField = {
            name: '#MaceteLessonPlan_name',
            theme: '#MaceteLessonPlan_theme',
            unit: '#MaceteLessonPlan_unit',
            territory_context: '#MaceteLessonPlan_territory_context',
            knowledge_object: '#MaceteLessonPlan_knowledge_object',
            evaluation: '#MaceteLessonPlan_evaluation',
            references_text: '#MaceteLessonPlan_references_text'
        };
        return modelField[field] ? $(modelField[field]) : $();
    }

    function safeAssistantHtml(value) {
        if (window.DOMPurify) {
            return window.DOMPurify.sanitize(value || '', {
                ALLOWED_TAGS: ['p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'a', 'div'],
                ALLOWED_ATTR: ['href', 'target', 'rel', 'data-list']
            });
        }
        return $('<div>').text(value || '').html();
    }

    function applyProposal(proposal) {
        if (!proposal || typeof proposal.value !== 'string') {
            return false;
        }

        var $target = proposalTarget(proposal.field);
        if (!$target.length || $target.prop('disabled')) {
            return false;
        }

        var value = safeAssistantHtml(proposal.value);
        if (proposal.operation === 'append' && $target.val()) {
            value = $target.val() + value;
        }

        var editor = $target.data('macete-rich-text');
        if (!editor && window.Macete && typeof window.Macete.initRichText === 'function') {
            window.Macete.initRichText($target.parent());
            editor = $target.data('macete-rich-text');
        }
        if (editor) {
            editor.setText('', 'silent');
            editor.clipboard.dangerouslyPasteHTML(value, 'silent');
            value = editor.root.innerHTML;
        }
        $target.val(value).trigger('change');
        return true;
    }

    function chatInputValue($input) {
        var editor = $input.data('macete-rich-text');
        return editor ? editor.root.innerHTML : $input.val();
    }

    function clearChatInput($input) {
        var editor = $input.data('macete-rich-text');
        if (editor) {
            editor.setText('', 'silent');
        }
        $input.val('');
    }

    function hasChatContent(value) {
        return $.trim($('<div>').html(value || '').text()) !== '';
    }

    function applyAllProposals(proposals) {
        var applied = 0;
        $.each(proposals, function (_, proposal) {
            if (applyProposal(proposal)) {
                applied += 1;
            }
        });
        return applied;
    }

    function appendProposals($container, proposals, $status) {
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
            var applied = applyAllProposals(proposals);
            $container.append($('<strong>').text('Sugestões aplicadas ao formulário:'));
            $container.append($list);
            if (applied > 0) {
                $status.text(applied + ' sugestão(ões) aplicada(s). Revise e salve o plano para confirmar as alterações.');
            }
            scrollMessages($container);
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

    function assistantValidationMessage(xhr) {
        var errors = xhr && xhr.responseJSON ? xhr.responseJSON.validation_errors : null;
        if (!$.isArray(errors) || errors.length === 0) {
            return 'Não foi possível consultar o assistente agora. Tente novamente em instantes.';
        }
        var fields = $.map(errors, function (error) {
            return $.isArray(error.location) ? error.location.join('.') : '';
        });
        return 'O contexto do plano precisa ser revisado: ' + fields.join(', ') + '.';
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
        var $toggle = $panel.find('.js-macete-assistant-toggle');
        var sendLabel = $.trim($send.text()) || 'Enviar';

        function setSending(isSending) {
            $send.prop('disabled', isSending);
            $send.text(isSending ? 'Gerando resposta…' : sendLabel);
        }

        $toggle.on('click', function () {
            var minimized = !$panel.hasClass('is-minimized');
            $panel.toggleClass('is-minimized', minimized);
            $toggle.attr('aria-expanded', minimized ? 'false' : 'true')
                .attr('title', minimized ? 'Expandir assistente' : 'Minimizar assistente')
                .find('.fa').toggleClass('fa-minus', !minimized).toggleClass('fa-plus', minimized);
            $toggle.find('.sr-only').text(minimized ? 'Expandir assistente' : 'Minimizar assistente');
            if (!minimized) {
                $message.trigger('focus');
                scrollMessages($messages);
            }
        });

        $send.on('click', function () {
            if ($send.prop('disabled')) {
                return;
            }
            var userMessage = $.trim(chatInputValue($message));
            if (!hasChatContent(userMessage)) {
                $status.text('Escreva uma mensagem para continuar.');
                $message.trigger('focus');
                return;
            }

            setSending(true);
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
                appendMessage($messages, 'Você', userMessage, true);
                appendMessage($messages, 'Assistente', reply.message || 'Não foi possível gerar uma resposta.', true);
                appendProposals($messages, reply.proposals, $status);
                appendSources($messages, reply.sources);

                if ($.isArray(reply.warnings) && reply.warnings.length > 0) {
                    $status.text(reply.warnings.join(' '));
                } else {
                    $status.text('Sugestão recebida. Revise-a antes de aplicar ao plano.');
                }
                clearChatInput($message);
                scrollMessages($messages);
            }

            requestReply(false).done(showReply).fail(function (xhr) {
                if (assistantErrorCode(xhr) === 'assistant_conversation_context_missing') {
                    $status.text('Atualizando o contexto do plano...');
                    requestReply(true).done(showReply).fail(function () {
                        $status.text('Não foi possível consultar o assistente agora. Tente novamente em instantes.');
                    }).always(function () {
                        setSending(false);
                    });
                    return;
                }
                $status.text(assistantValidationMessage(xhr));
                setSending(false);
            }).done(function () {
                setSending(false);
            });
        });

        $message.add($message.prev('.macete-rich-text').find('.ql-editor')).on('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                event.preventDefault();
                if (!$send.prop('disabled')) {
                    $send.trigger('click');
                }
            }
        });
    });
}(jQuery));

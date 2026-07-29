(function ($) {
    window.Macete = window.Macete || {};

    var allowedTags = ['p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'a', 'div'];
    var allowedAttributes = ['href', 'target', 'rel', 'data-list'];

    function sanitizeForEditor(content) {
        if (window.DOMPurify) {
            return window.DOMPurify.sanitize(content || '', {
                ALLOWED_TAGS: allowedTags,
                ALLOWED_ATTR: allowedAttributes,
            });
        }

        return content || '';
    }

    function initEditor(textarea) {
        if (textarea.data('macete-rich-text') || textarea.prop('disabled') || typeof window.Quill !== 'function') {
            return;
        }

        var container = $('<div class="macete-rich-text" aria-label="Editor de texto pedagógico"></div>');
        textarea.before(container);

        var editor = new window.Quill(container[0], {
            theme: 'snow',
            formats: ['bold', 'italic', 'list', 'link'],
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean'],
                ],
            },
        });
        var initialContent = sanitizeForEditor(textarea.val());
        if (initialContent) {
            editor.clipboard.dangerouslyPasteHTML(initialContent, 'silent');
        }

        textarea.addClass('macete-rich-text__source').attr('aria-hidden', 'true');
        textarea.data('macete-rich-text', editor);
        editor.on('text-change', function () {
            textarea.val(editor.root.innerHTML);
        });
    }

    window.Macete.initRichText = function (scope) {
        $(scope || document).find('textarea.t-field-tarea__input').each(function () {
            initEditor($(this));
        });
    };

    $(document).on('submit', '#macete-lesson-plan-form, #macete-lesson-record-form', function () {
        $('textarea.macete-rich-text__source').each(function () {
            var textarea = $(this);
            var editor = textarea.data('macete-rich-text');
            if (editor) {
                textarea.val(editor.root.innerHTML);
            }
        });
    });

    $(document).ready(function () {
        window.Macete.initRichText();
    });
})(jQuery);

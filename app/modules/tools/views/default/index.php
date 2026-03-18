<?php $this->pageTitle = 'Ferramentas — Administração'; ?>

<style>
.tools-page         { padding: 24px; max-width: 1100px; }
.tools-header       { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.tools-header h1    { margin: 0; font-size: 20px; font-weight: 700; color: #252A31; font-family: 'Inter', sans-serif; }
.tools-header p     { margin: 4px 0 0; color: #5F738C; font-size: 13px; font-family: 'Inter', sans-serif; }

.tools-warning {
    background: #fdf0e3;
    border-left: 4px solid #e98305;
    padding: 10px 16px;
    border-radius: 4px;
    margin-bottom: 24px;
    font-size: 13px;
    color: #a25100;
    font-family: 'Inter', sans-serif;
}

.tools-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }

/* Override t-cards for tighter layout */
.tools-card {
    background: #fafafe;
    border-radius: 6px;
    border: 1px solid #eff2f5;
    padding: 0;
    overflow: hidden;
}
.tools-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 16px 10px;
    border-bottom: 1px solid #eff2f5;
    background: #fff;
}
.tools-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #252A31;
    font-family: 'Inter', sans-serif;
    margin: 0;
}
.tools-card-desc {
    font-size: 12px;
    color: #5F738C;
    font-family: 'Inter', sans-serif;
    padding: 10px 16px 6px;
    line-height: 1.5;
}
.tools-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-top: 1px solid #eff2f5;
}
.tools-row-label {
    flex: 1;
    font-size: 13px;
    color: #465567;
    font-family: 'Inter', sans-serif;
}
.t-code-arg {
    font-family: monospace;
    font-size: 11px;
    background: #e8edf2;
    padding: 2px 7px;
    border-radius: 4px;
    color: #333;
    white-space: nowrap;
}

/* Terminal output panel */
.tools-output {
    background: #1e1e2e;
    border-radius: 8px;
    margin-bottom: 24px;
    overflow: hidden;
    display: none;
}
.tools-output-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #181825;
    color: #a6adc8;
    font-size: 12px;
    font-family: monospace;
}
.tools-output-header button {
    margin-left: auto;
    background: none;
    border: none;
    color: #a6adc8;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    padding: 2px 6px;
    border-radius: 3px;
}
.tools-output-header button:hover { background: #313244; color: #fff; }
.tools-output-pre {
    margin: 0;
    padding: 16px;
    white-space: pre-wrap;
    word-break: break-word;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    max-height: 400px;
    overflow-y: auto;
    line-height: 1.6;
}
.tools-output-spinner {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 16px;
    color: #6c7086;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
}
.tools-output-footer { padding: 10px 16px; background: #181825; }
@keyframes spin { to { transform: rotate(360deg); } }
.spin-icon { display: inline-block; animation: spin 0.9s linear infinite; font-size: 16px; }

/* Modal overlay */
.tools-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
</style>

<div class="tools-page">

    <div class="tools-header">
        <span class="t-icon-settings" style="font-size: 22px; color: #3f45ea;"></span>
        <div>
            <h1>Ferramentas</h1>
            <p>Execução de manutenções e comandos administrativos do sistema.</p>
        </div>
    </div>

    <div class="tools-warning">
        ⚠&nbsp; Área restrita ao <strong>Superadmin</strong>. Cada execução é registrada no log da aplicação.
    </div>

    <!-- Terminal output panel -->
    <div id="tools-output" class="tools-output">
        <div class="tools-output-header">
            <span>$</span>
            <span id="tools-output-title">yiic</span>
            <button onclick="document.getElementById('tools-output').style.display='none'">✕</button>
        </div>
        <div id="tools-spinner" class="tools-output-spinner">
            <span class="spin-icon t-icon-settings"></span> Executando, aguarde...
        </div>
        <pre id="tools-output-pre" class="tools-output-pre"></pre>
        <div class="tools-output-footer" id="tools-output-footer"></div>
    </div>

    <!-- Command cards -->
    <div class="tools-grid">
        <?php foreach ($commands as $cmdName => $cmd): ?>
        <div class="tools-card">
            <div class="tools-card-header">
                <span class="<?= CHtml::encode($cmd['icon']) ?>" style="font-size:16px; color:#3f45ea;"></span>
                <p class="tools-card-title"><?= CHtml::encode($cmd['label']) ?></p>
            </div>
            <p class="tools-card-desc"><?= CHtml::encode($cmd['description']) ?></p>

            <?php foreach ($cmd['args'] as $arg => $argLabel): ?>
            <div class="tools-row">
                <?php if (!empty($arg)): ?>
                <span class="t-code-arg"><?= CHtml::encode($arg) ?></span>
                <?php endif; ?>
                <span class="tools-row-label"><?= CHtml::encode($argLabel) ?></span>
                <div class="t-buttons-container auto-width" style="margin: 0;">
                    <button
                        class="t-button-primary tools-run-btn"
                        data-command="<?= CHtml::encode($cmdName) ?>"
                        data-arg="<?= CHtml::encode($arg) ?>"
                        data-label="yiic <?= CHtml::encode($cmdName) ?><?= !empty($arg) ? ' ' . CHtml::encode($arg) : '' ?>"
                        style="padding: 5px 14px; font-size: 12px; flex: none;"
                    >
                        <span class="t-icon-play"></span> Executar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Confirmation modal -->
<div id="tools-modal" class="tools-modal-overlay">
    <div class="t-modal-container" style="min-width: 400px; max-width: 500px; overflow: visible;">
        <div class="t-modal__header">
            <span class="t-title">Confirmar execução</span>
        </div>
        <div class="t-modal__body">
            <p style="font-size: 14px; color: #465567; margin-bottom: 12px; font-family: 'Inter', sans-serif;">
                Tem certeza que deseja executar:
            </p>
            <div class="t-badge-info" style="font-family: monospace; font-size: 13px; display: inline-flex;">
                <span id="tools-modal-label"></span>
            </div>
        </div>
        <div class="t-modal__footer" style="display: flex; gap: 8px; padding: 16px 24px;">
            <div class="t-buttons-container auto-width" style="margin:0;">
                <button class="t-button-secondary" onclick="window.toolsCloseModal()" style="padding: 7px 18px; flex: none;">Cancelar</button>
            </div>
            <div class="t-buttons-container auto-width" style="margin:0;">
                <button id="tools-modal-confirm" class="t-button-primary" style="padding: 7px 18px; flex: none;">
                    <span class="t-icon-play"></span> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var pendingCommand = null;
    var pendingArg     = null;

    document.querySelectorAll('.tools-run-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            pendingCommand = btn.dataset.command;
            pendingArg     = btn.dataset.arg;
            document.getElementById('tools-modal-label').textContent = btn.dataset.label;
            document.getElementById('tools-modal').style.display = 'flex';
        });
    });

    window.toolsCloseModal = function () {
        document.getElementById('tools-modal').style.display = 'none';
        pendingCommand = null;
        pendingArg     = null;
    };

    document.getElementById('tools-modal').addEventListener('click', function (e) {
        if (e.target === this) window.toolsCloseModal();
    });

    document.getElementById('tools-modal-confirm').addEventListener('click', function () {
        if (!pendingCommand) return;
        var cmd = pendingCommand, arg = pendingArg;
        window.toolsCloseModal();
        runCommand(cmd, arg);
    });

    function runCommand(command, arg) {
        var panel   = document.getElementById('tools-output');
        var spinner = document.getElementById('tools-spinner');
        var pre     = document.getElementById('tools-output-pre');
        var footer  = document.getElementById('tools-output-footer');
        var title   = document.getElementById('tools-output-title');

        title.textContent   = 'yiic ' + command + (arg ? ' ' + arg : '');
        pre.textContent     = '';
        footer.innerHTML    = '';
        spinner.style.display = 'flex';
        panel.style.display   = 'block';
        panel.scrollIntoView({ behavior: 'smooth' });

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= Yii::app()->createUrl('tools/default/run') ?>');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            spinner.style.display = 'none';
            try {
                var d = JSON.parse(xhr.responseText);
                pre.textContent = d.output || '(sem output)';
                footer.innerHTML = d.success
                    ? '<span class="t-badge-success" style="display:inline-flex;">✓ Concluído com sucesso</span>'
                    : '<span class="t-badge-critical" style="display:inline-flex;">✗ Falhou (exit ' + d.exitCode + ')</span>';
            } catch (e) {
                pre.textContent = xhr.responseText;
                footer.innerHTML = '<span class="t-badge-warning" style="display:inline-flex;">⚠ Resposta inesperada</span>';
            }
        };
        xhr.send(
            'command=' + encodeURIComponent(command) +
            '&arg='    + encodeURIComponent(arg) +
            '&<?= Yii::app()->request->csrfTokenName ?>=' + encodeURIComponent('<?= Yii::app()->request->csrfToken ?>')
        );
    }
})();
</script>

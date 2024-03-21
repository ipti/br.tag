<?php

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/sagres.css');
?>

<div id="mainPage" class="main">
	<div class="row">
		<div class="column">
			<h1>Sagres Edu</h1>
		</div>
	</div>
	<div class="alert alert-error alert-error-export" style="display: none;"></div>
	<?php if (Yii::app()->user->hasFlash('error')) : ?>
		<div class="alert alert-error">
			<?php echo Yii::app()->user->getFlash('error') ?>
		</div>
	<?php endif ?>
	<?php if (Yii::app()->user->hasFlash('success')) : ?>
		<div class="alert alert-success">
			<?php echo Yii::app()->user->getFlash('success') ?>
		</div>
		<br />
	<?php endif ?>
	<div class="sagres-header" style="display: inline-flex;margin-left: 15px;">
		<div>
			<h5>Mês</h5>
			<select id="mes" name="mes">
				<option value="0">Selecione um mês</option>
				<?php
					$anoAtual = date('Y');
					$mesAtual = date('n');

					$meses = [
						1 => 'Janeiro',
						2 => 'Fevereiro',
						3 => 'Março',
						4 => 'Abril',
						5 => 'Maio',
						6 => 'Junho',
						7 => 'Julho',
						8 => 'Agosto',
						9 => 'Setembro',
						10 => 'Outubro',
						11 => 'Novembro',
						12 => 'Dezembro'
					];
			
					if ($anoAtual == Yii::app()->user->year) {
						for ($mes = 1; $mes <= $mesAtual; $mes++) {
							echo "<option value='$mes'>$meses[$mes]</option>";
						}
					} else {
						for ($mes = 1; $mes <= 12; $mes++) {
							echo "<option value='$mes'>$meses[$mes]</option>";
						}
					}
				?>
			</select>
		</div>
		<div class="column" style=" display: flex;flex-direction: column; display: flex;align-items: left">
			<div style="display: flex;align-items: center, margin: 3px 5px 0 0;">
				<input type="checkbox" name="finalTurma" value="1" id="finalTurma" style="margin: 3px 5px 0 0;">
				<label for="finalTurma" style="vertical-align: middle;">Encerramento de Período</label>
			</div>
			<div style="display: flex;align-items: center, margin: 3px 5px 0 0;">
				<input type="checkbox" name="semMovimentacao" value="1" id="semMovimentacao" style="margin: 3px 5px 0 0;">
				<label for="semMovimentacao" style="vertical-align: middle;">Sem movimentação</label>
			</div>
			<div style="display: flex;align-items: center, margin: 3px 5px 0 0;">
				<input type="checkbox" name="generateWithoutCPF" value="1" id="generateWithoutCPF" style="margin: 3px 5px 0 0;">
				<label for="generateWithoutCPF" style="vertical-align: middle;">Gerar arquivo excluindo alunos sem CPF</label>
			</div>
		</div>
	</div>

	<div class="container-box" style="display: grid;">
		<a href="?r=sagres/default/createorupdate">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/school.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Cadastrar ou Editar Unidade</span><br>
					<span class="subtitle">Cadastre ou edite a Unidade gestora</span>
				</div>
			</button>
		</a>

		<a href="?r=sagres/default/export" id="exportLink">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/export.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Exportar Unidade</span><br>
					<span class="subtitle">Exporte os dados da Unidade</span>
				</div>
				<div id="loading-popup" style="display: none;">
					<div class="loading-content">
						<div class="loading-spinner"></div>
						<div class="loading-text">Aguarde enquanto o arquivo é gerado...</div>
					</div>
				</div>
			</button>
		</a>

		<a href="<?php echo Yii::app()->createUrl('professional') ?>">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/professional.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Profissionais</span><br>
					<span class="subtitle">Adicione e atualize profissionais</span>
				</div>
			</button>
		</a>

		<a href="?r=sagres/default/inconsistencysagres">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/inconsistency.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Inconsistências</span><br>
					<span class="subtitle">Lista de inconsistências SAGRES</span>
				</div>
				<?php
				if ($numInconsistencys != 0) {
					echo '<span class="pull-right circle">' . $numInconsistencys . '</span>';
				}
				?>
			</button>
		</a>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script>
	let selectedValue;
	let checkbox = document.getElementById("finalTurma");
	let checkboxSemMovi = document.getElementById("semMovimentacao");
	let checkboxWithoutCPF = document.getElementById("generateWithoutCPF"); 
	let checkboxValue = false;
	let checkboxValueSem = false;
	let checkboxWithoutCPFValue = false;

	function updateExportLink() {
		const month = parseInt(selectedValue, 10);

		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&month=${month}&finalClass=${checkboxValue}&noMovement=${checkboxValueSem}&withoutCpf=${checkboxWithoutCPFValue}`;
		exportLink.setAttribute('href', newHref);
	}

	checkbox.addEventListener('change', function() {
		checkboxValue = checkbox.checked;
		if (checkboxValue) {
			checkboxSemMovi.checked = false;
			checkboxValueSem = false;
		}
		updateExportLink();
	});

	checkboxSemMovi.addEventListener('change', function() {
		checkboxValueSem = checkboxSemMovi.checked;
		if (checkboxValueSem) {
			checkbox.checked = false;
			checkboxValue = false;
			checkboxWithoutCPF.checked = false;
			checkboxWithoutCPFValue = false;
		}
		updateExportLink();
	});

	checkboxWithoutCPF.addEventListener('change', function() {
		checkboxWithoutCPFValue = checkboxWithoutCPF.checked;
		if (checkboxValueSem) {
			checkboxSemMovi.checked = false;
			checkboxValueSem = false;
		}
		updateExportLink();
	});

	const selectElement = document.getElementById("mes");
	selectElement.addEventListener("change", (event) => {
		selectedValue = event.target.value;
		updateExportLink();
	});

	function downloadFile(url, filename) {
		const link = document.createElement('a');
		link.href = url;
		link.download = filename;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

	document.getElementById('exportLink').addEventListener('click', function(e) {
		e.preventDefault();
		const exportLink = document.getElementById('exportLink');
		const href = exportLink.getAttribute('href');

		if (!href) {
			console.error('O link de exportação não foi definido');
			return;
		}

		if (!selectedValue || selectedValue == '0') {
			$(".alert-error-export").html("Você precisa selecionar um mês antes de exportar os dados.");  // Change append to html to overwrite the content
			$(".alert-error-export").show();
			return;
		}

		downloadFile(href, 'Educacao.xml');
	});


	function downloadFile(url, filename) {
		$(".alert-error-export").hide();
		$(".alert-error-export").empty();

		$("#loading-popup").show();

		$.get(url, function(data) {
			$("#loading-popup").hide();
					location.reload();
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			$("#loading-popup").hide();
			$(".alert-error-export").append('Erro ao realizar o download do arquivo: ' + errorThrown);
			$(".alert-error-export").show();
		})
	}
</script>

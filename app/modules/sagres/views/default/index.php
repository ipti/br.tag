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
				<option value="1">Janeiro</option>
				<option value="2">Fevereiro</option>
				<option value="3">Março</option>
				<option value="4">Abril</option>
				<option value="5">Maio</option>
				<option value="6">Junho</option>
				<option value="7">Julho</option>
				<option value="8">Agosto</option>
				<option value="9">Setembro</option>
				<option value="10">Outubro</option>
				<option value="11">Novembro</option>
				<option value="12">Dezembro</option>
			</select>
		</div>
		<input type="checkbox" name="finalTurma" value="1" id="finalTurma" style="margin: 30px 0 0 20px;">
		<label for="finalTurma" style="margin: 30px 0 0 10px;">Encerramento de Período</label>
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
						<div class="loading-text">Aguarde equanto o arquivo é gerado...</div>
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
	const selectElement = document.getElementById("mes");
	var checkbox = document.getElementById("finalTurma");
	var checkboxValue = false;

	checkbox.addEventListener('change', function() {
		checkboxValue = checkbox.checked ? true : false;

		month = parseInt(selectedValue, 10)

		const year = new Date().getFullYear();
		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&year=${year}&month=${month}&finalClass=${checkboxValue}`;
		exportLink.setAttribute('href', newHref);
	});

	selectElement.addEventListener("change", (event) => {
		selectedValue = event.target.value;

		month = parseInt(selectedValue, 10)

		const year = new Date().getFullYear();
		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&year=${year}&month=${month}&finalClass=${checkboxValue}`;
		exportLink.setAttribute('href', newHref);

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

		if (!selectedValue) {
			$(".alert-error-export").append("Você precisa selecionar um mês antes de exportar os dados.");
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
				const zip = new JSZip();
				zip.file(filename, data);
				zip.generateAsync({
					type: "blob"
				}).then(function(blob) {
					var url = URL.createObjectURL(blob);
					var link = document.createElement('a');
					link.href = url;
					link.download = 'Educacao.zip';
					link.click();
					URL.revokeObjectURL(url);
				}).catch(function(err) {
					console.error(err);
					$(".alert-error-export").append('Erro ao criar o arquivo zip');
					$(".alert-error-export").show();
				}).finally(function() {
					location.reload();
				});
			})
			.fail(function() {

				$("#loading-popup").hide();

				$(".alert-error-export").append('Erro ao realizar o download do arquivo ');
				$(".alert-error-export").show();
			})

	}
</script>
<?php

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/sagres.css');
?>

<div id="mainPage" class="main">
	<div class="row">
		<div class="column">
			<h1>Gestão presente</h1>
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

	<div class="container-box" style="display: grid;">


		<a href="?r=gestaopresente/default/exportPersonalData" id="exportLink">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/export.svg" />
				</div>
				<div class="pull-left">
					<span class="title">Exportar Planilha</span><br>
					<span class="subtitle">Exporte os dados do município</span>
				</div>
				<div id="loading-popup" style="display: none;">
					<div class="loading-content">
						<div><img height="50px" width="50px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading"></div>
						<div class="loading-text">Aguarde enquanto o arquivo é gerado...</div>
					</div>
				</div>
			</button>
		</a>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js" integrity="sha512-xQBQYt9UcgblF6aCMrwU1NkVA7HCXaSN2oq0so80KO+y68M+n64FOcqgav4igHe6D5ObBLIf68DWv+gfBowczg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	let selectedValue;
	let checkbox = document.getElementById("finalTurma");
	let checkboxSemMovi = document.getElementById("semMovimentacao");
	let checkboxWithoutCPF = document.getElementById("generateWithoutCPF");
	let checkboxValue = false;
	let checkboxValueSem = false;
	let checkboxWithoutCPFValue = true;

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

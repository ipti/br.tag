<?php
use SagresEdu\SagresConsultModel;

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/sagres.css');
?>

<?php
/* $export =  new SagresConsultModel;
print_r($export->getEducacaoData(2022, '2022-01-1', '2022-01-31')); */
?>

<div id="mainPage" class="main">
	<div class="row">
		<div class="column">
			<h1>Sagres Edu</h1>
		</div>
	</div>
	<div class="alert alert-error alert-error-export" style="display: none;"></div>
	<?php if (Yii::app()->user->hasFlash('error')): ?>
		<div class="alert alert-error">
			<?php echo Yii::app()->user->getFlash('error') ?>
		</div>
	<?php endif ?>
	<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="alert alert-success">
			<?php echo Yii::app()->user->getFlash('success') ?>
		</div>
		<br />
	<?php endif ?>
	<div class="row">
		<div class="column no-grow">
			<div>
				<h5>Data Inicial</h5>
				<input id="data-inicio" style="border-color: #e5e5e5;" type="date" name="data_inicio"
					placeholder="DD/MM/AAAA">
			</div>
		</div>
		<div class="column no-grow">
			<div>
				<h5>Data Final</h5>
				<input id="data-final" style="border-color: #e5e5e5;" type="date" name="Data_final"
					placeholder="DD/MM/AAAA">
			</div>
		</div>
	</div>
	<div class="container-box" style="display: grid;">

		<a href="?r=sagres/default/create">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/school.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Cadastrar Unidade</span><br>
					<span class="subtitle">Cadastre uma Unidade gestora</span>
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
			</button>
		</a>

		<a href="<?php echo Yii::app()->createUrl('sagres/default/update', array('id' => 1)) ?>">
			<button type="button" class="report-box-container">
				<div class="pull-left" style="margin-right: 20px;">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sagresIcon/edit.svg" />
					<!-- <div class="t-icon-schedule report-icon"></div> -->
				</div>
				<div class="pull-left">
					<span class="title">Editar Unidade</span><br>
					<span class="subtitle">Atualize os dados da Unidade</span>
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
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script>
	// Selecione os elementos de input de data
	const dataInicioInput = document.querySelector('input[name="data_inicio"]');
	const dataFinalInput = document.querySelector('input[name="Data_final"]');

	// Adicione um ouvinte de eventos para o evento de clique no link
	document.getElementById('exportLink').addEventListener('click', function (e) {
		$(".alert-error-export").hide();
		$(".alert-error-export").empty();
		// Previna o comportamento padrão do link
		e.preventDefault();

		// Obtenha o valor das datas de início e fim
		const dataInicio = dataInicioInput.value;
		const dataFinal = dataFinalInput.value;

		if(dataInicio == '' || dataFinal == '') {
			$(".alert-error-export").append('Preencha os campos de data inicial e data final');
			$(".alert-error-export").show();
			return;
		}

		// Obtenha o ano de início
		const data = new Date(dataInicio);
		data.setUTCHours(0, 0, 0, 0); // Define as horas, minutos, segundos e milissegundos como zero
		const yearInicio = data.getUTCFullYear();

		// Atualize o atributo "href" com as datas selecionadas
		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&managementUnitId=${1}&year=${yearInicio}&data_inicio=${dataInicio}&data_final=${dataFinal}`;
		exportLink.setAttribute('href', newHref);

		// Chame a função downloadFile para gerar e baixar o arquivo
		downloadFile(newHref, 'Educacao.xml');
	});

	function downloadFile(url, filename) {
		$(".alert-error-export").hide();
		$(".alert-error-export").empty();
		$.get(url, function (data) {
			const zip = new JSZip();
			zip.file(filename, data);
			zip.generateAsync({type:"blob"}).then(function(blob) {
				var url = URL.createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = 'Educacao.zip';
				link.click();
				URL.revokeObjectURL(url);
			})
			.catch(function(err) {
				console.error(err);
				$(".alert-error-export").append('Erro ao criar o arquivo zip');
				$(".alert-error-export").show();
			});
		})
		.fail(function () {
			$(".alert-error-export").append('Erro ao realizar o download do arquivo');
			$(".alert-error-export").show();
		})
		.always(function() {
			location.reload();
		}); 
	}

</script>

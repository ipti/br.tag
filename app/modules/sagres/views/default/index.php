<?php
use SagresEdu\SagresConsultModel;

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));
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
	<div class="row">
		<div class="column">
			<a href="?r=sagres/default/create" class="widget-stats">
				<div><i class="fa fa-building-o fa-4x"></i></div>
				<span class="txt">Cadastrar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>

		<div class="column">
			<a href="?r=sagres/default/export" id="exportLink" class="widget-stats">
				<span class="glyphicons file_export"><i></i></span>
				<span class="txt">Exportar sagres</span>
				<div class="clearfix"></div>
			</a>
		</div>
		<div class="column">
			<a href="<?php echo Yii::app()->createUrl('sagres/default/update', array('id' => 1)) ?>"
				href="?r=sagres/default/update&id=2" class="widget-stats">
				<div><i class="fa fa-edit fa-4x"></i></div>
				<span class="txt">Editar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>

		<div class="column">
			<a href="<?php echo Yii::app()->createUrl('professional') ?>" class="widget-stats">
				<span class="glyphicons user"><i></i></span>
				<span class="txt">Profissionais</span>
				<div class="clearfix"></div>
			</a>
		</div>
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

		// Chame a função downloadFile para gerar e baixar o arquivo
		const url = `?r=sagres/default/export&managementUnitId=${1}&year=${yearInicio}&data_inicio=${dataInicio}&data_final=${dataFinal}`;
		const filename = 'Educacao.xml';
		downloadFile(url, filename);
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
		});
	}

</script>

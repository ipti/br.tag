<?php

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));
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
		</div>
	</div>
	<div class="row">
		<div class="column">
			<a href="?r=sagres/default/createorupdate" class="widget-stats">
				<div><i class="fa fa-building-o fa-4x"></i></div>
				<span class="txt">Cadastrar ou Editar Unidade</span>
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
	let selectedValue;
	const selectElement = document.getElementById("mes");

	selectElement.addEventListener("change", (event) => {
		selectedValue = event.target.value;

		const { startDate, endDate } = getDatesFromMonth(selectedValue);

		const year = new Date().getFullYear();
		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&managementUnitCode=${12346710}&year=${year}&startDate=${startDate}&endDate=${endDate}`;
		exportLink.setAttribute('href', newHref);

	});

	function getDatesFromMonth(monthValue) {
		const year = new Date().getFullYear();
		const month = parseInt(monthValue, 10);
		const startDate = new Date(year, month - 1, 1);
		const endDate = new Date(year, month, 0);
		return {
			startDate: startDate.toISOString().slice(0, 10),
			endDate: endDate.toISOString().slice(0, 10),
		};
	}

	function downloadFile(url, filename) {
		const link = document.createElement('a');
		link.href = url;
		link.download = filename;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

	document.getElementById('exportLink').addEventListener('click', function (e) {
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
		$.get(url, function (data) {
			const zip = new JSZip();
			zip.file(filename, data);
			zip.generateAsync({ type: "blob" }).then(function (blob) {
				var url = URL.createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = 'Educacao.zip';
				link.click();
				URL.revokeObjectURL(url);
			})
				.catch(function (err) {
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
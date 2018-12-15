/* ==========================================================
 * QuickAdmin v2.0.0
 * tables.js
 * 
 * http://www.mosaicpro.biz
 * Copyright MosaicPro
 *
 * Built exclusively for sale @Envato Marketplaces
 * ========================================================== */ 

$(function()
{

	function fnInitCompleteCallback(that)
	{
		var p = that.parent();
    	var l = p.find('label');

    	l.each(function(index, el) {
    		var iw = $("<div>").addClass('col-md-8').appendTo($(el).parent());
    		$(el).parent().addClass('form-group');
            $(el).find('input, select').addClass('form-control').removeAttr('size').appendTo(iw);
            $(el).addClass('col-md-4 control-label');
    	});
	}

	/* DataTables */
	if ($('.dynamicTable').size() > 0)
	{
		$('.dynamicTable').each(function()
		{
			// DataTables with TableTools
			if ($(this).is('.tableTools'))
			{
				$(this).dataTable({
					"sPaginationType": "bootstrap",
					"sDom": "<'row'<'col-md-5'T><'col-md-3'l><'col-md-4'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
					"oLanguage": {
						"sLengthMenu": "_MENU_ per page"
					},
					"oTableTools": {
						"sSwfPath": "../extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
				    },
				    "fnInitComplete": function () {
				    	fnInitCompleteCallback(this);
			        }
				});
			}
			// colVis extras initialization
			else if ($(this).is('.colVis'))
			{
				$(this).dataTable({
					"sPaginationType": "bootstrap",
					"sDom": "<'row'<'col-md-3'f><'col-md-3'l><'col-md-6'C>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
					"oLanguage": {
						"sLengthMenu": "_MENU_ per page"
					},
					"fnInitComplete": function () {
				    	fnInitCompleteCallback(this);
			        }
				});
			}
			// default initialization
			else
			{
				$(this).dataTable({
					"sPaginationType": "bootstrap",
					"sDom": "<'row'<'col-md-5'T><'col-md-3'l><'col-md-4'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
					"oLanguage": {
						"sLengthMenu": "_MENU_ per page"
					},
					"fnInitComplete": function () {
				    	fnInitCompleteCallback(this);
			        }
				});
			}
		});
	}
});
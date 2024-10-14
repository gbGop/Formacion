$(document).ready(function () {
	let porcenimp = "0";
	let basicToast = document.querySelector('.basic-toast');
	let basicToastModal = document.querySelector('.basic-toast-modal');
	let showBasicToast = new bootstrap.Toast(basicToast);
	let showBasicToastModal = new bootstrap.Toast(basicToastModal);
	let dt_basic_table = $('#tblFacturas');
	let facturaForm = $("#frmFactura");
	let multimediafiles = [];
	let multimediafilesConfig = [];
	let tipodoc = $("#tipoDoc");
	let estados = [];
	let numfilesPreview = 0;
	estados[1] = "Aprobado";
	estados[2] = "Rechazado";
	estados[3] = "Anulado";
	estados[4] = "En Aprobación";
	$(".switchresp").on("change", function () {
		let resp = $(this).data('resp');
		$(".inputresp[data-resp='" + resp + "']").val('');
		$(".inputrecep[data-resp='" + resp + "']").val('');
		$(".inputenvio[data-resp='" + resp + "']").val('');
		$(".inputvenc[data-resp='" + resp + "']").val('');
		$(".inputrecep[data-resp='" + resp + "']").attr("readonly", true);
		$(".inputresp[data-resp='" + resp + "']").attr("readonly", true);
		$(".inputresp[data-resp='" + resp + "']").attr("required", true);
		$(".inputenvio[data-resp='" + resp + "']").attr("readonly", true);
		if ($(this).is(':checked')) {
			$(".inputresp[data-resp='" + resp + "']").removeAttr("required");
			$(".inputresp[data-resp='" + resp + "']").removeAttr("readonly");
			$(".inputrecep[data-resp='" + resp + "']").removeAttr("readonly");
			$(".inputenvio[data-resp='" + resp + "']").removeAttr("readonly");
			validateFechasResp(resp);
		}
	});
	$(".inputrecep").on("change", function () {
		let resp = $(this).data('resp');
		let valrecep = $(this).val();
		$(".inputenvio[data-resp='" + resp + "']").val(valrecep);
		calcVencimiento(resp);
		calcSLAReal(resp)
	});
	$(".inputenvio").on("change", function () {
		let resp = $(this).data('resp');
		calcVencimiento(resp);
		calcSLAReal(resp)
	});

	$("#frmFactura").submit(function (e) {
		e.preventDefault();
		let numFiles = $("#multimediaFiles")[0].files.length;
		if (numFiles == 0 && numfilesPreview == 0) {
			$(".tab-pane").removeClass("active");
			$("#contentdoc ").addClass("active");
			$(".toast-body").html("Debe adjuntar algún documento");
			$(".toast-header").addClass('bg-warning');
			showBasicToastModal.show();
		}else {
			let idFac = $("#idFact").val();
			if (idFac == "") {
				saveFactura();
			} else {
				updateFactura();
			}
		}
	})
	$(".btncancel").on("click", function () {
		$("#frmFactura")[0].reset();
		$('#modalFacturas').modal('hide')
	});
	$("#tipoDoc").on("change", function () {
		let doc = $(this).val();
		$("#montoTot").val('');
		$("#montoNet").val('');
		$("#montoImp").val('');
		$("#montoTot").prop("readonly", true);
		$("#montoNet").prop("readonly", true);
		if (doc === 'Z3I1b2FZN0xFWnlINlBJZzE5ZlZlZz09' || doc === 'SE5uWWd2aDc5NFFWMEtPbWF4bzlGZz09' || doc === 'RVYyREY2UnpSWGV5QmlFZ3dRWUkvZz09') {
			$("#montoNet").prop("readonly", false);
			$("#montoTot").val('');
		}

		else {
			$("#montoNet").val('');
			$("#montoTot").prop("readonly", false);
		}
	});
	$("#montoTot").on("change keyup", function () {
		let tipodoc = $("#tipoDoc");
		calcImpuesto(tipodoc);
	});
	$("#montoNet").on("change keyup", function () {
		calcImpuesto(tipodoc);
	});
	$("#mesCon").on("change", function () {
		let fecha = new Date();
		let currentYear = fecha.getFullYear();
		let currentMonth = fecha.getMonth() + 1;
		let mes = $(this).val();
		if (currentYear == $("#anualCon").val()) {
			if (mes > currentMonth) {
				$(".toast-body").html('No puede seleccionar un mes superior al actual');
				$(".toast-header").addClass('bg-warning');
				showBasicToastModal.show();
				$("#mesCon").val(currentMonth);
			}
		}
	});
	$("#anualCon").on("change", function () {
		let fecha = new Date();
		let currentYear = fecha.getFullYear();
		let currentMonth = fecha.getMonth() + 1;
		let mes = $("#mesCon").val();
		if (currentYear == $("#anualCon").val()) {
			if (mes > currentMonth) {
				$(".toast-body").html('No puede seleccionar un mes superior al actual');
				$(".toast-header").addClass('bg-warning');
				showBasicToastModal.show();
				$("#mesCon").val(currentMonth);
			}
		}
	});
	$("#servOtor").on("change", function () {
		let valServ = $(this).val();
		$(".contentservOtorOtro").addClass('d-none');
		$("#servOtorOtro").prop("required", false);
		if (valServ === "Tld1d3ZjdE5xRWNZS3N1cVJHcVAxdz09") {
			$(".contentservOtorOtro").removeClass("d-none");
			$("#servOtorOtro").prop("required", "required");
		}
	})
	$("#conOta").on("change", function () {
		$(".contenOta").addClass("d-none");
		$("#btnbusCuenta").prop("disabled", false);
		$("#cuenta").prop("required", "required");
		$("#cuentanom").prop("required", "required");
		$("#proyecto").prop("required", "required");
		$("#proyecto").val("");
		$("#curso").prop("required", false);
		$("#nombOta").prop("required", false);
		$("#curso").val("");
		$("#cuenta").val("");
		$("#cuentanom").val("");
		$("#numOta").val("");
		$("#OtaNumero").val("");
		$("#nombOta").val("");
		if ($(this).is(':checked')) {
			$(".contenOta").removeClass("d-none");
			$("#btnbusCuenta").prop("disabled", "disabled");
			$("#curso").prop("required", true);
			$("#nombOta").prop("required", true);
		}
	})
	$("#btnsearchprov").on("click", function () {
		let rut = $("#rutProvee").val();
		searchProveedor(rut);
	});
	$("#btnsearchota").on("click", function () {
		let ota = $("#numOta").val();
		searchOta(ota);
	});
	$("#selcuenta").on("change", function () {
		let idCuenta = $(this).val();
		$("#selproyecto").val();
		$(".optionProyecto").addClass('d-none');
		$(".optionProyecto"+idCuenta).removeClass('d-none');
	});
	$("#btnbusCuenta").on("click", function () {
		$("#modalCuentProy").modal("show");
		$("#selcuenta").val('');
		$("#selproyecto").val('');
	});
	$("#btnaddCuentProy").on("click", function () {
		let cuenta = $("#selcuenta").val();
		let cuentanombre = $("#selcuenta option:selected").html();
		let proyecto = $("#selproyecto").val();
		$("#proyecto").val(proyecto);
		$("#cuenta").val(cuenta);
		$("#cuentanom").val(cuentanombre);
		$('#modalCuentProy').modal('hide');
	});
	$(".create-new").on("click", function () {
		$('#modalFacturas').modal('show')
	});
	$(document).on("click", ".btn-export", function () {
		$('#frmreportexcel').submit();
	});
	$("#btnCloseModal").on("click", function () {
		$('#modalFacturas').modal('hide')
	});
	$("#btnCloseModalCuenProy").on("click", function () {
		$('#modalCuentProy').modal('hide');
	});
	$(document).on('hidden.bs.modal', '#modalFacturas', function () {
		let multimediafiles = [];
		let multimediafilesConfig = [];
		numfilesPreview = 0;
		$("#multimediaFiles").prop("required", true);
		$("#frmFactura")[0].reset();
		$("#observaciones").text('');
		$(".inputresp").attr("readonly", true);
		$(".contenOta").addClass("d-none");
		$(".inputrecep").prop("readonly", true);
		$(".inputenvio").prop("readonly", true);
		$("#numdoc").removeAttr("readonly", true);
		$("#conOta").prop("checked", false);
		$("#conOta").change();
		$(".badge-pill").html('0')
		$(".badge-pill").removeClass('badge-dager');
		$(".badge-pill").addClass('badge-success');
		$("#ex1-tab-1").click();
		multimediaUpload(multimediafiles, multimediafilesConfig);
	});
	if (dt_basic_table.length) {
		var dt_basic = dt_basic_table.DataTable({
			ajax: "?sw=getFacturas",
			serverSide: false,
			processing: true,
			columns: [
				{data: 'factur_numdoc', name: 'N° Documento'},
				{data: 'factur_proveerut', name: 'Rut Proveedor'},
				{data: 'proveedor_nombre', name: 'Nombre Proveedor'},
				{data: 'tipdoc_dsc', name: 'Tipo Documento'},
				{data: 'factur_montoto', name: 'Monto Total'},
				{data: 'factur_fecemision', name: 'Fecha emisión'},
				{data: 'factur_numota', name: 'N° OTA'},
				{data: 'factur_status', name: 'Estado'},
				{data: 'factur_anual', name: 'Año'},
				{data: 'factur_respgast', name: 'Responsable'},
				{data: '', name: 'Responsable'},
			],
			columnDefs: [
				{
					// Actions
					targets: 10,
					title: 'Acciones',
					orderable: false,
					render: function () {
						$('[data-toggle="tooltip"]').tooltip();
						return (
							'<div class="dropdown">\n' +
							'  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
							'Opciones' +
							'  </button>\n' +
							'  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\n' +
							'    <a class="dropdown-item item-edit" href="javascript:void(0)"><i class="fa fa-edit"></i> Editar</a>\n' +
							'    <a class="dropdown-item item-del" href="javascript:void(0)"><i class="fa fa-eraser"></i> Eliminar</a>\n' +
							'  </div>\n' +
							'</div>'
						);
					}
				}, {
					targets: 7,
					render: function (data) {
						let estado = estados[data];
						return estado;
					}
				}, {
					targets: 4,
					render: function (data) {
						return Math.round(data);
					}
				}
			],
			order: '',
			dom: '<"card-header border-bottom p-4"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-end mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
			displayLength: 5,
			lengthMenu: [5, 10, 25, 50, 75, 100],
			buttons: [
				{
					text: 'Agregar nuevo documento',
					className: 'btn btn-success create-new',
					attr: {
						'data-bs-toggle': 'modal',
						'data-bs-target': '#modalFacturas'
					},
					init: function (api, node, config) {
						$(node).removeClass('btn-secondary');
					}
				},
				{

					text: 'Exportar facturas a excel',
					className: 'btn btn-link btn-export',
					init: function (api, node, config) {
						$(node).removeClass('btn-secondary');
						$(node).removeClass('dt-button');
					}
				}
			],
			oLanguage: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar: ",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			initComplete: function () {
				this.api()
					.columns()
					.every(function () {
						var that = this;
						$('.inputfilter').on('keyup change clear', function () {
							if (that.search() !== this.value) {
								that.search(this.value).draw();
							}
						});
					});
			},
		});
	}
	$('.dt_basic_table tbody').on('click', '.item-del', function () {
		let rowData = dt_basic.row($(this).parents('tr')).data();
		swal({
				title: 'Eliminar Factura',
				text: '¿Estas seguro de eliminar esta factura?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Si, eliminar!',
				cancelButtonText: 'Cancelar',
				customClass: {
					confirmButton: 'button btn-primary  small mr-4',
					cancelButton: 'button btn-danger small'
				},
				buttonsStyling: true
			},
			function (inputValue) {
				if (inputValue) {
					deleteFactura(rowData.factur_id);
				}
			});
	});

	function deleteFactura(factur_id) {
		$.ajax({
			url: '?sw=finanzas_facturas_delete',
			data: {
				idFac: factur_id
			},
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					dt_basic.ajax.reload();
					$(".toast-body").html(datos.message);
				} else {
					$(".toast-body").html(datos.message);
					$(".toast-header").addClass('bg-warning');
				}
				showBasicToast.show();
			}, complete: function () {
			}
		})
	}

	$('.dt_basic_table tbody').on('click', '.item-edit', function () {
		$("#modalFacturas").find('.modal-title').text('Gestionar factura');
		$("#modalFacturas").find('.modalBtns button[type="submit"]').text('Actualizar');
		let rowData = dt_basic.row($(this).parents('tr')).data();
		if (rowData.factur_ota == 1) {
			$("#conOta").prop("checked", true);
			$("#conOta").change();
		}
		facturaForm.find('input[name="idFact"]').val(rowData.factur_id);
		facturaForm.find('input[name="numdoc"]').val(rowData.factur_numdoc);
		facturaForm.find('input[name="numdoc"]').prop("readonly", true);
		facturaForm.find('input[name="rutProvee"]').val(rowData.factur_proveerut);
		facturaForm.find('select[name="tipoDoc"]').val(rowData.factur_tipdocid);
		$("#tipoDoc").change();
		facturaForm.find('input[name="montotot"]').val(Math.round(rowData.factur_montoto));
		facturaForm.find('input[name="montoNet"]').val(Math.round(rowData.factur_montonet));
		facturaForm.find('input[name="montoImp"]').val(Math.round(rowData.factur_impuest));
		facturaForm.find('input[name="numOta"]').val(rowData.factur_numota);
		facturaForm.find('input[name="nombreProvee"]').val(rowData.factur_proveenom);
		facturaForm.find('input[name="cuenta"]').val(rowData.factur_cuenta);
		facturaForm.find('input[name="cuentanom"]').val(rowData.factur_cuenta_nombre);
		facturaForm.find('input[name="proyecto"]').val(rowData.factur_proyecto);
		facturaForm.find('input[name="fechaEmision"]').val(rowData.factur_fecemision);
		facturaForm.find('input[name="servOtorOtro"]').val(rowData.factur_servotro);
		facturaForm.find('input[name="curso"]').val(rowData.factur_curso);
		facturaForm.find('input[name="nombOta"]').val(rowData.factur_otanombre);
		facturaForm.find('textarea[name="observaciones"]').val(rowData.factur_observacion);
		facturaForm.find('select[name="servOtor"]').val(rowData.factur_servid);
		facturaForm.find('select[name="anualCon"]').val(rowData.factur_anual);
		facturaForm.find('select[name="mesCon"]').val(rowData.factur_mes);
		facturaForm.find('select[name="cui"]').val(rowData.factur_cui);
		facturaForm.find('select[name="respGast"]').val(rowData.factur_respgastEncodeado);
		facturaForm.find('select[name="estdoc"]').val(rowData.factur_status);
		//Se deben cargar los responsables de la factura
		getProveedoresByIdFac(rowData.factur_id);
		getDocumentosByIdFac(rowData.factur_id);
		//Se deben cargar los documentos de la factura
		$("#modalFacturas").modal("show");
	});
	$('#tblFacturas thead tr').clone(true).appendTo('#contentfilters');

	$('#contentfilters tr th').each(function (i) {
		$(this).addClass("p-1");
		var title = $(this).text(); //es el nombre de la columna
		if (title === "N° Documento" || title === "Tipo Documento" || title === "Rut Proveedor" || title === "Nombre Proveedor" || title === "N° OTA" || title === "Año" || title === "Responsable") {
			$(this).html('<input class="form-control" type="text" style="min-width: 150px" placeholder="' + title + '" />');

			$('input', this).on('keyup change', function () {
				if (dt_basic.column(i).search() !== this.value) {
					dt_basic
						.column(i)
						.search(this.value)
						.draw();
				}
			});
		} else {
			$(this).remove();
		}
	});
	$('#multimediaFiles').on("filepredelete", function (event, key, jqXHR, data) {
		var abort = true;
		if (confirm("Esta seguro de eliminar este archivo")) {
			abort = false;
		}
		return abort; // you can also send any data/object that you can receive on `filecustomerror` event
	});

	function multimediaUpload(preview, previewConfig) {
		$('#multimediaFiles').fileinput('destroy');
		multimediaFiles = $("#multimediaFiles").fileinput({
			initialPreview: preview,
			initialPreviewConfig: previewConfig,
			overwriteInitial: false,
			initialPreviewAsData: true,
			initialPreviewShowDelete: true,
			language: "es",
			maxFilePreviewSize: 10240,
			showCancel: false,
			showUpload: false,
			showRemove: false,
			deleteUrl: "?sw=finanzas_delete_file"
		}).on('filebeforedelete', function (event, id, index) {

		});
		;
	}

	function updateFactura() {
		const form = document.getElementById('frmFactura');
		const formData = new FormData(form);
		$("#btnsavefactura").prop("disabled", true);
		$(".btncancel").prop("disabled", true);
		$.ajax({
			url: '?sw=finanzas_facturas_update',
			data: formData,
			processData: false,
			contentType: false,
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					$(".toast-body").html(datos.message);
					$("#frmFactura")[0].reset();
					$(".inputresp").prop("readonly", true);
					$(".inputrecep").prop("readonly", true);
					$(".inputenvio").prop("readonly", true);
					$('#modalFacturas').modal('hide');
					dt_basic.ajax.reload();
				} else {
					$(".toast-body").html("Ha ocurrido un error al ingresar la factura");
					$(".toast-header").addClass('bg-warning');
				}
				showBasicToast.show();
			}, complete: function () {
				$("#btnsavefactura").prop("disabled", false);
				$(".btncancel").prop("disabled", false);
			}
		})
	}

	function searchProveedor(rutprov) {
		$.ajax({
			url: '?sw=getProveedores',
			data: {
				rut: rutprov
			},
			type: 'post',
			dataType: 'json',
			success: function (response) {
				$(".toast-header").removeClass('bg-warning');
				//let datos = JSON.parse(response);
				if (response.code == 200) {
					$("#nombreProvee").val(response.proveedor);
				} else {
					$(".toast-body").html('No se ha encontrado un proveedor con ese rut');
					$(".toast-header").addClass('bg-warning');
					showBasicToastModal.show();
				}
			}
		})
	}

	function searchOta(ota) {
		$.ajax({
			url: '?sw=getOTA',
			data: {
				ota: ota
			},
			type: 'post',
			dataType: 'json',
			success: function (response) {
				$(".toast-header").removeClass('bg-warning');
				//let datos = JSON.parse(response);
				if (response.code == 200) {
					$("#nombOta").val(response.ota);
					$("#curso").val(response.curso);
					$("#cuenta").val(response.cuenta);
					$("#cuentanom").val(response.cuenta_nombre);
					$("#proyecto").val(response.proyecto);
				} else {
					$(".toast-body").html('No se ha encontrado OTA con la ota suministrada');
					$(".toast-header").addClass('bg-warning');
					showBasicToastModal.show();
				}
			}
		})
	}

	function saveFactura() {
		const form = document.getElementById('frmFactura');
		const formData = new FormData(form);
		$("#btnsavefactura").prop("disabled", true);
		$(".btncancel").prop("disabled", true);
		$.ajax({
			url: '?sw=finanzas_facturas_save',
			data: formData,
			processData: false,
			contentType: false,
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					dt_basic.ajax.reload();
					$(".toast-body").html(datos.message);
					$("#frmFactura")[0].reset();
					$(".inputresp").prop("readonly", true);
					$(".inputrecep").prop("readonly", true);
					$(".inputenvio").prop("readonly", true);
					showBasicToast.show();
					$('#modalFacturas').modal('hide');
				} else {
					showBasicToast.show();
					$(".toast-body").html(datos.message);
					$(".toast-header").addClass('bg-warning');
				}
			}, complete: function () {
				$("#btnsavefactura").prop("disabled", false);
				$(".btncancel").prop("disabled", false);
			}
		})
	}

	function getProveedoresByIdFac(idfac) {
		let respformat = []
		respformat[1] = "Sup";
		respformat[2] = "Eje";
		respformat[3] = "Con";
		respformat[4] = "Jef";
		respformat[5] = "Jef1";
		respformat[6] = "Jef2";
		$.ajax({
			url: '?sw=getResponsableByIdFac',
			data: {
				idFac: idfac
			},
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$.map(datos, function (value, key) {
					let rut = value.resprut;
					let tipo = key;
					let recep = value.recep;
					let envio = value.envio;
					let resp = respformat[tipo];
					$("#aplica" + resp).prop("checked", true);
					$("#selresp" + resp).prop("readonly", false);
					$("#dateRecep" + resp).prop("readonly", false);
					$("#dateEnvio" + resp).prop("readonly", false);
					$("#dateEnvio" + resp).val(envio);
					$("#dateRecep" + resp).val(recep);
					$("#selresp" + resp).val(rut);
					$("#dateEnvio" + resp).change();
					$("#dateRecep" + resp).change();
				});
			}, complete: function () {
			}
		})
	}

	function getDocumentosByIdFac(idfac) {
		let multimediafiles = [];
		let multimediafilesConfig = [];
		$.ajax({
			url: '?sw=getDocumentoByIdFac',
			data: {
				idFac: idfac
			},
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$.map(datos, function (value, key) {
					numfilesPreview++;
					let config = [];
					multimediafiles.push("https://www.goptest.cl/bch_masco/admin/upload/finanzas/" + value.findoc_url);
					multimediafilesConfig.push({
						'type': 'pdf',
						'description': value.findoc_nombre,
						'caption': value.findoc_nombre,
						'key': value.findoc_id
					});
				});
				multimediaUpload(multimediafiles, multimediafilesConfig);
			}, complete: function () {
				if (numfilesPreview > 0) {
					$("#multimediaFiles").prop("required", false);
				}
			}
		})
	}

	function calcImpuesto(element) {
		let impuesto;
		let monto;
		let montoTot = 0;
		let montoenable;
		let doc = element.val();
		if (doc === 'Z3I1b2FZN0xFWnlINlBJZzE5ZlZlZz09' || doc === 'SE5uWWd2aDc5NFFWMEtPbWF4bzlGZz09' || doc === 'RVYyREY2UnpSWGV5QmlFZ3dRWUkvZz09') {
			if (doc === 'Z3I1b2FZN0xFWnlINlBJZzE5ZlZlZz09') {
				porcenimp = "0.19";
			} else {
				porcenimp = "0.13";
			}
			montoenable = "neto";
		} else {
			montoenable = "total";
		}
		if (doc === "SjFDbTFRYUV1bWRZd0srVGM5ZDJJdz09" || doc === "QmxLOGNQNmt1eVVaeitDVGdYazU5dz09" ) {
			porcenimp = "-1";
		}
		if (montoenable === "total") {
			monto = $("#montoTot").val();
			impuesto = monto * porcenimp;
		} else {
			monto = $("#montoNet").val();
			impuesto = monto * porcenimp;
			if (doc === 'SE5uWWd2aDc5NFFWMEtPbWF4bzlGZz09') {
				montoTot = Math.round(monto) - Math.round(impuesto);
			}
			if (doc === 'Z3I1b2FZN0xFWnlINlBJZzE5ZlZlZz09') {
				montoTot = Math.round(monto) + parseInt(Math.round(impuesto));
			}
			if (doc === 'RVYyREY2UnpSWGV5QmlFZ3dRWUkvZz09') {
				montoTot = Math.round(monto);
			}

			$("#montoTot").val(montoTot);
		}
		if (impuesto > 0 && doc !== "SjFDbTFRYUV1bWRZd0srVGM5ZDJJdz09" && doc !== "QmxLOGNQNmt1eVVaeitDVGdYazU5dz09" && doc !== "RVYyREY2UnpSWGV5QmlFZ3dRWUkvZz09") {
			$("#montoImp").val(Math.round(impuesto));
		}
	}

	function LPad(sValue, iPadBy) {
		sValue = sValue.toString();
		return sValue.length < iPadBy ? LPad("0" + sValue, iPadBy) : sValue;
	}

	function calcVencimiento(resp) {
		let rececp = $(".inputrecep[data-resp='" + resp + "']").val();
		let sla = parseInt($(".sladef" + resp).html());
		if (rececp !== "") {
			let recepstr = rececp.split("-");
			let recepdateInit = new Date(recepstr[0], recepstr[1] - 1, recepstr[2]);
			let recepdate = new Date(recepstr[0], recepstr[1], recepstr[2]);
			recepdateInit.setDate(recepdateInit.getDate() + (sla - 1));
			while (recepdateInit.getDay() === 6 || recepdateInit.getDay() === 0) {
				recepdateInit.setDate(recepdateInit.getDate() + (1));
				sla++;
			}
			recepdate.setDate(recepdate.getDate() + (sla - 1));
			$(".inputvenc[data-resp='" + resp + "']").val(recepdate.getFullYear() + "-" + LPad(recepdate.getMonth(), 2) + "-" + LPad(recepdate.getDate(), 2));
		}
	}

	function validateFechasResp(resp) {
		let lastValEnv = "";
		let valVenc = "";
		let lastvalVenc = "";
		$(".inputvenc").each(function () {
			valVenc = $(this).val();
			if (valVenc === "") {
				return false;
			}
			lastvalVenc = valVenc;
		});
		$(".inputenvio").each(function () {
			if (!$(this).attr('readonly') && $(this).data('resp') != resp) {
				let valEnvio = $(this).val();
				lastValEnv = valEnvio;
				if (lastValEnv === "") {
					lastValEnv = lastvalVenc;
					return false
				}
			}
		});
		if (lastValEnv !== "") {
			$(".inputrecep[data-resp='" + resp + "']").val(lastValEnv);
		}
		calcVencimiento(resp);
	}

	function calcSLAReal(resp) {
		let difference = 0;
		let slareal = 0;
		let rececp = $(".inputrecep[data-resp='" + resp + "']").val();
		let respuesta = $(".inputenvio[data-resp='" + resp + "']").val();
		let sla = parseInt($(".sladef" + resp).html());
		let nolaborables = 0;
		let datevec;
		if (rececp !== "" && respuesta !== "") {
			let recepstr = rececp.split("-"),
				respstr = respuesta.split("-"),
				recepdate = new Date(recepstr[0], recepstr[1] - 1, recepstr[2]),
				respdate = new Date(respstr[0], respstr[1] - 1, respstr[2]);
			datevec = recepdate;
			datevec.setDate(datevec.getDate() + (sla - 1));
			while (datevec.getDay() === 6 || datevec.getDay() === 0) {
				datevec.setDate(datevec.getDate() + 1);
			}
			let recepdateInit = datevec;
			difference = respdate.getTime() - datevec.getTime();
			recepdateInit.setDate(recepdateInit.getDate() + 1);
			while (recepdateInit.getDay() === 6 || recepdateInit.getDay() === 0) {
				nolaborables++;
				recepdateInit.setDate(recepdateInit.getDate() + 1);
			}
			slareal = difference / (1000 * 60 * 60 * 24) - nolaborables;
			if (slareal < 0) {
				slareal = 0;
			}
		}
		$("#slareal" + resp).removeClass("badge-danger");
		$("#slareal" + resp).addClass("badge-success");
		if (slareal > 0) {
			$("#slareal" + resp).removeClass("badge-success");
			$("#slareal" + resp).addClass("badge-danger");
		}
		$("#slareal" + resp).html(slareal);
	}

	multimediaUpload(multimediafiles, multimediafilesConfig);
});
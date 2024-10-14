$(document).ready(function () {
	let porcenimp = "0";
	let basicToast = document.querySelector('.basic-toast');
	let basicToastModal = document.querySelector('.basic-toast-modal');
	let showBasicToast = new bootstrap.Toast(basicToast);
	let showBasicToastModal = new bootstrap.Toast(basicToastModal);
	let dt_basic_table = $('#tblReembolsos');
	let frmReembolso = $("#frmReembolso");
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

	$("#frmReembolso").submit(function (e) {
		e.preventDefault();
		let numFiles = $("#multimediaFiles")[0].files.length;
		if (numFiles == 0 && numfilesPreview==0) {
			$(".toast-body").html("Debe adjuntar algún documento");
			$(".toast-header").addClass('bg-warning');
			showBasicToastModal.show();
		}else {
			let idFac = $("#idReembo").val();
			if (idFac == "") {
				saveReembolso();
			} else {
				updateReembolso();
			}
		}
	})
	$(".btncancel").on("click", function () {
		$("#frmReembolso")[0].reset();
		$('#modalReembolsos').modal('hide')
	});
	$("#tipoDoc").on("change", function () {
		let doc = $(this).val();
		$("#tipDocOtro").val('')
		$(".contenttipDocOtro").addClass("d-none");
		if (doc === "U3YvV3VQWEEyZnlRc04reGtLY0ZBUT09") {
			$(".contenttipDocOtro").removeClass("d-none");
		}
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
		$("#numOta").prop("required", false);
		$("#cuenta").prop("required", "required");
		$("#proyecto").prop("required", "required");
		$("#curso").prop("required", false);
		$("#nombOta").prop("required", false);
		$("#proyecto").val("");
		$("#curso").val("");
		$("#cuenta").val("");
		$("#cuentanom").val("");
		$("#numOta").val("");
		$("#OtaNumero").val("");
		$("#nombOta").val("");
		if ($(this).is(':checked')) {
			$(".contenOta").removeClass("d-none");
			$("#btnbusCuenta").prop("disabled", "disabled");
			$("#numOta").prop("required", true);
			$("#curso").prop("required", true);
			$("#nombOta").prop("required", true);
		}
	})
	$("#btnsearchprov").on("click", function () {
		let rut = $("#rutColabora").val();
		searchColaborador(rut);
	});
	$("#btnsearchota").on("click", function () {
		let ota = $("#numOta").val();
		searchOta(ota);
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
		$('#modalReembolsos').modal('show')
	});
	$(document).on("click", ".btn-export", function () {
		$('#frmreportexcel').submit();
	});
	$("#btnCloseModal").on("click", function () {
		$('#modalReembolsos').modal('hide')
	});
	$("#btnCloseModalCuenProy").on("click", function () {
		$('#modalCuentProy').modal('hide');
	});
	$(document).on('hidden.bs.modal', '#modalReembolsos', function () {
		let multimediafiles = [];
		let multimediafilesConfig = [];
		numfilesPreview = 0;
		$("#multimediaFiles").prop("required", true);
		$("#frmReembolso")[0].reset();
		$("#observaciones").text('');
		$("#numdoc").html('');
		$(".inputresp").attr("readonly", true);
		$(".contenOta").addClass("d-none");
		$(".inputrecep").prop("readonly", true);
		$(".inputenvio").prop("readonly", true);
		$("#btnbusCuenta").removeAttr("disabled");
		$("#conOta").prop("checked", false);
		$("#conOta").change();
		$(".slarealEjec").html('0')
		$(".slarealEjec").removeClass('badge-dager');
		$(".slarealEjec").addClass('badge-success');
		$("#ex1-tab-1").click();
		multimediaUpload(multimediafiles, multimediafilesConfig);
	});
	if (dt_basic_table.length) {
		var dt_basic = dt_basic_table.DataTable({
			ajax: "?sw=getReembolsos",
			serverSide: false,
			processing: true,
			columns: [
				{data: 'reembo_numdoc', name: 'N° Documento'},
				{data: 'reembo_proveerut', name: 'Rut Colaborador'},
				{data: 'proveedor_nombre', name: 'Nombre Colaborador'},
				{data: 'tipdoc_dsc', name: 'Tipo Documento'},
				{data: 'reembo_montoto', name: 'Monto Total'},
				{data: 'reembo_fecemision', name: 'Fecha emisión'},
				{data: 'reembo_numota', name: 'N° OTA'},
				{data: 'reembo_status', name: 'Estado'},
				{data: 'reembo_anual', name: 'Año'},
				{data: 'reembo_respgast', name: 'Responsable'},
				{data: '', name: 'Responsable'}
			],
			columnDefs: [
				{
					// Actions
					targets: -1,
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
						'data-bs-target': '#modalReembolsos'
					},
					init: function (api, node, config) {
						$(node).removeClass('btn-secondary');
					}
				},
				{

					text: 'Exportar reembolsos a excel',
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
			}
		});
	}
	$('.dt_basic_table tbody').on('click', '.item-del', function () {
		let rowData = dt_basic.row($(this).parents('tr')).data();
		swal({
				title: 'Eliminar Reembolso',
				text: '¿Estas seguro de eliminar este reembolso?',
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
					deleteReembolso(rowData.reembo_id);
				}
			});
	});

	function deleteReembolso(reembo_id) {
		$.ajax({
			url: '?sw=finanzas_reembolso_delete',
			data: {
				idFac: reembo_id
			},
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					dt_basic.ajax.reload();
					let cleanResponse = response;
					$(".toast-body").html(cleanResponse);
				} else {
					let cleanResponse = response;
					$(".toast-body").html(cleanResponse);
					$(".toast-header").addClass('bg-warning');
				}
				showBasicToast.show();
			}, complete: function () {
			}
		})
	}

	$('.dt_basic_table tbody').on('click', '.item-edit', function () {
		$("#modalReembolsos").find('.modal-title').text('Gestionar reembolso');
		$("#modalReembolsos").find('.modalBtns button[type="submit"]').text('Actualizar');
		let rowData = dt_basic.row($(this).parents('tr')).data();
		if (rowData.reembo_ota == 1) {
			$("#conOta").prop("checked", true);
			$("#conOta").change();
		}
		$("#numdoc").html(rowData.reembo_numdoc);
		frmReembolso.find('input[name="idReembo"]').val(rowData.reembo_id);
		frmReembolso.find('input[name="rutColabora"]').val(rowData.reembo_proveerut);
		frmReembolso.find('input[name="nombreColabora"]').val(rowData.reembo_proveenom);
		frmReembolso.find('select[name="tipoDoc"]').val(rowData.reembo_tipdocid);
		$("#tipoDoc").change();
		frmReembolso.find('input[name="tipDocOtro"]').val(Math.round(rowData.reembo_tipdoc_otro));
		frmReembolso.find('input[name="montotot"]').val(Math.round(rowData.reembo_montoto));
		frmReembolso.find('input[name="numOta"]').val(rowData.reembo_numota);
		frmReembolso.find('input[name="nombreColabora"]').val(rowData.reembo_proveenom);
		frmReembolso.find('input[name="cuenta"]').val(rowData.reembo_cuenta);
		frmReembolso.find('input[name="cuentanom"]').val(rowData.reembo_cuenta_nombre);
		frmReembolso.find('input[name="proyecto"]').val(rowData.reembo_proyecto);
		frmReembolso.find('input[name="fechaEmision"]').val(rowData.reembo_fecemision);
		frmReembolso.find('input[name="servOtorOtro"]').val(rowData.reembo_servotro);
		frmReembolso.find('input[name="curso"]').val(rowData.reembo_curso);
		frmReembolso.find('input[name="nombOta"]').val(rowData.reembo_otanombre);
		frmReembolso.find('textarea[name="observaciones"]').val(rowData.reembo_observacion);
		frmReembolso.find('select[name="servOtor"]').val(rowData.reembo_servid);
		frmReembolso.find('select[name="anualCon"]').val(rowData.reembo_anual);
		frmReembolso.find('select[name="mesCon"]').val(rowData.reembo_mes);
		frmReembolso.find('select[name="cui"]').val(rowData.reembo_cui);
		frmReembolso.find('select[name="respGast"]').val(rowData.reembo_respgastEncodeado);
		frmReembolso.find('select[name="estdoc"]').val(rowData.reembo_status);
		//Se deben cargar los responsables del reembolso
		getProveedoresByIdReembo(rowData.reembo_id);
		getDocumentosByIdReembo(rowData.reembo_id);
		//Se deben cargar los documentos del reembolso
		$("#modalReembolsos").modal("show");
	});
	$('#tblReembolsos thead tr').clone(true).appendTo('#contentfilters');

	$('#contentfilters tr th').each(function (i) {
		$(this).addClass("p-1");
		var title = $(this).text(); //es el nombre de la columna
		if (title === "N° Documento" || title === "Tipo Documento" || title === "Rut Colaborador" || title === "Nombre Colaborador" || title === "N° OTA" || title === "Año" || title === "Responsable") {
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
			deleteUrl: "?sw=finanzas_delete_file_reemb"
		}).on('filebeforedelete', function (event, id, index) {

		});
		;
	}

	function updateReembolso() {
		const form = document.getElementById('frmReembolso');
		const formData = new FormData(form);
		$("#btnsaveReembolso").prop("disabled", true);
		$(".btncancel").prop("disabled", true);
		$.ajax({
			url: '?sw=finanzas_reembolso_update',
			data: formData,
			processData: false,
			contentType: false,
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					let cleanResponse = datos.message;
					$(".toast-body").html(cleanResponse);
					$("#frmReembolso")[0].reset();
					$(".inputresp").prop("readonly", true);
					$(".inputrecep").prop("readonly", true);
					$(".inputenvio").prop("readonly", true);
					$('#modalReembolsos').modal('hide');
					dt_basic.ajax.reload();
				} else {
					$(".toast-body").html("Ha ocurrido un error al ingresar el reembolso");
					$(".toast-header").addClass('bg-warning');
				}
				showBasicToast.show();
			}, complete: function () {
				$("#btnsaveReembolso").prop("disabled", false);
				$(".btncancel").prop("disabled", false);
			}
		})
	}

	function searchColaborador(rut) {
		$.ajax({
			url: '?sw=getColaborador',
			data: {
				rut: rut
			},
			type: 'post',
			dataType: 'json',
			success: function (response) {
				$(".toast-header").removeClass('bg-warning');
				//let datos = JSON.parse(response);
				if (response.code == 200) {
					$("#nombreColabora").val(response.colaborador);
				} else {
					$(".toast-body").html('No se ha encontrado un colaborador con ese rut');
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

	function saveReembolso() {
		const form = document.getElementById('frmReembolso');
		const formData = new FormData(form);
		$("#btnsaveReembolso").prop("disabled", true);
		$(".btncancel").prop("disabled", true);
		$.ajax({
			url: '?sw=finanzas_reembolso_save',
			data: formData,
			processData: false,
			contentType: false,
			type: 'post',
			success: function (response) {
				let datos = jQuery.parseJSON(response);
				$(".toast-header").removeClass('bg-warning');
				if (datos.code == 200) {
					dt_basic.ajax.reload();
					let cleanResponse = datos.message;
					$(".toast-body").html(cleanResponse);
					$("#frmReembolso")[0].reset();
					$(".inputresp").prop("readonly", true);
					$(".inputrecep").prop("readonly", true);
					$(".inputenvio").prop("readonly", true);
					showBasicToast.show();
					$('#modalReembolsos').modal('hide');
				} else {
					showBasicToast.show();
					let cleanResponse = datos.message;
					$(".toast-body").html(cleanResponse);
					$(".toast-header").addClass('bg-warning');
				}
			}, complete: function () {
				$("#btnsaveReembolso").prop("disabled", false);
				$(".btncancel").prop("disabled", false);
			}
		})
	}

	function getProveedoresByIdReembo(idReemb) {
		let respformat = []
		respformat[1] = "Sup";
		respformat[2] = "Eje";
		respformat[3] = "Con";
		respformat[4] = "Jef";
		respformat[5] = "Jef1";
		respformat[6] = "Jef2";
		$.ajax({
			url: '?sw=getResponsableByIdReembo',
			data: {
				idReemb: idReemb
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

	function getDocumentosByIdReembo(idRem) {
		let multimediafiles = [];
		let multimediafilesConfig = [];
		$.ajax({
			url: '?sw=getDocumentoByIdReembo',
			data: {
				idRem: idRem
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
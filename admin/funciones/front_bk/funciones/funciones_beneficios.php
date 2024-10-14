<?php
function DownloadFile_2022($id){
    $File=DownloadFile_2022_data($id);
    $filepath=$File[0]->url;
    //echo "<br>$filepath"; exit();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    flush(); // Flush system output buffer
    readfile($filepath);
    die();
    //header("Location: https://gcloud.cl/bch/Bch_Procedimiento_Becas_Pregrado_2022_1.pdf");
}
function BeneficiosPreguntasFrecuentes($PRINCIPAL, $idm){
	
	$PregRes=BeneficiosPreguntasFrecuentes_data($idm);
	//print_r($PregRes);
	$cuenta_respuestas=count($PregRes);
	//echo "cuenta ".$cuenta_respuestas;
	foreach($PregRes as $unico){
		$cuentaRow++;
		if($cuentaRow=="1"){
			$show=" show ";
		} else {
			$show="  ";
		}
		$row_item_preguntas_respuestas.="
						  <div class='accordion-item'>
				    <h2 class='accordion-header' id='heading".$unico->id."'>
				      <button class='accordion-button-card' type='button' data-bs-toggle='collapse' data-bs-target='#collapse".$unico->id."' aria-expanded='true' aria-controls='collapse".$unico->id."'>
				        <h4 class=''>".(($unico->pregunta))."</h4>
				      </button>
				    </h2>
				    <div id='collapse".$unico->id."' class='accordion-collapse collapse ".$show."' aria-labelledby='heading".$unico->id."' data-bs-parent='#accordionExample'>
				      <div class='accordion-body'>
				        ".($unico->respuesta)."
				      </div>
				    </div>
				  </div>";
			
			
	}
	
	if($cuenta_respuestas>0){
    $PRINCIPAL             = str_replace("{ACCORDION_ROW_ITEM}", ($row_item_preguntas_respuestas), $PRINCIPAL);
		
	} else {
    $PRINCIPAL             = str_replace("{ACCORDION_ROW_ITEM}", "No existen preguntas y respuestas para esta familia de beneficios.", $PRINCIPAL);
		
	}
	
	
return($PRINCIPAL);		
	
	
	
}


function ColocaCamposFormularioVestuario($PRINCIPAL, $datos, $rut){
	
	
	$prendas=$datos[2];
	//print_r($prendas);
	
	foreach($prendas as $prenda){
		$tiene_talla_ingresada=TieneDatosTallaPorUsuarioPorPrenda($rut, $prenda->temporada, $prenda->id_prenda);
		
		if($prenda->combobox_talla>0){ 
			$row_talla .= file_get_contents("views/beneficios/78_row_talla_combo.html"); 
			$tallas=VestuarioTraigoTallas($prenda->combobox_talla);
			$options_tallas="<option value=''>-Seleccione-</option>";
			foreach($tallas as $talla){
				if($tiene_talla_ingresada[0]->talla==$talla->valor){
					$options_tallas.="<option value='".$talla->valor."' selected>".$talla->valor."</option>";
				}else{
					$options_tallas.="<option value='".$talla->valor."'>".$talla->valor."</option>";
				}
				
				
			}
			$row_talla             = str_replace("{OPTIONS}", ($options_tallas), $row_talla);
			
		}else{
			$row_talla .= file_get_contents("views/beneficios/78_row_talla.html");
		}
		
		
		if($prenda->bloque_comentario){
			$row_talla             = str_replace("{BLOQUE_COMENTARIO}", file_get_contents("views/beneficios/78_row_talla_comentario.html"), $row_talla);
			$row_talla             = str_replace("{PLACEHOLDER}", $prenda->bloque_comentario, $row_talla);

		}else{
			$row_talla             = str_replace("{BLOQUE_COMENTARIO}", "", $row_talla);
		}
		
		$combobo_tallas	=	"";
		
		$mi_tipo_talla	= Vestuario_tipo_Talla_rut($prenda->tipo_talla, $rut);
		$combobo_tallas.="<option value='".$mi_tipo_talla."'>".$mi_tipo_talla."</option>";

		if($mi_tipo_talla>0){
			$texto_mi_talla="<center><small>
							MI TALLA ES <span style='BACKGROUND-COLOR: #f1f1f1;
    padding-left: 5px;
    padding-right: 5px;
    border-radius: 5px;
    font-weight: 700;
    FONT-SIZE: 14px;'>".$mi_tipo_talla."</span>
							
							</small></center>";
		} else {
			$texto_mi_talla="<center style='    color: #999;    font-size: 12px;'>
												Aún no has ingresado tu Talla
											</center>";
		}

		$TipoTallas=Vestuario_tipo_Talla($prenda->tipo_talla);
		//print_r($TipoTallas);
		foreach ($TipoTallas as $unicoTalla){
			$combobo_tallas.="<option value='".$unicoTalla->valor."'>".$unicoTalla->valor."</option>";
			
		}
		$row_talla             = str_replace("{ID_PRENDA_FORM}", ($prenda->id_prenda), $row_talla);
		$row_talla             = str_replace("{ID_PRENDA_ENC}", Encodear3($prenda->id_prenda), $row_talla);
		$row_talla             = str_replace("{ID_TIPO_TALLA_ENC}", Encodear3($prenda->tipo_talla), $row_talla);
		$row_talla             = str_replace("{ID_PRENDA}", Encodear3($prenda->id_prenda), $row_talla);
		$row_talla             = str_replace("{TIPO_TALLA_MAS_TALLA_PERSONAL}", ($combobo_tallas), $row_talla);
		$row_talla             = str_replace("{MI_TALLA_ES}", ($texto_mi_talla), $row_talla);
		$row_talla             = str_replace("{IMAGEN_PRENDA}", ($prenda->imagen), $row_talla);
		$row_talla             = str_replace("{TALLA}", ($prenda->prenda), $row_talla);
		$row_talla             = str_replace("{DESCRIPCION}", ($prenda->descripcion), $row_talla);
		$row_talla             = str_replace("{ID_PREGUNTA}", ($prenda->id_prenda), $row_talla);
		$row_talla             = str_replace("{ENCUENTRA_TALLA}", utf8_encode($prenda->encuentra_talla), $row_talla);
		$row_talla             = str_replace("{VALUE}", ($tiene_talla_ingresada[0]->talla), $row_talla);
		$row_talla             = str_replace("{VALUE_COMENTARIO}", ($tiene_talla_ingresada[0]->comentario), $row_talla);
	}
    $PRINCIPAL             = str_replace("{ROW_TALLAS}", utf8_decode($row_talla), $PRINCIPAL);
	
return($PRINCIPAL);	
}
function ObtenerDatosGlobalesVestuario($PRINCIPAL, $rut, $id_empresa){
		$fecha_hoy					=	date("Y-m-d");
		$arreglo_fecha      = explode("-", $fecha_hoy);
		$es_maternal_si=0;
		$Array_TP						=	Vestuario_temporada_activa();

		$temporada					=	$Array_TP[0]->temporada_activa;
		$periodo						=	$Array_TP[0]->periodo_activo;
		
		
		//echo "rut $rut, $temporada, $periodo";
		
			$ListaPrendas	=	Vestuario_TieneDatosTallaPorUsuarioPorPrendaTemporada($rut, $temporada, $periodo);
			//print_r($ListaPrendas);
    	foreach ($ListaPrendas as $un5){
    		if($un5->maternal=="1"){$prenda_maternal="SI";} else {$prenda_maternal="";}
    	}

		//echo $row_pren;


		$Usua = DatosUsuario_($rut, $id_empresa);
		$genero=$Usua[0]->genero;
		$datos_segmento=VestuarioObtenerSegmento($genero);
		
		
		
		$segmento=$datos_segmento[0]->segmento;
		
		//echo "segmento $segmento";
		
		if($segmento=="MASCULINO BCH"){
			if($Usua[0]->id_cargo=="6085"){
				$segmento="VIGILANTES";
			}
			if($Usua[0]->id_cargo=="6005"){
				$segmento="MASCULINO AUX";
			}			
		}
		
		if($segmento=="FEMENINO BCH"){
			if($Usua[0]->id_area=="4407"){
				$segmento="FEMENINO EDW";
			}
		}
		//echo "<br>genero $genero temporada $temporada periodo $periodo segmento $segmento";
		$NombreTemporada=VestuarioBuscaTemporada($temporada,$periodo);
		$NombreSegmento=$segmento;
		$grupos_de_prendas=Vestuario_Trae_Grupos_prendas($segmento, $temporada, $periodo);
//print_r($grupos_de_prendas);

foreach ($grupos_de_prendas as $unico){
	
	//echo "<br><h3>".$unico->tipo_prenda."</h3>";
	$Prenda=Vestuario_Trae_prendas_dada_Grupo($segmento, $temporada, $periodo, $unico->tipo_prenda, $rut);
	//print_r($Prenda);
	
	$row_tipo_prendas .= file_get_contents("views/beneficios/78_row_tipo_prendas.html"); 

	$row_tipo_prendas             = str_replace("{NOMBRE_GRUPO_PRENDAS}", ($unico->tipo_prenda), $row_tipo_prendas);			
	$row_tipo_prendas             = str_replace("{ID_PRENDA_GRUPO}", ($unico->id), $row_tipo_prendas);			
	$row_tipo_prendas             = str_replace("{TOTAL}", ($unico->maximo_tipo), $row_tipo_prendas);			
	$row_prendas="";
	$sumaprenda=0;
	$suma_maximo=$suma_maximo+$unico->maximo_tipo;

		foreach ($Prenda as $unico2){
			
			$id_prenda=$unico2->id;
			$nombre=$unico2->prenda;
			$descripcion=$unico2->descripcion;
			$tipo_talla=$unico2->tipo_talla;
			$img1=$unico2->imagen;
			$img2=$unico2->imagen2;
			$img3=$unico2->imagen3;
			
			$es_maternal=$unico2->maternal;
		if($es_maternal>0){$es_maternal_si++;}
			
			
			
			$CuentaPrenda=Vestuario_TieneDatosTallaPorUsuarioPorPrenda($rut, $id_prenda);

			$sumaprenda=$CuentaPrenda+$sumaprenda;
			$suma_prenda_total=$CuentaPrenda+$suma_prenda_total;
	

			//echo "<br>id_prenda $id_prenda -> CuentaPrenda $CuentaPrenda ->sumaprenda $sumaprenda ";			
			//echo "<br>-->".$nombre."";
			
		$tiene_talla_ingresada=TieneDatosTallaPorUsuarioPorPrenda($rut, $temporada, $id_prenda);
							
							
						//	if($prenda->combobox_talla>0){ 
								$row_prendas .= file_get_contents("views/beneficios/78_row_prenda.html"); 
								$tallas=VestuarioTraigoTallas($tipo_talla);
								$options_tallas="<option value=''>-Seleccione-</option>";
								foreach($tallas as $talla){
									if($tiene_talla_ingresada[0]->talla==$talla->valor){
										$options_tallas.="<option value='".$talla->valor."' selected>".$talla->valor."</option>";
									}else{
										$options_tallas.="<option value='".$talla->valor."'>".$talla->valor."</option>";
									}
									
									
								}
								$row_prendas             = str_replace("{OPTIONS}", ($options_tallas), $row_prendas);



$maternal_checked="  ";
								
if($segmento=="FEMENINO BCH"){
if($es_maternal_si>0){
		$maternal_checked=" checked ";
	} else {
		
	}
	
	$row_checkbox="<br><input type='radio' name='maternal' id='maternal' ".$maternal_checked." value='1' style='    margin-right: 7px;' > Es una prenda maternal";
} else {
	$row_checkbox="";
}
							
									$row_prendas             = str_replace("{ROW_CHECKBOX_MATERNAL}", ($row_checkbox), $row_prendas);
					
								if($unico2->bloque_comentario){
									$row_prendas             = str_replace("{BLOQUE_COMENTARIO}", file_get_contents("views/beneficios/78_row_prendas_comentario.html"), $row_prendas);
									$row_prendas             = str_replace("{PLACEHOLDER}", $prenda->bloque_comentario, $row_prendas);

								}else{
									$row_prendas             = str_replace("{BLOQUE_COMENTARIO}", "", $row_prendas);
								}
							
								$combobo_tallas	=	"";
							
								$mi_tipo_talla	= Vestuario_tipo_Talla_rut($tipo_talla, $rut);
								$combobo_tallas.="<option value='".$mi_tipo_talla."'>".$mi_tipo_talla."</option>";

								if($mi_tipo_talla>0){
									$texto_mi_talla="
									<center><small>
											Mi talla es: <span style='BACKGROUND-COLOR: #f1f1f1;padding-left: 5px; padding-right: 5px;  border-radius: 5px;  font-weight: 700;  FONT-SIZE: 14px;'>".$mi_tipo_talla."</span>
												</small></center>";
								} else {
									$texto_mi_talla="<center style='    color: #999;    font-size: 12px;'>
																		Aún no has ingresado tu talla
																	</center>";
								}

								$TipoTallas=Vestuario_tipo_Talla($tipo_talla);
								//print_r($TipoTallas);
								foreach ($TipoTallas as $unicoTalla){
									$combobo_tallas.="<option value='".$unicoTalla->valor."'>".$unicoTalla->valor."</option>";
									
								}

							$row_prendas             = str_replace("{ID_PRENDA_FORM}", ($id_prenda), $row_prendas);					
							$row_prendas             = str_replace("{ID_PRENDA_ENC}", Encodear3($id_prenda), $row_prendas);
							$row_prendas             = str_replace("{ID_TIPO_TALLA_ENC}", Encodear3($tipo_talla), $row_prendas);
							$row_prendas             = str_replace("{ID_PRENDA}", Encodear3($id_prenda), $row_prendas);
							$row_prendas             = str_replace("{TIPO_TALLA_MAS_TALLA_PERSONAL}", ($combobo_tallas), $row_prendas);
							$row_prendas             = str_replace("{MI_TALLA_ES}", ($texto_mi_talla), $row_prendas);
							$row_prendas             = str_replace("{IMAGEN_PRENDA_1}", ($img1), $row_prendas);
							$row_prendas             = str_replace("{IMAGEN_PRENDA_2}", ($img2), $row_prendas);
							$row_prendas             = str_replace("{IMAGEN_PRENDA_3}", ($img3), $row_prendas);
							$row_prendas             = str_replace("{NOMBRE_PRENDA}", ($nombre), $row_prendas);
							$row_prendas             = str_replace("{DESCRIPCION_PRENDA}", ($descripcion), $row_prendas);
							$row_prendas             = str_replace("{ENCUENTRA_TALLA}", utf8_encode($unico2->encuentra_talla), $row_prendas);
							$row_prendas             = str_replace("{VALUE}", ($tiene_talla_ingresada[0]->talla), $row_prendas);
							$row_prendas             = str_replace("{VALUE_COMENTARIO}", ($tiene_talla_ingresada[0]->comentario), $row_prendas);			

//echo "<br>Row ".$row_prendas;

		}
	
	$pendiente_listo="";
	$DISPLAY_NONE_LISTO="";
if($sumaprenda>0 and $sumaprenda>=$unico->maximo_tipo){
	$pendiente_listo=" Listo ";
	$DISPLAY_PRENDA= " display:none; ";
} else {
	$DISPLAY_NONE_LISTO=" display:none; ";
	$DISPLAY_PRENDA= "  ";
}

	$row_prendas             = str_replace("{DISPLAY_PRENDA}", ($DISPLAY_PRENDA), $row_prendas);			


	$row_tipo_prendas             = str_replace("{ELEGIDAS_RUT}", ($sumaprenda), $row_tipo_prendas);			

	$row_tipo_prendas             = str_replace("{ROW_LISTA_PRENDAS_POR_TIPO}", ($row_prendas), $row_tipo_prendas);			

	$row_tipo_prendas             = str_replace("{PENDIENTE_LISTO}", ($pendiente_listo), $row_tipo_prendas);			
	$row_tipo_prendas             = str_replace("{DISPLAY_NONE_LISTO}", ($DISPLAY_NONE_LISTO), $row_tipo_prendas);			

	
}



if($suma_prenda_total==0){
	$texto_prendas_faltantes="";
	$VerBonoValidar=" display:none; ";
}
elseif($suma_prenda_total>0 and $suma_prenda_total<$suma_maximo){
	$texto_prendas_faltantes="<div class='alert alert-primary'>Llevas seleccionadas ".$suma_prenda_total." prendas de un total de ".$suma_maximo."</div>";
	$VerBonoValidar=" display:none; ";
}

elseif($suma_prenda_total>0 and $suma_prenda_total>=$suma_maximo){
	$texto_prendas_faltantes="
		<center>
			<div class='alert alert-success'><strong>¡Muy Bien!</strong> Terminaste de seleccionar todas tus prendas.</div>
		</center>";
	$VerBonoValidar="";
}


    //echo "<br> suma_prenda_total $suma_prenda_total  suma_maximo $suma_maximo";

    $PRINCIPAL = str_replace("{TEXTO_PRENDA_FALTANTE}", 	$texto_prendas_faltantes, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VER_BOTON_STYLE}", 				$VerBonoValidar, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TEMPORADA_NOMBRE}", 				$NombreTemporada, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SEGMENTO_NOMBRE}", 				$NombreSegmento, $PRINCIPAL);


    $PRINCIPAL = str_replace("{VISTA_PRENDAS_AGRUPADAS_POR_TIPO_VESTUARIO}", 	$row_tipo_prendas, $PRINCIPAL);

//print_r($grupos_de_prendas);

$arreglo[0]=$PRINCIPAL;
$arreglo[1]=$VerBonoValidar;

return $arreglo;
	
}
function Beneficio_Tipo_Field($type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $op9, $op10, $op11, $op12){

$rut = $_SESSION["user_"];

	if($required=="Si"){
		$required_field=" required ";
	} else {
		$required_field=" ";
	}

		$data_value="";

	if($type_field=="checkbox"){
		$input_form="	<input type='checkbox' id='".$name_field."' name='".$name_field."' value='SI' ".$required_field."> ".$op8."	";
	}

	if($type_field=="combobox"){
		if($op1<>"")	{$option.="<option value='".$op1."'>".$op1."</option>";}
		if($op2<>"")	{$option.="<option value='".$op2."'>".$op2."</option>";}
		if($op3<>"")	{$option.="<option value='".$op3."'>".$op3."</option>";}
		if($op4<>"")	{$option.="<option value='".$op4."'>".$op4."</option>";}
		if($op5<>"")	{$option.="<option value='".$op5."'>".$op5."</option>";}
		if($op6<>"")	{$option.="<option value='".$op6."'>".$op6."</option>";}
		if($op7<>"")	{$option.="<option value='".$op7."'>".$op7."</option>";}
		if($op8<>"")	{$option.="<option value='".$op8."'>".$op8."</option>";}
		if($op9<>"")	{$option.="<option value='".$op9."'>".$op9."</option>";}
		if($op10<>"")	{$option.="<option value='".$op10."'>".$op10."</option>";}
		if($op11<>"")	{$option.="<option value='".$op11."'>".$op11."</option>";}
		if($op12<>"")	{$option.="<option value='".$op12."'>".$op12."</option>";}
		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'><option value=''></option>".$option."</select>";
	}


	if($type_field=="combobox_onchange"){
		if($op1<>"")	{$option.="<option value='".$op1."'>".$op1."</option>";}
		if($op2<>"")	{$option.="<option value='".$op2."'>".$op2."</option>";}
		if($op3<>"")	{$option.="<option value='".$op3."'>".$op3."</option>";}
		if($op4<>"")	{$option.="<option value='".$op4."'>".$op4."</option>";}
		if($op5<>"")	{$option.="<option value='".$op5."'>".$op5."</option>";}
		if($op6<>"")	{$option.="<option value='".$op6."'>".$op6."</option>";}
		if($op7<>"")	{$option.="<option value='".$op7."'>".$op7."</option>";}
		if($op8<>"")	{$option.="<option value='".$op8."'>".$op8."</option>";}
		if($op9<>"")	{$option.="<option value='".$op9."'>".$op9."</option>";}
		if($op10<>"")	{$option.="<option value='".$op10."'>".$op10."</option>";}
		if($op11<>"")	{$option.="<option value='".$op11."'>".$op11."</option>";}
		if($op12<>"")	{$option.="<option value='".$op12."'>".$op12."</option>";}
		$input_form="	<select name='".$name_field."' onchange='colocadatosyomismo(this.value)' ".$required_field." class='form form-control'><option value=''></option>".$option."</select>";
	}


	if($type_field=="select_cargas"){


 $rut = $_SESSION["user_"];
 $array_cargas=Beneficio_BuscaCargas($rut);
 //print_r($array_cargas);


foreach($array_cargas as $unico){
	
	$option.="<option value='".$unico->carga_rut."'>".$unico->carga_nombre."</option>";
	
}
	
		

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value=''></option>".$option."</select>";
	}


	if($type_field=="date"){
		$input_form="	<input type='date' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	if($type_field=="email"){
		
			//echo "<br>Tipe $type_field, $name_field, $required, $id, rut $rut<br>";
			if($id==6){
				$Datos_Personales_Array_value=Beneficios_CheckDatosPersonas($rut);
				$data_value=$Datos_Personales_Array_value[0]->email_particular;
			}		
			
		$input_form="	<input type='email' id='".$name_field."'  name='".$name_field."' ".$required_field."  value='".$data_value."' class='form form-control'>";
	}

	if($type_field=="info"){
		$input_form="	<input type='text' id='".$name_field."'  name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	if($type_field=="number"){
		$input_form="	<input type='number' id='".$name_field."'  name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	if($type_field=="text"){
					
			$Datos_Personales_Array_value=Beneficios_CheckDatosPersonas($rut);

			if($id==5){
				$data_value=$Datos_Personales_Array_value[0]->celular;
			}			

			if($id==226){
				$data_value=$Datos_Personales_Array_value[0]->ciudad;
			}		

			if($id==209){
				$data_value=$Datos_Personales_Array_value[0]->region;
			}		

			if($id==208){
				$data_value=$Datos_Personales_Array_value[0]->comuna;
			}		

			if($id==207){
				$data_value=$Datos_Personales_Array_value[0]->direccion;
			}		


		
		$input_form="	<input type='text' id='".$name_field."' name='".$name_field."' ".$required_field." value='".$data_value."' class='form form-control'>";
	}

	if($type_field=="textarea"){
		

		
		$input_form="	<textarea  name='".$name_field."' ".$required_field." class='form form-control'></textarea>";
	}

	if($type_field=="uploadfile"){
		$input_form="	<input type='file' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	return $input_form;

}

function Beneficio_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $op9, $op10, $op11, $op12){

			//echo "<br>-- Beneficio_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8";
	$rut = $_SESSION["user_"];
			
				$value_data=Beneficio_Tipo_Field_WithValue_data($idl,$name_field);
				
		//echo "<br>Data $value_data";		
	if($required=="Si"){
		$required_field=" required ";
	} else {
		$required_field=" ";
	}

	if($type_field=="checkbox"){
		$input_form="	<input type='checkbox' id='".$name_field."' name='".$name_field."' value='SI' ".$required_field."> ".$op8."	";
	}

	if($type_field=="combobox"){
			
			$selected="";
	if($op1==$value_data)		{ $selected1=" selected ";}
	if($op2==$value_data)		{ $selected2=" selected ";}
	if($op3==$value_data)		{ $selected3=" selected ";}
	if($op4==$value_data)		{ $selected4=" selected ";}
	if($op5==$value_data)		{ $selected5=" selected ";}
	if($op6==$value_data)		{ $selected6=" selected ";}
	if($op7==$value_data)		{ $selected7=" selected ";}
	if($op8==$value_data)		{ $selected8=" selected ";}
	if($op9==$value_data)		{ $selected9=" selected ";}
	if($op10==$value_data)	{ $selected10=" selected ";}
	if($op11==$value_data)	{ $selected11=" selected ";}
	if($op2==$value_data)		{ $selected12=" selected ";}

		if($op1<>""){$option.="<option value='".$op1."' ".$selected1.">".$op1."</option>";}
		if($op2<>""){$option.="<option value='".$op2."' ".$selected2.">".$op2."</option>";}
		if($op3<>""){$option.="<option value='".$op3."' ".$selected3.">".$op3."</option>";}
		if($op4<>""){$option.="<option value='".$op4."' ".$selected4.">".$op4."</option>";}
		if($op5<>""){$option.="<option value='".$op5."' ".$selected5.">".$op5."</option>";}
		if($op6<>""){$option.="<option value='".$op6."' ".$selected6.">".$op6."</option>";}
		if($op7<>""){$option.="<option value='".$op7."' ".$selected7.">".$op7."</option>";}
		if($op8<>""){$option.="<option value='".$op8."' ".$selected8.">".$op8."</option>";}
		if($op9<>""){$option.="<option value='".$op9."' ".$selected9.">".$op9."</option>";}
		if($op10<>""){$option.="<option value='".$op10."' ".$selected10.">".$op10."</option>";}
		if($op11<>""){$option.="<option value='".$op11."' ".$selected11.">".$op11."</option>";}
		if($op12<>""){$option.="<option value='".$op12."' ".$selected12.">".$op12."</option>";}
		

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value=''></option>".$option."</select>";
	}




	if($type_field=="select_cargas"){


 $rut = $_SESSION["user_"];
 $array_cargas=Beneficio_BuscaCargas($rut);
 //print_r($array_cargas);


foreach($array_cargas as $unico){
	
	$option.="<option value='".$unico->carga_rut."'>".$unico->carga_nombre."</option>";
	
}
	
		

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value=''></option>".$option."</select>";
	}

	if($type_field=="combobox_onchange"){
		if($op1<>"")	{$option.="<option value='".$op1."'>".$op1."</option>";}
		if($op2<>"")	{$option.="<option value='".$op2."'>".$op2."</option>";}
		if($op3<>"")	{$option.="<option value='".$op3."'>".$op3."</option>";}
		if($op4<>"")	{$option.="<option value='".$op4."'>".$op4."</option>";}
		if($op5<>"")	{$option.="<option value='".$op5."'>".$op5."</option>";}
		if($op6<>"")	{$option.="<option value='".$op6."'>".$op6."</option>";}
		if($op7<>"")	{$option.="<option value='".$op7."'>".$op7."</option>";}
		if($op8<>"")	{$option.="<option value='".$op8."'>".$op8."</option>";}
		if($op9<>"")	{$option.="<option value='".$op9."'>".$op9."</option>";}
		if($op10<>"")	{$option.="<option value='".$op10."'>".$op10."</option>";}
		if($op11<>"")	{$option.="<option value='".$op11."'>".$op11."</option>";}
		if($op12<>"")	{$option.="<option value='".$op12."'>".$op12."</option>";}
		$input_form="	<select name='".$name_field."' onchange='colocadatosyomismo(this.value)' ".$required_field." class='form form-control'><option value=''></option>".$option."</select>";
	}

	if($type_field=="date"){
		$input_form="	<input type='date' name='".$name_field."' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="email"){
		
			if($id==6){
				$Datos_Personales_Array_value=Beneficios_CheckDatosPersonas($rut);
				$value_data=$Datos_Personales_Array_value[0]->email_particular;
			}		
		
		$input_form="	<input type='email' name='".$name_field."' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="info"){
		$input_form="	<input type='text' name='".$name_field."' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="number"){
		$input_form="	<input type='number'  id='".$name_field."' name='".$name_field."' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="text"){
		
			$Datos_Personales_Array_value=Beneficios_CheckDatosPersonas($rut);

			if($id==5){
				$value_data=$Datos_Personales_Array_value[0]->celular;
			}			

			if($id==226){
				$value_data=$Datos_Personales_Array_value[0]->ciudad;
			}		

			if($id==209){
				$value_data=$Datos_Personales_Array_value[0]->region;
			}		

			if($id==208){
				$value_data=$Datos_Personales_Array_value[0]->comuna;
			}		

			if($id==207){
				$value_data=$Datos_Personales_Array_value[0]->direccion;
			}		
		
		$input_form="	<input type='text'  id='".$name_field."' name='".$name_field."' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="textarea"){
		$input_form="	<textarea  name='".$name_field."' ".$required_field." class='form form-control'>".$value_data."</textarea>";
	}

	if($type_field=="uploadfile"){
		$input_form="	<input type='file' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	return $input_form;

}

function Beneficio_Tipo_Field_WithValueUser_Ficha($type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $id_evento){

	//echo "<br>-- Beneficio_Tipo_Field tbl_beneficios_agenda_fichas_respuestas ($type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8";
	//echo "<br>IdEvento ".$id_evento;

	$Respuesta= Beneficio_Form_Respuestas_Ficha($name_field, $id_evento);
	//echo "<br>Respuesta ".$Respuesta;



	if($required=="Si"){
		$required_field=" required ";
	} else {
		$required_field=" ";
	}

	if($type_field=="checkbox"){

		$input_form="	<input type='checkbox' id='".$name_field."' name='".$name_field."' value='SI' ".$required_field.">  ".$op12." ";
	}

	if($type_field=="combobox"){



		if($op1==$Respuesta){$op1_check=" selected ";}
		if($op2==$Respuesta){$op2_check=" selected ";}
		if($op3==$Respuesta){$op3_check=" selected ";}
		if($op4==$Respuesta){$op4_check=" selected ";}
		if($op5==$Respuesta){$op5_check=" selected ";}
		if($op6==$Respuesta){$op6_check=" selected ";}
		if($op7==$Respuesta){$op7_check=" selected ";}
		if($op8==$Respuesta){$op8_check=" selected ";}


		if($op1<>""){$option.="<option value='".$op1."' ".$op1_check." >".$op1."</option>";}
		if($op2<>""){$option.="<option value='".$op2."' ".$op2_check." >".$op2."</option>";}
		if($op3<>""){$option.="<option value='".$op3."' ".$op3_check." >".$op3."</option>";}
		if($op4<>""){$option.="<option value='".$op4."' ".$op4_check." >".$op4."</option>";}
		if($op5<>""){$option.="<option value='".$op5."' ".$op5_check." >".$op5."</option>";}
		if($op6<>""){$option.="<option value='".$op6."' ".$op6_check." >".$op6."</option>";}
		if($op7<>""){$option.="<option value='".$op7."' ".$op7_check." >".$op7."</option>";}
		if($op8<>""){$option.="<option value='".$op8."' ".$op8_check." >".$op8."</option>";}

		//echo "<br>op 1 ".$op1." ".$op1_check." $op1==$Respuesta ";

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value=''></option>".$option."</select>";
	}

	if($type_field=="date"){
		$input_form="	<input type='date' name='".$name_field."' ".$required_field." value='".$Respuesta."' class='form form-control'>";
	}

	if($type_field=="email"){
		$input_form="	<input type='email' name='".$name_field."' ".$required_field." value='".$Respuesta."' class='form form-control'>";
	}

	if($type_field=="info"){
		$input_form="	<input type='text' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	if($type_field=="number"){
		$input_form="	<input type='number' name='".$name_field."' ".$required_field." value='".$Respuesta."' class='form form-control'>";
	}

	if($type_field=="text"){
		$input_form="	<input type='text' name='".$name_field."' ".$required_field."  value='".$Respuesta."'class='form form-control'>";
	}

	if($type_field=="textarea"){
		$input_form="	<textarea  name='".$name_field."' ".$required_field." class='form form-control'>".$Respuesta."</textarea>";
	}

	if($type_field=="uploadfile"){
		$input_form="	<input type='file' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

//echo "<br>input_form ".$input_form."<br><br>";

	return $input_form;

}

function Chileactivo_lista_mis_encuestas($PRINCIPAL, $rut, $id_empresa){

 $array_mis_encuestas=Chileactivo_mis_encuestas_data($id_empresa);
	//print_r($array_mis_encuestas);

 foreach ($array_mis_encuestas as $unico){
 	//print_r($unico);
	$row.=file_get_contents("views/mini_encuestas/".$id_empresa."_row_encuestas.html");
	$boton_ver_resultados="<center><a href='?sw=encuesta_2020&id_encuesta=".Encodear3($unico->id)."' class='btn btn-info'>Ir a Encuesta</a></center>";

	$row = str_replace("{NOMBRE}", utf8_encode($unico->nombre),$row);
	$row = str_replace("{BOTON_VER_RESULTADOS}", $boton_ver_resultados,$row);

 }
    $PRINCIPAL = str_replace("{LISTA_MIS_ENCUESTAS}",$row,$PRINCIPAL);

    return($PRINCIPAL);
}


function ColocaEventosCalendario($PRINCIPAL, $id_empresa, $id_agenda, $id_especialista, $filtro_cui, $filtro_rut, $vista_esp)
{
		//echo "<br>ColocaEventosCalendario $id_empresa, $id_agenda, $id_especialista, $filtro_cui, $filtro_rut, $filtro_tipo, $vista_esp<br>";
    $eventos          = Agenda_Eventos_2020_id_agenda_especialista($id_empresa, $id_agenda, $id_especialista, $filtro_cui, $filtro_rut, $filtro_tipo);
    //print_r($eventos);
    
    
    $contador_eventos = 1;
    $fecha_actual     = date("Y-m-d");
    foreach ($eventos as $eve) {

			//echo "<br>ocupada ".$eve->ocupada;
			$rut_ocupada=$eve->ocupada;
			$usu	=	DatosUsuario_($rut_ocupada, $id_empresa);
        if ($eve->fecha_inicio < $eve->fecha_termino) {
        		//echo "<h1>BBB</h1>";
            $html_evento .= file_get_contents("views/calendario/eventos/" . $id_empresa . "_row_basic-views_rangos.html");
        } else if ($eve->fecha_inicio == $eve->fecha_termino) {
            //echo "<h1>AAA</h1>";
            $html_evento .= file_get_contents("views/calendario/eventos/" . $id_empresa . "_row_basic-views.html");
        }

        $html_evento = str_replace("{FECHA_INICIO}", 	$eve->fecha_inicio, $html_evento);
        $html_evento = str_replace("{FECHA_TERMINO}", $eve->fecha_termino, $html_evento);

        if($eve->ocupada <> "" and $vista_esp=="1"){
        	if($eve->fecha_ficha <> "") {
        	 $html_evento = str_replace("{COLOR_EVENTO}", "#8BB851", $html_evento);
        	 $html_evento = str_replace("{URL_EXTERNA}", "url: '?sw=calendario_event&idr=" . $eve->id_evento . "&vista_esp=1',", $html_evento);
        	} else {
        	 $html_evento = str_replace("{COLOR_EVENTO}", "#F36016", $html_evento);
        	 $html_evento = str_replace("{URL_EXTERNA}", "url: '?sw=calendario_event&idr=" . $eve->id_evento . "&vista_esp=1',", $html_evento);
        	}
        }

        if($eve->ocupada <> "" and $vista_esp==""){
        	 $html_evento = str_replace("{COLOR_EVENTO}", "#dc3545", $html_evento);
        	 $html_evento = str_replace("{URL_EXTERNA}", "url: '',", $html_evento);
        }

        if ($eve->target == "1"  and $fecha_actual<=$eve->fecha_inicio) {
        		//echo "<br><h1>A</h1>fecha inicio ".$eve->nombre." ";
            $html_evento = str_replace("{URL_EXTERNA}", "url: '" . $eve->link_acceso . "',", $html_evento);
            $html_evento = str_replace("{COLOR_EVENTO}", "#3788D8", $html_evento);
        }

        if ($eve->target == "1" and $fecha_actual>$eve->fecha_inicio) {
        	//echo "<br><h1>B</h1>fecha inicio ".$eve->nombre." ";
        		$html_evento = str_replace("{URL_EXTERNA}", "url: '',", $html_evento);
        		$html_evento = str_replace("{COLOR_EVENTO}", "#ccc", $html_evento);
        	 	$html_evento = str_replace("{NOMBRE}", $eve->nombre." - ", $html_evento);
        }

				if ($eve->target == "2"  and $fecha_actual<$eve->fecha_inicio) {
						//echo "<br><h1>C</h1>fecha inicio ".$eve->nombre." ";
						$html_evento = str_replace("{URL_EXTERNA}", "url: '',", $html_evento);
						$html_evento = str_replace("{COLOR_EVENTO}", "", $html_evento);
				}
 
        if($vista_esp==1){
        		$html_evento = str_replace("{IDEVENTO}", $eve->id_evento."&vista_esp=1", $html_evento);
     				$html_evento = str_replace("{NOMBRE}",   $eve->nombre." ".$eve->nombre_completo."", $html_evento);
        } else {
        	$eve->descripcion="";
        		$html_evento = str_replace("{IDEVENTO}", $eve->id_evento, $html_evento);
     				$html_evento = str_replace("{NOMBRE}",   $eve->nombre." ".$eve->descripcion."".$usu[0]->nombre_completo." ".$eve->ocupado."", $html_evento);

        }

        if ($contador_eventos < count($eventos)) {
            $html_evento .= ", ";
        }

        $contador_eventos++;
        $total_eventos .= $html_evento;
    }
    $PRINCIPAL = str_replace("{ROW_EVENTOS}", utf8_encode($html_evento), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_ACTUAL}", utf8_encode($fecha_actual), $PRINCIPAL);
    return ($PRINCIPAL);
}

?>
<?php
function Ona_Jefatura_fn($PRINCIPAL, $rut){
    $Ona_datos=ONA_Jefatura_data($rut);
    //print_r($Ona_datos);
        foreach ($Ona_datos as $u){
            $cuenta_linea++;
            if($cuenta_linea==1){
                $nivel_influencia=$u[0]->nivel_influencia;
                $total_nominaciones=$u[0]->total_nominaciones;
            }
            if($cuenta_linea==2){
                $comunicacion_int=$u[0]->comunicacion;
                $asesoramiento_int=$u[0]->asesoramiento;
                $confianza_int=$u[0]->confianza;
                $politica_int=$u[0]->politica;
                $nominaciones_nivel_int=$u[0]->nominaciones_nivel;
            }
            if($cuenta_linea==3){
                $comunicacion_ext=$u[0]->comunicacion;
                $asesoramiento_ext=$u[0]->asesoramiento;
                $confianza_ext=$u[0]->confianza;
                $politica_ext=$u[0]->politica;
                $nominaciones_nivel_ext=$u[0]->nominaciones_nivel;
            }
        }
    $arreglo["nivel_influencia"]=$nivel_influencia;
    $arreglo["total_nominaciones"]=$total_nominaciones;
    $arreglo["comunicacion_ext"]=$comunicacion_ext;
    $arreglo["asesoramiento_ext"]=$asesoramiento_ext;
    $arreglo["confianza_ext"]=$confianza_ext;
    $arreglo["politica_ext"]=$politica_ext;
    $arreglo["nominaciones_nivel_ext"]=$nominaciones_nivel_ext;
    $arreglo["comunicacion_int"]=$comunicacion_int;
    $arreglo["asesoramiento_int"]=$asesoramiento_int;
    $arreglo["confianza_int"]=$confianza_int;
    $arreglo["politica_int"]=$politica_int;
    $arreglo["nominaciones_nivel_int"]=$nominaciones_nivel_int;
    $PRINCIPAL = str_replace("{nivel_influencia_j}", 	$arreglo["nivel_influencia"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{total_nominaciones_j}", 	$arreglo["total_nominaciones"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{comunicacion_ext_j}", 	$arreglo["comunicacion_ext"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{asesoramiento_ext_j}", 	$arreglo["asesoramiento_ext"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{confianza_ext_j}", 	$arreglo["confianza_ext"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{politica_ext_j}", 	$arreglo["politica_ext"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{nominaciones_nivel_ext_j}", 	$arreglo["nominaciones_nivel_ext"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{comunicacion_int_j}", 	$arreglo["comunicacion_int"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{asesoramiento_int_j}", 	$arreglo["asesoramiento_int"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{confianza_int_j}", 	$arreglo["confianza_int"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{politica_int_j}", 	$arreglo["politica_int"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{nominaciones_nivel_int_j}", 	$arreglo["nominaciones_nivel_int"] , $PRINCIPAL);
    $Ona_datos=ONA_Unidad_data($rut);
    //print_r($Ona_datos);
    $cuenta_linea=0;
    foreach ($Ona_datos as $u){
        $cuenta_linea++;
        if($cuenta_linea==1){
            $nivel_influencia_u=$u[0]->nivel_influencia;
            $total_nominaciones_u=$u[0]->total_nominaciones;
        }
        if($cuenta_linea==2){
            $comunicacion_int_u=$u[0]->comunicacion;
            $asesoramiento_int_u=$u[0]->asesoramiento;
            $confianza_int_u=$u[0]->confianza;
            $politica_int_u=$u[0]->politica;
            $nominaciones_nivel_int_u=$u[0]->nominaciones_nivel;
        }
        if($cuenta_linea==3){
            $comunicacion_ext_u=$u[0]->comunicacion;
            $asesoramiento_ext_u=$u[0]->asesoramiento;
            $confianza_ext_u=$u[0]->confianza;
            $politica_ext_u=$u[0]->politica;
            $nominaciones_nivel_ext_u=$u[0]->nominaciones_nivel;
        }
    }
    $arreglo["nivel_influencia_u"]=$nivel_influencia_u;
    $arreglo["total_nominaciones_u"]=$total_nominaciones_u;
    $arreglo["comunicacion_ext_u"]=$comunicacion_ext_u;
    $arreglo["asesoramiento_ext_u"]=$asesoramiento_ext_u;
    $arreglo["confianza_ext_u"]=$confianza_ext_u;
    $arreglo["politica_ext_u"]=$politica_ext_u;
    $arreglo["nominaciones_nivel_ext_u"]=$nominaciones_nivel_ext_u;
    $arreglo["comunicacion_int_u"]=$comunicacion_int_u;
    $arreglo["asesoramiento_int_u"]=$asesoramiento_int_u;
    $arreglo["confianza_int_u"]=$confianza_int_u;
    $arreglo["politica_int_u"]=$politica_int_u;
    $arreglo["nominaciones_nivel_int_u"]=$nominaciones_nivel_int_u;
    $PRINCIPAL = str_replace("{nivel_influencia_u}", 	$arreglo["nivel_influencia_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{total_nominaciones_u}", 	$arreglo["total_nominaciones_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{comunicacion_ext_u}", 	$arreglo["comunicacion_ext_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{asesoramiento_ext_u}", 	$arreglo["asesoramiento_ext_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{confianza_ext_u}", 	$arreglo["confianza_ext_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{politica_ext_u}", 	$arreglo["politica_ext_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{nominaciones_nivel_ext_u}", 	$arreglo["nominaciones_nivel_ext_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{comunicacion_int_u}", 	$arreglo["comunicacion_int_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{asesoramiento_int_u}", 	$arreglo["asesoramiento_int_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{confianza_int_u}", 	$arreglo["confianza_int_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{politica_int_u}", 	$arreglo["politica_int_u"] , $PRINCIPAL);
    $PRINCIPAL = str_replace("{nominaciones_nivel_int_u}", 	$arreglo["nominaciones_nivel_int_u"] , $PRINCIPAL);

    $Ona_datos=ONA_Reportes_data($rut);
    //echo "<pre>";    print_r($Ona_datos);    echo "</pre>";
    $cuenta_linea=0;
    $row_reportes="";
    foreach ($Ona_datos as $u){

        $cuenta_linea++;

            $nivel_influencia=$u->nivel_influencia;

            $comunicacion_int=$u->comunicacion;
            $asesoramiento_int=$u->asesoramiento;
            $confianza_int=$u->confianza;
            $politica_int=$u->politica;
            $nominaciones_nivel_int=$u->nivel_esperado;

            $comunicacion_ext=$u->comunicacion_ext;
            $asesoramiento_ext=$u->asesoramiento_ext;
            $confianza_ext=$u->confianza_ext;
            $politica_ext=$u->politica_ext;

        $row_reportes.="            <TR>
                <td>".$u->nombre."</td>
                <td>".$u->cargo."</td>
                <TD>".$comunicacion_int."</TD>
                <TD>".$asesoramiento_int."</TD>
                <td>".$confianza_int."</td>
                <td>".$politica_int."</td>
                <td class='td_oculta_bottom'></td>
                 <td class='td_background_reportes_general'><b>".$u->total_nominaciones."</b></td>
                 <td>".$u->nominaciones_nivel."</td>
            </TR>";

    }

    $PRINCIPAL = str_replace("{ROW_COLABORADORES_REPORTES}", 	$row_reportes , $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}

function OrgChart_Num_Equipos_iconos($rut,$AllIcons){

    //print_r($_GET);
    $perfil_orgchart=Potencial_OrgChart_BuscaPerfil($_SESSION["user_"]);
    $cuenta_equipo=OrgChart_Cuenta_MiEquipo($rut);
    if($cuenta_equipo>0){
        $num_equipo="
                    <center>
                    <span style='color:#002464'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-plus-fill' viewBox='0 0 16 16'>
                      <path d='M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'/>
                      <path fill-rule='evenodd' d='M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z'/>
                    </svg> (".$cuenta_equipo.") reportes
                    </span>
                    </center>
                    ";

        $row_ficha_num_equipo="
        <div class='col-lg-6'>
        <center>
            <a href='?sw=home_talento_colaborador_ficha&rut_enc=".Encodear4($rut)."' target='_blank'>
             <div class='badge badge-info'>
                    <svg class='bi bi-person-badge' fill='currentColor' height='16' viewBox='0 0 16 16'
                         width='16' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'/>
                        <path d='M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z'/>
                    </svg>
                    Ficha
                </div>
            </a>
            </center>
        </div>
              <div class='col-lg-6'>
          <center>
         <a href='?sw=home_org_chart_colaborador&rut_enc=".Encodear4($rut)."'>
             <div class='badge badge-info'>
                <svg class='bi bi-people-fill' fill='currentColor' height='16' viewBox='0 0 16 16' width='16'
                     xmlns='http://www.w3.org/2000/svg'>
                    <path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'/>
                    <path d='M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z'
                          fill-rule='evenodd'/>
                    <path d='M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'/> 
                </svg> (".$cuenta_equipo.")
                 reportes 
                 </div>
            </a>
            </center>
        </div>   ";

        if($_GET["print"]=="1"  or $perfil_orgchart=="Visualizador_basico"){
            $row_ficha_num_equipo=" 
 <div class='col-lg-6'>

        </div>
              <div class='col-lg-6'>
          <center>
         <a href='?sw=home_org_chart_colaborador&rut_enc=".Encodear4($rut)."'>
             <div class='badge badge-info'>
                <svg class='bi bi-people-fill' fill='currentColor' height='16' viewBox='0 0 16 16' width='16'
                     xmlns='http://www.w3.org/2000/svg'>
                    <path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'/>
                    <path d='M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z'
                          fill-rule='evenodd'/>
                    <path d='M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'/> 
                </svg> (".$cuenta_equipo.")
                 reportes 
                 </div>
            </a>
            </center>
        </div>   ";
        }
        
    } else {
        $num_equipo="";
        $row_ficha_num_equipo="
                        <div class='col-lg-6'>
                         <center>
                            <a href='?sw=home_talento_colaborador_ficha&rut_enc=".Encodear4($rut)."' target='_blank'>
                             <div class='badge badge-info'>
                                    <svg class='bi bi-person-badge' fill='currentColor' height='16' viewBox='0 0 16 16'
                                         width='16' xmlns='http://www.w3.org/2000/svg'>
                                        <path d='M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'/>
                                        <path d='M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z'/>
                                    </svg>
                                    Ficha
                                </div>
                            </a>
                            </center>
                        </div>
                            <div class='col-lg-6'></div>";
        if($_GET["print"]=="1" or $perfil_orgchart=="Visualizador_basico"){
            $row_ficha_num_equipo=" <div class='col-lg-6'></div> <div class='col-lg-6'></div> ";
        }



    }

    $Iconos_orgChart=OrgChartIconosPorRut($rut);
    if($rut=="8027636"){
        //echo "<br>rut ".$rut."<br>"; print_r($Iconos_orgChart);        echo "<br>";        print_r($AllIcons);
    }


    $row_iconos_org_chart_2022="";
    if($Iconos_orgChart[1]=="1"){ $row_iconos_org_chart_2022.="<p style='display: inline;' title='Identifica a la persona como sucesor natural de otra posicion. La posicion a suceder, se encuentra identificada.'> ".$AllIcons[0]->icon."</p>";}
    if($Iconos_orgChart[2]=="1"){ $row_iconos_org_chart_2022.="<p  style='display: inline;' title='Refleja que la persona esta identificada con potencial para asumir otra posicion. La posicion a suceder puede estar identificada'> ".$AllIcons[1]->icon."</p>";}
    if($Iconos_orgChart[3]=="1"){ $row_iconos_org_chart_2022.="<p style='display: inline;' title='Identifica como crucial para el plan de continuidad de negocios, por contribuir en la toma de decisiones estratégicas frente a un evento disruptivo'> ".$AllIcons[2]->icon."</p>";}
    if($Iconos_orgChart[4]=="1"){ $row_iconos_org_chart_2022.="<p style='display: inline;' title='Identifica aquella persona critica en el plan de continuidad de negocios, para resguardar la continuidad operacional de la cadena de pago del banco, frente a un evento disruptivo'> ".$AllIcons[3]->icon."</p>";}
    if($Iconos_orgChart[7]=="1"){ $row_iconos_org_chart_2022.="<p  style='display: inline;' title='Identifica a las unidades organizacionales que tienen sucesor identificado'> ".$AllIcons[4]->icon."</p>";}  if($Iconos_orgChart[5]=="1"){ $row_iconos_org_chart_2022.="".$AllIcons[4]->icon."";}
    if($Iconos_orgChart[6]=="1"){ $row_iconos_org_chart_2022.="".$AllIcons[5]->icon."";}



    //echo "<btr>--> ".$row_iconos_org_chart_2022;
    if($perfil_orgchart=="Visualizador_basico"){
        $row_iconos_org_chart_2022="";
    }

    $arreglo[0]=$num_equipo;
    $arreglo[1]=$row_ficha_num_equipo;
    $arreglo[2]=$row_iconos_org_chart_2022;

    return $arreglo;

}
function Reconocimiento_2021_alertas_($PRINCIPAL, $rut, $saldo,$Puntos_Recibidos,$Puntos_Repartidos){
	
	if($saldo==""){$saldo=0;}
	if($Puntos_Recibidos==""){$Puntos_Recibidos=0;}
	if($Puntos_Repartidos==""){$Puntos_Repartidos=0;}
	
	//echo "Funciones Amonestacion Reconocimiento_2021_alertas_ rut $rut,  saldo $saldo, Puntos_Recibidos  $Puntos_Recibidos, Puntos_Repartidos $Puntos_Repartidos";
	
	if($Puntos_Recibidos>0){
	
		if($saldo>0){
			$DISPLAY_FORM_RECONOCIMIENTO_COLABORACION="";
			$DISPLAY_ALERTA_RECONOCIMIENTO_COLABORACION="display:none !important;";
			$DISPLAY_ALERTA_SIN_ACCESO=" display:none !important;";		
		
		} else {
			$DISPLAY_FORM_RECONOCIMIENTO_COLABORACION="display:none !important;";
			$DISPLAY_ALERTA_RECONOCIMIENTO_COLABORACION="";
			$DISPLAY_ALERTA_SIN_ACCESO=" display:none !important;";		
			
		}
		
	} else {
		
		$DISPLAY_FORM_RECONOCIMIENTO_COLABORACION=" display:none !important;";
		$DISPLAY_ALERTA_RECONOCIMIENTO_COLABORACION=" display:none !important;";
		$DISPLAY_ALERTA_SIN_ACCESO="";
	}

        $PRINCIPAL = str_replace("{DISPLAY_FORM_RECONOCIMIENTO_COLABORACION}", 		$DISPLAY_FORM_RECONOCIMIENTO_COLABORACION , $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAY_ALERTA_RECONOCIMIENTO_COLABORACION}", 	$DISPLAY_ALERTA_RECONOCIMIENTO_COLABORACION , $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAY_ALERTA_SIN_ACCESO}", 									$DISPLAY_ALERTA_SIN_ACCESO , $PRINCIPAL);

	
	echo CleanHTMLWhiteList($PRINCIPAL);exit;
	
}

function SubirArchivosGenericos_2020($unico, $extension_doc, $prefijo, $ruta, $numero, $nombre_file)
{

    //print_r($unico);

    //echo "<br>$extension_archivo, $prefijo, $ruta<br>";
    $ruta_con_archivo=$ruta."/".$prefijo."_BK.".$extension_archivo;
    $ruta_con_archivo = $ruta . "/" . $prefijo . "." . $extension_doc;
    copy($unico['tmp_name'], $ruta_con_archivo);
    $nombre_imagen_video = $prefijo . "." . $extension_doc;
    //RedimensionarImagenSlider($ruta_con_archivo, $nombre_imagen_slider);
    //Devuelvol un arreglo, con datos del archivo subido
    $arreglo[0]          = $ruta_con_archivo; //Ruta Completa
    $arreglo[1]          = $prefijo . "." . $extension_doc; //Nombre del Archivo
    $arreglo[2]          = $ruta . "/" . $prefijo; //Ruta del Objeto sin el index
    return ($arreglo);
}

function Datos_Academicos_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $op9, $op10, $op11, $op12){

			//echo "<br>-- Beneficio_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8";
			$rut = $_SESSION["user_"];
			$value_data=Datos_Academicos_Tipo_Field_WithValue_data($idl,$name_field);
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
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}

	if($type_field=="select_est"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Carreras=Datos_Academicos_Trae_Carreras_data();
			foreach ($Carreras as $unicoC){
				if($value_data<>""){
							if($value_data==$unicoC->carreras){
					 			$selected=" selected ";
				 				} else {
				 			 $selected=" ";}		
				}

				$option.="<option value='".$unicoC->carreras."'>".$unicoC->carreras."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	/*if($op1==$value_data)		{ $selected1=" selected ";}
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
		*/

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}
	
	if($type_field=="select_inst"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Instituciones=Datos_Academicos_Trae_instituciones_data();
			foreach ($Instituciones as $unicoC){
				if($value_data<>""){
							if($value_data==$unicoC->institucion){
					 			$selected=" selected ";
				 				} else {
				 			 $selected=" ";}		
				}

				$option.="<option value='".$unicoC->institucion."'>".$unicoC->institucion."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	
		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}

	if($type_field=="select_ano"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Anos=Datos_Anos();
			foreach ($Anos as $unicoC){


				$option.="<option value='".$unicoC->ano_estudio."'>".$unicoC->ano_estudio."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				
				<option value='".$value_data."'>".$value_data."</option>
				".$option."</select>";
	}

	if($type_field=="select_pais"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Paises=Datos_Paises();
			foreach ($Paises as $unicoC){
				$option.="<option value='".$unicoC->pais."'>".$unicoC->pais."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}

	if($type_field=="select_sucursal"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Sucursales=Datos_Sucursales();
			foreach ($Sucursales as $unicoC){
				$option.="<option value='".$unicoC->sucursal."'>".$unicoC->sucursal."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}

	if($type_field=="select_idioma"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Idiomas=Datos_Idiomas();
			foreach ($Idiomas as $unicoC){
				$option.="<option value='".$unicoC->idioma."'>".$unicoC->idioma."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
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
		$input_form="	<input type='date' name='".$name_field."' ".$required_field."  autocomplete='off' class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="email"){
		
			if($id==6){
				$Datos_Personales_Array_value=Beneficios_CheckDatosPersonas($rut);
				$value_data=$Datos_Personales_Array_value[0]->email_particular;
			}		
		
		$input_form="	<input type='email' name='".$name_field."' ".$required_field." autocomplete='off' class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="info"){
		$input_form="	<input type='text' name='".$name_field."' ".$required_field." autocomplete='off' class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="number"){
		$input_form="	<input type='number'  id='".$name_field."' name='".$name_field."' autocomplete='off' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="text"){
		

		
		$input_form="	<input type='text'  id='".$name_field."' name='".$name_field."' autocomplete='off' ".$required_field." class='form form-control' value='".$value_data."'>";
	}

	if($type_field=="textarea"){
		$input_form="	<textarea  name='".$name_field."' ".$required_field." class='form form-control'>".$value_data."</textarea>";
	}

	if($type_field=="uploadfile"){
		$input_form="	<input type='file' name='".$name_field."' ".$required_field." class='form form-control'>";
	}

	return $input_form;

}

function Datos_Academicos_Ficha_por_colaborador_Web_Print_FN($rut_col, $tipo_despliegue, $tipo_sucesion, $nombre_mostrar){
						
						$id_empresa = $_SESSION["id_empresa"];
						if($tipo_despliegue=="print"){
	    					$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/datos_academicos_2021/index_col_ficha_print.html"),"2020");
						} 
						 else {

                             $perfil_potencial=Potencial_Sucesion_BuscaPerfil($_SESSION["user_"]);
                             //echo "<br> $perfil_potencial ";
                             if($perfil_potencial=="Visualizador_full_orgchart") {
                                 $PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/datos_academicos_2021/index_col_ficha_org_chart.html"),"2020");

                             } else {
                                    $PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/datos_academicos_2021/index_col_ficha.html"),"2020");
                             }


						}

       			$datos_usuario=DatosUsuario_($rut_col, $id_empresa);
						$foto_avatar = VerificaFotoPersonal($rut_col);
							//print_r($datos_usuario); echo "foto avatar ".$foto_avatar;
        		$datos_usuario_jefe=DatosUsuario_($datos_usuario[0]->jefe, $id_empresa);				
							//echo "nombre completo ".$datos_usuario[0]->nombre_completo;
						
						$fecha_acutalizacion_inf_general=Potencial_Fecha_Actualizacion_TblUsuario();
	        	$PRINCIPAL = str_replace("{FECHA_ACTUALIZACION_INFORMACION_GENERAL}", FechaDDMMYYYY($fecha_acutalizacion_inf_general), $PRINCIPAL);

						
        		$PRINCIPAL = str_replace("{NOMBRE_COMPLETO_FICHA}", $datos_usuario[0]->nombre_completo, $PRINCIPAL);
            $PRINCIPAL = str_replace("{EDAD_FICHA}", 						$datos_usuario[0]->edad, $PRINCIPAL);
            $PRINCIPAL = str_replace("{CARGO_FICHA}", 					Potencial_nomenclatura($datos_usuario[0]->cargo), $PRINCIPAL);
            $PRINCIPAL = str_replace("{JEFE_DIRECTO_FICHA}",		$datos_usuario_jefe[0]->nombre_completo, $PRINCIPAL);
            $PRINCIPAL = str_replace("{AREA_FICHA}", 						$datos_usuario[0]->area, $PRINCIPAL);
            $PRINCIPAL = str_replace("{DIVISION_FICHA}", 				$datos_usuario[0]->division, $PRINCIPAL);
            $PRINCIPAL = str_replace("{AVATAR_FICHA}", 					$foto_avatar, $PRINCIPAL);
            $PRINCIPAL = str_replace("{RUT_COL_ENC}", 					$_GET["rut_enc"], $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_DIVISION_FICHA}", 	$datos_usuario[0]->division, $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_AREA_FICHA}", 			$datos_usuario[0]->area, $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_ZONA_FICHA}", 			$datos_usuario[0]->zona, $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_SECC}", 						$datos_usuario[0]->centro_costo, $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_OFI}", 						$datos_usuario[0]->sucursal, $PRINCIPAL);
            $PRINCIPAL = str_replace("{REGION}", 								$datos_usuario[0]->regional, $PRINCIPAL);
            $PRINCIPAL = str_replace("{NIVEL}", 								$datos_usuario[0]->perfil_evaluacion, $PRINCIPAL);
            $PRINCIPAL = str_replace("{FEC_INDEM}", 						Potencial_calculoAnos($datos_usuario[0]->familia_cargo), $PRINCIPAL);
            $PRINCIPAL = str_replace("{NOMBRE_CARGO}", 					Potencial_nomenclatura($nombre_mostrar), $PRINCIPAL);
          
				 		$PRINCIPAL 	= Datos_Academicos_tbl_formularios_respuestas($PRINCIPAL, 	$rut_col);


	
				return $PRINCIPAL;
}

function Datos_Academicos_tbl_formularios_respuestas($PRINCIPAL, $rut_col){


		$id_form="1";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_1=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_1);
		if(count($Resp_1)>0){
			foreach ($Resp_1 as $unico){
				$row_form1.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)." 
														<br>
														<div class='bullet_white'></div>  ".utf8_encode($unico->idc02)." - 
														".utf8_encode($unico->idc03)."
														</span>
														</div>
														<div class='col-lg-2 col-xs-2'>	
														
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
																	
														</div>									
													</div>";
			}
			$row_form1.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
			
		} else {
			$row_form1.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}
		
		$id_form="2";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_2=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_2);
		if(count($Resp_2)>0){
			foreach ($Resp_2 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				
				$row_form2.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)." 
														".utf8_encode($unico->idc02)."
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc03)."
														<br>
														<div class='bullet_white'></div>" .utf8_encode($unico->idc04)." 
														".utf8_encode($unico->idc05)."
														<br> 

														<div class='bullet_white'></div> ".utf8_encode($unico->idc06)." - ".utf8_encode($unico->idc07)."
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc08)."
														".utf8_encode($unico->idc09)."
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
														
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
																	
														</div>																
													</div>";
			}
						$row_form2.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form2.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}

		$id_form="3";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_3=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_3);

			if(count($Resp_3)>0){
			foreach ($Resp_3 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				
				
				$row_form3.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)." <br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc02)."
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc03).",
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc04)." - 
														".utf8_encode($unico->idc05)."
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc07)."
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
														
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
																	
														</div>																
													</div>";
			}
						$row_form3.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form3.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}

		$id_form="4";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_4=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_4);
			if(count($Resp_4)>0){
			foreach ($Resp_4 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				
				
				$row_form4.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)."
														<br>
														<div class='bullet_white'></div> ".utf8_encode($unico->idc02)."
														<br><div class='bullet_white'></div> ".utf8_encode($unico->idc03)."
														
														<br><div class='bullet_white'></div> ".utf8_encode($unico->idc04)." - ".utf8_encode($unico->idc05)."
														<br><div class='bullet_white'></div> ".utf8_encode($unico->idc07)."
														
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
														
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
																	
														</div>		
													</div>";
			}
						$row_form4.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form4.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}

		$id_form="5";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_5=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_5);
			if(count($Resp_5)>0){
			foreach ($Resp_5 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				
				
				$row_form5.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)."
														<br><div class='bullet_white'></div> ".utf8_encode($unico->idc02)." - ".utf8_encode($unico->idc03)."
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
														</div>															
													</div>
													";
			}
						$row_form5.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form5.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}



$id_form="6";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_6=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_5);
			if(count($Resp_6)>0){
			foreach ($Resp_6 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				
				
				$row_form6.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														".utf8_encode($unico->idc01)." ".utf8_encode($unico->idc02)."
														<br><div class='bullet_white'></div> ".utf8_encode($unico->idc03)."
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
														</div>															
													</div>
													";
			}
						$row_form6.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form6.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}




$id_form="7";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_7=Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_5);
			if(count($Resp_7)>0){
			foreach ($Resp_7 as $unico){
				
				if($unico->archivo1<>""){
					$archivo_certificado="<br><div class='bullet_white'></div><a href='docs/".$unico->archivo1."' target='_blank' class='btn btn-link' style='    padding-left: 2px;font-weight: 600;font-size: 15px;color: #192857;'><span style='color: #192857;'>Ver Certificado</span></a>";
				} else {
					$archivo_certificado="";
				}
				$lugar="";
				if($unico->idc02<>""){
					$lugar.="<br><div class='bullet_white'></div> Sucursal 1: ".utf8_encode($unico->idc02)."";
				}
				if($unico->idc03<>""){
					$lugar.="<br><div class='bullet_white'></div> Sucursal 2: ".utf8_encode($unico->idc03)."";
				}
				if($unico->idc04<>""){
					$lugar.="<br><div class='bullet_white'></div> Sucursal 3: ".utf8_encode($unico->idc04)."";
				}								
				$row_form7.="<div class='row'>
													<div class='col-lg-10 col-xs-10'>
														<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
														<div class='bullet_lightBlue'></div> 
														 Flexibilidad para trabajar en otra ciudad: ".utf8_encode($unico->idc01)."
														".$lugar."
														<br><div class='bullet_white'></div> Area de Inter&eacute;s: ".utf8_encode($unico->idc05)."
														</span>
														".$archivo_certificado."
														</div>
														<div class='col-lg-2 col-xs-2'>	
																	<a href='?sw=home_datos_academicos_formularios&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-pencil' viewBox='0 0 16 16'>
																		  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
																		</svg>
																	</a>
																	<a href='?sw=home_datos_academicos&idf=".Encodear3($id_form)."&rut_enc=".Encodear3($rut_col)."&idl=".Encodear3($unico->id)."&dl=1'>
																		<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#666' class='bi bi-trash' viewBox='0 0 16 16'>
																		  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
																		  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
																		</svg>														
																	</a>
														</div>															
													</div>
													";
			}
						$row_form7.="<div class='row'>
													<div class='col-lg-12 col-xs-12' style='    text-align: right;font-size: 12px;color: #999;'> Actualizado el ".FechaDDMMYYYY($unico->fecha)."
													</div>
									</div>";
		} else {
			$row_form7.="<div class='txt_actualizada_al'><center>Sin información</center></div>";
		}
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_ENSENANZA_ID_FORM_1}",			$row_form1, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_PREGRADO_ID_FORM_2}",			$row_form2, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_POSTGRADO_ID_FORM_3}",			$row_form3, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_DOCTORADO_ID_FORM_4}",			$row_form4, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_CERTIFICACION_ID_FORM_5}",	$row_form5, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_IDIOMAS_ID_FORM_6}",				$row_form6, $PRINCIPAL);
	 	$PRINCIPAL = str_replace("{ROW_DATOS_ACADEMICA_PREFERENCIA_ID_FORM_7}",		$row_form7, $PRINCIPAL);

    return ($PRINCIPAL);				
}

function Talento_Mapa_por_colaborador_Web_Print_FN($rut_col, $tipo_despliegue, $tipo_busqueda,$interino, $todes){
				$id_empresa = $_SESSION["id_empresa"];
				//echo "<br>Talento_Mapa_por_colaborador_Web_Print_FN, $rut_col, $tipo_despliegue, $tipo_busqueda, id_empresa $id_empresa $todes";
				
		    $PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_mapa_print.html"),"2020");
				$rut_col_get=$rut_col;
				$Map_sucesor						=Talento_Busca_Sucesions_map($rut_col, "sucesor",$interino);
				$Map_reemplazo					=Talento_Busca_Sucesions_map($rut_col, "reemplazo",$interino);
				$Map_sucesor_potencial	=Talento_Busca_Sucesions_map($rut_col, "sucesor_potencial",$interino);

				$Cerrado=Potencial_talento_busca_cierre_mapa($rut_col);
				if($Cerrado>0){
					$display_none_eliminar="display:none;";
				} else {
					$display_none_eliminar="";
				}

				/*echo "<br><h1>".$rut_col."</h1>";
				echo "<br> reem ".print_r($Map_reemplazo);
				echo "<br> pot ".print_r($Map_sucesor_potencial);
				echo "<br> suc ".print_r($Map_sucesor);*/


				if((count($Map_reemplazo)>0 or count($Map_sucesor_potencial)>0) and count($Map_sucesor)==0){
					$reclutamiento="1";
				} else {
					$reclutamiento="";
				}

			if(count($Map_sucesor)>0){
				foreach($Map_sucesor as $unico){
					//echo "<br>";
					//print_r($unico);
					$rut_col	=	DatosUsuario_($unico->rut_col, $id_empresa);
					//print_r($rut_col);
					$foto_col = BuscaAvatar2021($unico->rut_col);
			 		$fecha_agregado=(fechaCastellano($unico->fecha));
						
						
						if(Potencial_nomenclatura($rut_col[0]->cargo)<>""){
							$coma=", ";
						} else {
							$coma="";
						}
						
					$row_sucesores_print.="
					<div class='row' style='margin-bottom: 10px;'>
					   <div class='col-lg-12'>
					   		<img src='".$foto_col."' style='border-radius: 50%;    width: 50px;'> 
					      <h4 style='    margin-bottom: 0px; display: initial;'><span class='c-brand-weight_font'>".$rut_col[0]->nombre_completo."</span></h4>
					      ".$coma." ".Potencial_nomenclatura($rut_col[0]->cargo)."		
					   </div>
					   <div class='col-lg-6'>
					   </div>
					</div>	
					";
					
				}
			} else {
				if($reclutamiento=="1"){
								$row_sucesores_print="<div><i class='far fa-comment-alt'></i> Reclutamiento</div>";
		
				} else {
						$row_sucesores_print="<div><i class='far fa-comment-alt'></i> Sin sucesores seleccionados</div>";
				
				}

			}

			if(count($Map_reemplazo)>0){
				foreach($Map_reemplazo as $unico){
					$rut_col	=	DatosUsuario_($unico->rut_col, $id_empresa);
					$foto_col = BuscaAvatar2021($unico->rut_col);
					
					$fecha_agregado=utf8_decode(fechaCastellano($unico->fecha));
					
						if(Potencial_nomenclatura($rut_col[0]->cargo)<>""){
							$coma=", ";
						} else {
							$coma="";
						}					
					$row_reemplazo_print.="
					<div class='row' style='margin-bottom: 10px;'>
					   <div class='col-lg-12'>
					   		<img src='".$foto_col."' style='border-radius: 50%;    width: 50px;'>
					      <h4  style='    margin-bottom: 0px; display: initial;'><span class='c-brand-weight_font'>".$rut_col[0]->nombre_completo."</span></h4>
					      ".$coma." ".Potencial_nomenclatura($rut_col[0]->cargo)."		
					   </div>


					</div>	
					";		
				}
			} else {
					$row_reemplazo_print="<div><i class='far fa-comment-alt'></i> Sin reemplazos seleccionados</div>";
			}

			if(count($Map_sucesor_potencial)>0){
				foreach($Map_sucesor_potencial as $unico){
					$rut_col	=	DatosUsuario_($unico->rut_col, $id_empresa);
					$foto_col = BuscaAvatar2021($unico->rut_col);
					$fecha_agregado=utf8_decode(fechaCastellano($unico->fecha));

						if(Potencial_nomenclatura($rut_col[0]->cargo)<>""){
							$coma=", ";
						} else {
							$coma="";
						}

					$row_sucesor_potencial_print.="
					<div class='row' style='margin-bottom: 10px;'>
					   <div class='col-lg-12'>
					   <img src='".$foto_col."' style='border-radius: 50%;    width: 50px;'>
					      <h4  style='    margin-bottom: 0px; display: initial;'><span class='c-brand-weight_font'>".$rut_col[0]->nombre_completo."</span></h4>
					      ".$coma." ".Potencial_nomenclatura($rut_col[0]->cargo)."		
					   </div>


					</div>	
					
					";		
				}
			} else {
					$row_sucesor_potencial_print="<div><i class='far fa-comment-alt'></i> Sin sucesores potenciales seleccionados</div>";
			}


					if($todes=="todes"){
							//echo "<br>rut_col_get $rut_col_get<br>";
							$rut_col_full	=	DatosUsuario_($rut_col_get, $id_empresa);
							//print_r($rut_col_full);
							//exit();
						  $PRINCIPAL = str_replace("{NOMBRE_CARGO_DIVISION}", utf8_encode(Potencial_abreviatura_sinutf8($rut_col_full[0]->division)), $PRINCIPAL);				
					} 
   					

	 					$PRINCIPAL = str_replace("{ROWS_SUCESOR_PRINT}", 									$row_sucesores_print, $PRINCIPAL);
	 					$PRINCIPAL = str_replace("{ROWS_REEMPLAZO_PRINT}", 								$row_reemplazo_print, $PRINCIPAL);
					 	$PRINCIPAL = str_replace("{ROWS_SUCESOR_POTENCIAL_PRINT}", 				$row_sucesor_potencial_print, $PRINCIPAL);
						//echo "<br>RutCol TblUsuario $rut_col_get, $id_empresa";
						$rut_col_suceder	=	DatosUsuario_($rut_col_get, $id_empresa);
						//print_r($rut_col_suceder);
						$foto_col_suceder = VerificaFotoPersonal($rut_col_get);

			if($interino=="1"){
				if($Map_sucesor[0]->cargo_cambio<>""){
					$Map_sucesorcargo_cambio=$Map_sucesor[0]->cargo_cambio;
				} elseif($Map_reemplazo[0]->cargo_cambio<>"")
				{
					$Map_sucesorcargo_cambio=$Map_reemplazo[0]->cargo_cambio;
				} else {
					$Map_sucesorcargo_cambio=$Map_sucesor_potencial[0]->cargo_cambio;
				}
			
				
				$cargomostrar="Interino<br>".$Map_sucesorcargo_cambio;
			} else {
				$cargomostrar=Potencial_nomenclatura($rut_col_suceder[0]->cargo);
			}


	 					$PRINCIPAL = str_replace("{CARGO_A_SUCEDER}", 						$cargomostrar, $PRINCIPAL);
	 					$PRINCIPAL = str_replace("{NOMBRE_A_SUCEDER}", 						$rut_col_suceder[0]->nombre_completo, $PRINCIPAL);
	 					$PRINCIPAL = str_replace("{FOTO_A_SUCEDER}", 							$foto_col_suceder, $PRINCIPAL);
	
	return $PRINCIPAL;
	
	
}

function Potencial_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $op9, $op10, $op11, $op12){

			//echo "<br>-- Beneficio_Tipo_Field_WithValue($idl,$type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8";
	$rut = $_SESSION["user_"];
	$value_data=Potencial_Tipo_Field_WithValue_data($idl,$name_field);
				
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
	
	if($type_field=="division_select"){
		
		$div_list=Potencial_Divisiones();
		
		
		foreach ($div_list as $unico){
			$option.="<option value='".$unico->division."'>".$unico->division."</option>";
		}
		
		
				
		
		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
		<option value='".$value_data."'>".$value_data."</option>;
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

	if($type_field=="select_est"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Carreras=Datos_Academicos_Trae_Carreras_data();
			foreach ($Carreras as $unicoC){
				if($value_data<>""){
							if($value_data==$unicoC->carreras){
					 			$selected=" selected ";
				 				} else {
				 			 $selected=" ";}		
				}

				$option.="<option value='".$unicoC->carreras."'>".$unicoC->carreras."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	/*if($op1==$value_data)		{ $selected1=" selected ";}
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
		*/

		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}
	
	if($type_field=="select_inst"){
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$Instituciones=Datos_Academicos_Trae_instituciones_data();
			foreach ($Instituciones as $unicoC){
				if($value_data<>""){
							if($value_data==$unicoC->institucion){
					 			$selected=" selected ";
				 				} else {
				 			 $selected=" ";}		
				}

				$option.="<option value='".$unicoC->institucion."'>".$unicoC->institucion."</option>";
			}
			//print_r($Carreras);
			
	$selected="";
	
		$input_form="	<select name='".$name_field."' ".$required_field." class='form form-control'>
				<option value='".$value_data."'>".$value_data."</option>".$option."</select>";
	}

	return $input_form;
}

function Potencial_Tipo_Field_WithValueUser_Ficha($type_field, $name_field, $required, $id, $op1, $op2, $op3, $op4, $op5, $op6, $op7, $op8, $id_evento){

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

function Talento_Ficha_por_colaborador_Web_Print_FN($rut_col, $tipo_despliegue, $tipo_sucesion, $nombre_mostrar){
						//echo "<br>FN Talento_Ficha_por_colaborador_Web_Print_FN $rut_col, tipo_despliegue $tipo_despliegue, tipo_sucesion $tipo_sucesion";
                        //$perfil_sesion=Potencial_Sucesion_BuscaPerfil($_SESSION["user_"]);
						//echo "<br>perfil ".$perfil_sesion;
						$id_empresa = $_SESSION["id_empresa"];
						if($tipo_despliegue=="print"){
	    					$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_print.html"),"2020");
						} 
						elseif($tipo_despliegue=="sucesion") {
    						$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_sucesion.html"),"2020");
						}
						elseif($tipo_despliegue=="cargo") {
    						$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_por_cargo.html"),"2020");
						}		
						elseif($tipo_despliegue=="checkbox_division") {
    						$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_por_checkbox_division.html"),"2020");
						}												
						 else {

                             $perfil_potencial=Potencial_Sucesion_BuscaPerfil($_SESSION["user_"]);
                             //echo "<br> $perfil_potencial ";
                             if($perfil_potencial=="Visualizador_full_orgchart") {
                                 $PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha_org_chart.html"),"2020");

                             } else {
                                 $PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha.html"),"2020");
                             }

    						//$PRINCIPAL.= FuncionesTransversales_2020(file_get_contents("views/talento_2021/index_col_ficha.html"),"2020");
						}

       		$datos_usuario=DatosUsuario_($rut_col, $id_empresa);
       		$disponibilidad=PotencialBuscaDisponibilidad($rut_col);
			$foto_avatar = VerificaFotoPersonal($rut_col);
						//print_r($datos_usuario); echo "foto avatar ".$foto_avatar;
        	$datos_usuario_jefe=DatosUsuario_($datos_usuario[0]->jefe, $id_empresa);
							//echo "nombre completo ".$datos_usuario[0]->nombre_completo;
			$fecha_acutalizacion_inf_general=Potencial_Fecha_Actualizacion_TblUsuario();
	        $PRINCIPAL = str_replace("{FECHA_ACTUALIZACION_INFORMACION_GENERAL}", FechaDDMMYYYY($fecha_acutalizacion_inf_general), $PRINCIPAL);
        	$PRINCIPAL = str_replace("{NOMBRE_COMPLETO_FICHA}", $datos_usuario[0]->nombre_completo, $PRINCIPAL);
            $PRINCIPAL = str_replace("{EDAD_FICHA}", 						$datos_usuario[0]->edad, $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPONIBILIDAD_TRASLADO_1}", 					$disponibilidad[1], $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPONIBILIDAD_TRASLADO_2}", 					$disponibilidad[2], $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPONIBILIDAD_TRASLADO_3}", 					$disponibilidad[3], $PRINCIPAL);
            $PRINCIPAL = str_replace("{CARGO_CRITICO}", 											$disponibilidad[4], $PRINCIPAL);
            $PRINCIPAL = str_replace("{CARGO_FICHA}", 					Potencial_nomenclatura($datos_usuario[0]->cargo), $PRINCIPAL);
            $PRINCIPAL = str_replace("{JEFE_DIRECTO_FICHA}",		$datos_usuario_jefe[0]->nombre_completo, $PRINCIPAL);
            $PRINCIPAL = str_replace("{AREA_FICHA}", 						Potencial_abreviatura($datos_usuario[0]->area), $PRINCIPAL);
            $PRINCIPAL = str_replace("{DIVISION_FICHA}", 				Potencial_abreviatura($datos_usuario[0]->division), $PRINCIPAL);
            $PRINCIPAL = str_replace("{AVATAR_FICHA}", 					$foto_avatar, $PRINCIPAL);
            $PRINCIPAL = str_replace("{RUT_COL_ENC}", 					$_GET["rut_enc"], $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_DIVISION_FICHA}", 		Potencial_abreviatura($datos_usuario[0]->division), $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_AREA_FICHA}", 				Potencial_abreviatura($datos_usuario[0]->area), $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_ZONA_FICHA}", 				Potencial_abreviatura($datos_usuario[0]->zona), $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_SECC}", 						Potencial_abreviatura($datos_usuario[0]->centro_costo), $PRINCIPAL);
            $PRINCIPAL = str_replace("{GLOSA_OFI}", 						$datos_usuario[0]->sucursal, $PRINCIPAL);
            $PRINCIPAL = str_replace("{REGION}", 								$datos_usuario[0]->regional, $PRINCIPAL);
            $PRINCIPAL = str_replace("{NIVEL}", 								$datos_usuario[0]->perfil_evaluacion, $PRINCIPAL);
            $PRINCIPAL = str_replace("{FEC_INDEM}", 						Potencial_calculoAnos($datos_usuario[0]->familia_cargo), $PRINCIPAL);
            $PRINCIPAL = str_replace("{NOMBRE_CARGO}", 					Potencial_nomenclatura($nombre_mostrar), $PRINCIPAL);
			if($tipo_sucesion=="Reemplazo"){ $tipo_sucesion="Reemplazo Temporal"; }
            $PRINCIPAL = str_replace("{TIPO_SUCESION}", 						$tipo_sucesion, $PRINCIPAL);           

            $doc_prism="<div class='txt_sin_informacion'>Sin Información</div>";
            $fecha_prism="";

            $Prisms=Potencial_Busca_Doc_Prism($rut_col);
            $doc_prism="";
            foreach($Prisms as $uniPrism) {
                $doc_prism.="
                    <a href='docs/".$uniPrism->documento."' target='_blank' class='btn btn-btn-link' ><span class='c-brand'>".$uniPrism->nombre_archivo."</span></a>";
                $informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($uniPrism->fecha)."</div>";
                $fecha_prism=$informacion_actualizada;

            }


        		$PRINCIPAL = str_replace("{LISTA_ROW_PRISM}", 			$doc_prism, $PRINCIPAL);
        		$PRINCIPAL = str_replace("{INFORMACION_PRISM_ACTUALIZADA}", 			$fecha_prism, $PRINCIPAL);
          		
				 		$PRINCIPAL 	= Potencial_tbl_formularios_respuestas($PRINCIPAL, 	$rut_col);
						$PRINCIPAL 	= Potencial_AgraRecoPorRut($PRINCIPAL, $id_empresa,  		$rut_col);
						$PRINCIPAL 	= Potencial_CursosPorRut_2021($PRINCIPAL, $id_empresa,  	$rut_col);
						$PRINCIPAL 	= Potencial_Datos_Tablas_2021($PRINCIPAL, $id_empresa,  	$rut_col);	
	
				return $PRINCIPAL;
}

function Potencial_AgraRecoPorRut($PRINCIPAL, $id_empresa, $rut)
{   
	
  $date_hace_12_meses = date("Y-m-d", strtotime(" -12 months"));
    //echo $date;
		//echo "rut $rut, id_empresa $id_empresa";

$array_agradecimiento=Busca_Reco_MiEquipo("GRACIAS", $id_empresa, $rut);
//print_r($array_agradecimiento);
if(count($array_agradecimiento)>0){
    foreach ($array_agradecimiento as $unico) {		
        $row .= file_get_contents("views/talento_2021/row_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<div class='far fa-heart fa-1x icon_mascon' title='Agradecimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="Agradecimiento Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<div class='far fa-heart fa-1x icon_mascon' title='Agradecimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="".utf8_encode($unico->categorita)."";
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		        //echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;echo "<br>Rut $rut, rut_reco $rut_reco<br>";

		$Usua=DatosUsuario_($rut_reco, $id_empresa);
            //echo "<br>->Usuario<br>";
            //print_r($Usua);
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
        //echo "<br>->Usuario Historico <br>";print_r($Usua);


		
		if($Usua[0]->nombre_completo==""){
			
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
		$row = str_replace("{ICO_RECO}", 		$ico_reco, $row);
        $row = str_replace("{TIPO_RECO}", 		$tipo_reco, $row);
        $row = str_replace("{CLASS_RECO}", 		$class_reco, $row);
		$row = str_replace("{FECHA_RECO}", 		FechaDDMMYYYY($unico->fecha), $row);
        $row = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row);			
		$row = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row); 	
		$row = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row);		
		
	}
} else {
	$row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row = str_replace("{TEXTO_SIN}", 		"<div class='txt_sin_informacion'>Sin Agradecimientos</div>", $row);	
}
$array_reco=Busca_Reco_MiEquipo("RECONOCIMIENTO", $id_empresa, $rut);
//print_r($array_reco);
//echo "cuenta ".count($array_reco);
if(count($array_reco)>0){

    foreach ($array_reco as $unico) {		
        $row2 .= file_get_contents("views/talento_2021/row_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		="Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;

			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				
				if($unico->categorita=="SELECCION DEL CHILE"){
					$tipo_reco="Selección del Chile";
				} elseif($unico->categorita=="COLABORACION"){
					$tipo_reco="Colaboración";
				} else {
					$tipo_reco="";
				}
				
				$ico_reco		="<div class='icon-star fa-1x icon_mascon' title='Reconocimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="Reconocimiento - ".($tipo_reco);
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
		
			
			
		
		$row2 = str_replace("{ICO_RECO}", 		$ico_reco, $row2);
        $row2 = str_replace("{TIPO_RECO}", 		$tipo_reco, $row2);
        $row2 = str_replace("{CLASS_RECO}", 		$class_reco, $row2);
		$row2 = str_replace("{FECHA_RECO}", 		FechaDDMMYYYY($unico->fecha), $row2);
        $row2 = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row2);			
		$row2 = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row2); 	
		$row2 = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row2);			
	}
	
} else {
	$row2 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row2 = str_replace("{TEXTO_SIN}", 		"<div class='txt_sin_informacion'>Sin Reconocimientos</div>", $row2);
}

$array_AMON=Busca_Reco_MiEquipo("AMONESTACION", $id_empresa, $rut);
//print_r($array_AMON);

if(count($array_AMON)>0){
    foreach ($array_AMON as $unico) {		
    	
    	
    	//echo "<br>".$date_hace_12_meses." ".$unico->fecha;
    	if($date_hace_12_meses > $unico->fecha){ continue;}
    	
        $row3 .= file_get_contents("views/talento_2021/row_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		=utf8_encode($unico->categorita);
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<div class='far fa-file-alt  fa-1x icon_mascon' title='Amonestacion/Recomendaciones' style='display: inline; display: inline; '></div>";	
				$tipo_reco		=utf8_encode($unico->categorita);
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		
	
	

		
		if($unico->acuse_recibo<>""){
			$acuse_recibdo="
			
					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>
			
			
			<div class='alert alert-info'><center>Acuse de recibo realizado</center></div>";
		} else {
			
			if($rut==$_SESSION["user_"]){
$acuse_recibdo="

					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>

			<div class='alert alert-warning'><center>Acuse de recibo pendiente</center>
			<br>
			<center>
				<a href='?sw=mi_perfil_2020&acr=".Encodear3($unico->id)."' class='btn btn-info'>Acusar Recibo</a>
			</center>
			
			</div>";				
			} else {
	$acuse_recibdo="

					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>

			<div class='alert alert-warning'><center>Acuse de recibo pendiente</center>

			
			</div>";			
			}
			//echo "rut $rut session".$_SESSION["user_"];
			
			
		}
		
		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
					$row3 = str_replace("{ICO_RECO}", 		$ico_reco, $row3);
        $row3 = str_replace("{TIPO_RECO}", 		$tipo_reco, $row3);
        $row3 = str_replace("{CLASS_RECO}", 		$class_reco, $row3);
		
				$row3 = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row3); 	
				$row3 = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row3);		

				$row3 = str_replace("{ACUSE_RECIBO}", 		$acuse_recibdo, $row3);

		
	}
} else {
	$row3 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_amon_sin.html");
	$row3 = str_replace("{TEXTO_SIN}", 		"<div class='txt_sin_informacion'>Sin Amonestaciones</div>", $row3);	
}


    $PRINCIPAL = str_replace("{LISTA_AGRADECIMIENTO}", ($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_AMONESTACIONES}", ($row3), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_RECONOCIMIENTO}", ($row2), $PRINCIPAL);
    return ($PRINCIPAL);
}

    function Potencial_Cursos_String_3puntos($string, $max_length, $append) {
        // If the string is already shorter than the maximum length, return it as is
        if (strlen($string) <= $max_length) {
            return $string;
        }
        // Truncate the string and append the ellipsis
        return substr($string, 0, $max_length) . $append;
    }

function Potencial_CursosPorRut_2021($PRINCIPAL, $id_empresa, $rut)
{   

//echo "rut $rut, id_empresa $id_empresa";

$array_cursos=Potencial_Busca_Cursos_MiEquipo_data("", $id_empresa, $rut);


if(count($array_cursos)>80){    $font_size="14px";  $height_size="20px";      } else     {$font_size="14px";$height_size="auto"; }

if(count($array_cursos)>0){
    foreach ($array_cursos as $unico) {		
        $row .= file_get_contents("views/talento_2021/row_ficha_cursos.html");

		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		
		if($unico->curso==$unico->programa){
			$curso=$unico->programa;
		} else {
			$curso=$unico->programa." - ".$unico->curso;
		}
		$curso="".$curso;
        $curso_array = explode("<br>", $curso);
        $curso=$curso_array[0];

        $curso=Potencial_Cursos_String_3puntos($curso,60, "...");
		$row = str_replace("{CURSO}", 		utf8_encode($curso), $row);
        $row = str_replace("{FECHA}", 		utf8_encode($unico->fecha_inicio), $row);
        $row = str_replace("{APROBADO}", 	utf8_encode($unico->status), $row);
        $row = str_replace("{font_size}", 	utf8_encode($font_size), $row);
        $row = str_replace("{height_size}", 	utf8_encode($height_size), $row);

		$estado="";
		
		if($unico->estado=="APROBADO"){
			$estado="<div class='alert alert-success'>Aprobado</div>";
		} else {
			continue;
		}
		if($unico->estado=="REPROBADO"){
			$estado="<div class='alert alert-danger'>Reprobado</div>";
		}

		if($unico->estado=="REPROBADO_POR_INASISTENCIA"){
			$estado="<div class='alert alert-danger'>Reprobado por Inasistencia</div>";
		}

		if($unico->estado=="EN_PROCESO"){
			$estado="<div class='alert alert-warning'>En Proceso</div>";
		}

		if($unico->estado=="NO_INICIADO"){
			$estado="<div class='alert alert-warning'>No Iniciado</div>";
		}

	        $row = str_replace("{ESTADO}", 	utf8_encode($estado), $row);
	
		if($unico->resultado<>""){
			$nota="Nota: ".round($unico->resultado)."%";
			
		} else {
			$nota="";
		}
		
        $row = str_replace("{NOTA}", 	$nota, $row);
		
		
	}
} else {
	$row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row = str_replace("{TEXTO_SIN}", 		"Sin Reconocimientos", $row);	
}



    $PRINCIPAL = str_replace("{LISTA_CURSOS_APROBADOS}", ($row), $PRINCIPAL);
    return ($PRINCIPAL);
}

function Potencial_Datos_Tablas_2021($PRINCIPAL, $id_empresa, $rut)
{   
		
		$sininformacion="<div class='txt_sin_informacion'>Sin Informaci&oacute;n</div>";
		//echo "Potencial_Datos_Tablas_2021 rut $rut, id_empresa $id_empresa";
		$CarreraInterna=Potencial_List_tbl_mc_carrera_interna($rut);
		foreach ($CarreraInterna as $unico){
			if($unico->fec_ter==""){$unico->fec_ter=" la actualidad";}
			if($unico->fec_ter=="0000-00-00"){$unico->fec_ter=" la actualidad";}
			$unico->fec_ini = str_replace(" 00:00:00.000", "", $unico->fec_ini);

			
		$row_tbl_carrera_interna.="
		
						      <li class=' ai-c py-4 fw-b c-brand bbw-1' style='{DISPLAY_CARGO_DENTRO_BANCO_FICHA_1}'>
						        <strong>".(Potencial_nomenclatura($unico->cargo_glosa))." </strong>: ".utf8_encode(($unico->fec_ini))." a ".utf8_encode(($unico->fec_ter))."
						      </li>	";			
						      //FechaDDMMYYYY
		}

			if($row_tbl_carrera_interna==""){
    			$PRINCIPAL = str_replace("{ROW_TBL_CARRERA_INTERNA}", ($sininformacion), $PRINCIPAL);
			} else {
    			$PRINCIPAL = str_replace("{ROW_TBL_CARRERA_INTERNA}", ($row_tbl_carrera_interna), $PRINCIPAL);
			}		
			
			$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";
			
				if($row_tbl_carrera_interna==""){$display_carrera_interna_ficha_1=" display:none !important; ";
				$PRINCIPAL = str_replace("{INFORMACION_CARRERA_INTERNA_ACTUALIZADA}", 		$sininformacion, $PRINCIPAL);
				} else {
				$PRINCIPAL = str_replace("{INFORMACION_CARRERA_INTERNA_ACTUALIZADA}", 		$informacion_actualizada, $PRINCIPAL);
				}
				
				$PRINCIPAL = str_replace("{INFORMACION_CARRERA_INTERNA_ACTUALIZADA}", ($sininformacion), $PRINCIPAL);

		$Desempeno=Potencial_List_tbl_mc_tramo_desempeno($rut);
		foreach ($Desempeno as $unico){
		$row_tbl_mc_tramo_desempeno.="
						      
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
						        <div class='bullet_lightBlue'></div> <span style='font-size: 13px !important;'>Tramo gesti&oacute;n del desempeño:</span> <span class='c-brand-weight'>
						        ".utf8_encode(($unico->tramo_gestion_desempeno))."</span>
						      </li>	";			
						      
		$row_tbl_mc_tramo_desempeno_ZONA.="
							  Zona Salarial: <span class='c-brand-weight'>".utf8_encode($unico->zona_salarial)."</span></span>
						     ";							      
						      //FechaDDMMYYYY
						$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";
			      
		}		
		
			if($row_tbl_mc_tramo_desempeno==""){$display_mc_tramo_desempeno_ficha_1=" display:none !important; ";
				$PRINCIPAL = str_replace("{INFORMACION_TRAMO_DESEMPENO_ACTUALIZADA}", 		$sininformacion, $PRINCIPAL);
				} else {
				$PRINCIPAL = str_replace("{INFORMACION_TRAMO_DESEMPENO_ACTUALIZADA}", 		$informacion_actualizada, $PRINCIPAL);
				}
			$PRINCIPAL = str_replace("{DISPLAY_TRAMO_DESEMPENO}", 		$display_mc_tramo_desempeno_ficha_1, $PRINCIPAL);

		
			$Metas=Potencial_List_tbl_mc_potencial_metas($rut);
			//print_r($Metas);
			foreach ($Metas as $unico){
				//echo "<br>";
				//print_r($unico);
				//echo "<br>";
						if($unico->periodo=="2016" or $unico->periodo=="2017"){
							continue;
						}
									$row_tbl_mc_tramo_desempeno.="
						      
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
						        <div class='bullet_lightBlue'></div> Meta ".$unico->periodo.": <span class='c-brand-weight'>
						        ".utf8_encode(($unico->meta))."</span>
						      </li>	";	
				
			}

			$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";		

			
			if($row_tbl_mc_tramo_desempeno==""){
    			$PRINCIPAL = str_replace("{ROW_TBL_TRAMO_DESEMPENO}", ($sininformacion), $PRINCIPAL);
    			$PRINCIPAL = str_replace("{INFORMACION_TRAMO_DESEMPENO_ACTUALIZADA}", "", $PRINCIPAL);
    			
			} else {
    			$PRINCIPAL = str_replace("{ROW_TBL_TRAMO_DESEMPENO}", ($row_tbl_mc_tramo_desempeno), $PRINCIPAL);
    			$PRINCIPAL = str_replace("{INFORMACION_TRAMO_DESEMPENO_ACTUALIZADA}", ($informacion_actualizada), $PRINCIPAL);
			}




			if($row_tbl_mc_tramo_desempeno_ZONA==""){
    			$PRINCIPAL = str_replace("{ROW_TBL_TRAMO_DESEMPENO_ZONA_SALARIAL}", ($sininformacion), $PRINCIPAL);
			} else {
    			$PRINCIPAL = str_replace("{ROW_TBL_TRAMO_DESEMPENO_ZONA_SALARIAL}", ($row_tbl_mc_tramo_desempeno_ZONA), $PRINCIPAL);
			}


		$Competencias=Potencial_List_tbl_mc_potencial_competencias($rut);
		$cuenta_competencia=0;
		//print_r($Competencias);

		foreach ($Competencias as $unico){


			$TxtAscendente="<br>Ascendente:";
			$display_full_des_asc="";
			$display_full_comp="";
			if($unico->ascendente==""){
				//echo "->oculto descendente y ascendente";
				$display_full_des_asc=" display:none; ";
				$TxtAscendente="";
			}
		
			if($unico->final==""){
				//echo "-->oculto todo";
				$display_full_comp=" display: none !important; ";
				$TxtAscendente="";
			}

		if($display_full_comp==" display: none !important; "){
			
		} else {
			$cuenta_competencia++;
		}
			
			$row_tbl_mc_potencial_competencias.="
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1' style='".$display_full_comp."'>
						        <div class='bullet_lightBlue'></div> <span style=''>Periodo: <strong>".utf8_encode($unico->periodo)."</strong></span>
						      </li>		
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1' style='".$display_full_comp."'>
						        <div class='bullet_lightwhite'></div>Nota Final: <strong>".utf8_encode($unico->final)."</strong> 
						        <!--<span style='".$display_full_des_asc."'><br><br>
						        Descendente:
						        ".$TxtAscendente."
						        </span>
						        <span class='c-brand-weight'>
						        ".utf8_encode(($unico->final))."
						        <br>".utf8_encode(($unico->descendente))."
						        <span style='".$display_full_des_asc."'><br>".utf8_encode(($unico->ascendente))."</span>
						        </span>-->
						      </li>	";			
						      //FechaDDMMYYYY
		}		
		
    $PRINCIPAL = str_replace("{ROW_TBL_COMPETENCIAS_HISTORICAS}", ($row_tbl_mc_potencial_competencias), $PRINCIPAL);
		//echo "<br>row_tbl_mc_potencial_competencias<br>._".$row_tbl_mc_potencial_competencias."_.";
		
		$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";		
		//echo "cuenta competencia $cuenta_competencia";
		
		if($cuenta_competencia>0){
			//ho "<h1>a</h1>";
	    $PRINCIPAL = str_replace("{INFORMACION_COMPETENCIAS_HISTORICAS_ACTUALIZADA}", ($informacion_actualizada), $PRINCIPAL);
			
		} else {
			//echo "<h1>b</h1>";
   		 $PRINCIPAL = str_replace("{INFORMACION_COMPETENCIAS_HISTORICAS_ACTUALIZADA}", ($sininformacion), $PRINCIPAL);
		
		}


		$Clima=Potencial_List_tbl_mc_potencial_clima($rut);
		foreach ($Clima as $unico){
		$row_tbl_mc_potencial_clima.="
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1' style='{DISPLAY_CARGO_DENTRO_BANCO_FICHA_1}'>
						        <div class='bullet_lightBlue'></div> <span style='min-width:25%'>Periodo:</span> <span class='c-brand-weight'>
						        ".utf8_encode($unico->periodo)."</span>
						      </li>		
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
						        <div class='bullet_lightwhite'></div> Resultado Jefatura: <span class='c-brand-weight'>
						        ".utf8_encode(($unico->clima))."</span>
						      </li>	";			
						      //FechaDDMMYYYY
		}		
    $PRINCIPAL = str_replace("{ROW_TBL_CLIMA}", ($row_tbl_mc_potencial_clima), $PRINCIPAL);

		$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";		


if($row_tbl_mc_potencial_clima==""){
    $PRINCIPAL = str_replace("{ROW_TBL_CLIMA}", $sininformacion, $PRINCIPAL);

	    $PRINCIPAL = str_replace("{INFORMACION_CLIMA_ACTUALIZADA}", "<div class='txt_sin_informacion'>Sin Informaci&oacute;n</div>", $PRINCIPAL);
	
} else {
    $PRINCIPAL = str_replace("{ROW_TBL_CLIMA}", ($row_tbl_mc_potencial_clima), $PRINCIPAL);

		    $PRINCIPAL = str_replace("{INFORMACION_CLIMA_ACTUALIZADA}", ($informacion_actualizada), $PRINCIPAL);

}


		$Bac=Potencial_List_tbl_mc_potencial_bac($rut);
		$textoBac="Colaborador no presenta registro";
			foreach ($Bac as $unico){
				
				//print_r($unico);
				//echo "unico bac ".$unico->rut;
				if($unico->rut<>""){
					$textoBac="Colaborador presenta registro";
				} else {
					$textoBac="Colaborador no presenta registro";
				}

							      //FechaDDMMYYYY
			}		
		
		$row_tbl_mc_potencial_bac.="
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1' style='{DISPLAY_CARGO_DENTRO_BANCO_FICHA_1}'>
						        <div class='bullet_lightBlue'></div> <span class='c-brand-weight'>
						        ".$textoBac."
						        </span>
						      </li>		
						      	";					
    $PRINCIPAL = str_replace("{ROW_TBL_BAC}", ($row_tbl_mc_potencial_bac), $PRINCIPAL);


		$Relacionados=Potencial_List_tbl_mc_potencial_relacionados($rut);
		//print_r($Relacionados);
		foreach ($Relacionados as $unico){
		$row_tbl_mc_potencial_relacionados.="
						      <li class='d-flex ai-c py-4 fw-b c-brand bbw-1' style='{DISPLAY_CARGO_DENTRO_BANCO_FICHA_1}'>
						        <div class='bullet_lightBlue'></div> <span class='c-brand-weight' style='font-family: 'nunito_sans_bold';padding-left: 5px;'>".utf8_encode($unico->estado)."</span> 
						      </li>		
						      ";			
						      //FechaDDMMYYYY
		}		
		if($unico->fecha==""){$unico->fecha=="2021-07-08";}
				$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($unico->fecha)."</div>";		

		if($row_tbl_mc_potencial_relacionados==""){
		$PRINCIPAL = str_replace("{LISTA_ROW_DECLARACION_RELACIONADOS}", ($sininformacion), $PRINCIPAL);	
		$PRINCIPAL = str_replace("{INFORMACION_DECLARACION_RELACIONADOS_ACTUALIZADA}", "", $PRINCIPAL);
			
		} else {
		$PRINCIPAL = str_replace("{LISTA_ROW_DECLARACION_RELACIONADOS}", ($row_tbl_mc_potencial_relacionados), $PRINCIPAL);	
		$PRINCIPAL = str_replace("{INFORMACION_DECLARACION_RELACIONADOS_ACTUALIZADA}", ($informacion_actualizada), $PRINCIPAL);
		}


    return ($PRINCIPAL);
}

function Potencial_calculoAnos($fecha_dada){
	
	$hoy=date("Y-m-d");

	$firstDate = new DateTime($fecha_dada); 
	$secondDate = new DateTime($hoy); 
	$intvl = $firstDate->diff($secondDate); 
	//echo "<br>Hoy ".$hoy;echo "<br>Fecha Dada ".$fecha_dada;

	$tiempo_transcurrido="".$intvl->y . " a&ntilde;os, " . $intvl->m." meses";	

	return $tiempo_transcurrido;
	
}

function Potencial_calculoAnos_v2($fecha_dada){
	
	$hoy=date("Y-m-d");

	$firstDate = new DateTime($fecha_dada); 
	$secondDate = new DateTime($hoy); 
	$intvl = $firstDate->diff($secondDate); 
	//echo "<br>Hoy ".$hoy;echo "<br>Fecha Dada ".$fecha_dada;

	$tiempo_transcurrido="".$intvl->y . "A, " . $intvl->m."M";	

	return $tiempo_transcurrido;
	
}

function Potencial_tbl_formularios_respuestas($PRINCIPAL, $rut_col){

		
		$fecha_dada="2018-10-01";
		Potencial_calculoAnos($fecha_dada);
		$sininformacion="<div class='txt_sin_informacion'>Sin Información</div>";
		$id_form="1";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_1=Potencial_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_2);
		if($Resp_1[0]->idc01<>""){
			$PRINCIPAL = str_replace("{ESTADO_CIVIL}", 				utf8_encode($Resp_1[0]->idc01), $PRINCIPAL);
		} else {
			$PRINCIPAL = str_replace("{ESTADO_CIVIL}", 				($sininformacion), $PRINCIPAL);	
		}
		
	
		$id_form="2";
		//echo "<br>RutCol $rut_col, id_form $id_form";
		$Resp_2=Potencial_tbl_formularios_respuestas_data($id_form, $rut_col);
		//print_r($Resp_2);
		$fecha_2=Potencial_tbl_formularios_respuestas_data_ultimaFecha($id_form, $rut_col);
			$informacion_actualizada="<div class='txt_actualizada_al'>
			Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($fecha_2)."</div>";
		
			if($Resp_2[0]->idc01==""){$display_profesion_ficha_1=" display:none !important; ";
				$PRINCIPAL = str_replace("{INFORMACION_FORMACION_ACADEMICA_ACTUALIZADA}", 		$sininformacion, $PRINCIPAL);
				} else {
				$PRINCIPAL = str_replace("{INFORMACION_FORMACION_ACADEMICA_ACTUALIZADA}", 		$informacion_actualizada, $PRINCIPAL);
				}
				
					$PRINCIPAL = str_replace("{PROFESION_FICHA_1}", 		utf8_encode($Resp_2[0]->idc01), $PRINCIPAL);
					$PRINCIPAL = str_replace("{UNIVERSIDAD_FICHA_1}", 	utf8_encode($Resp_2[0]->idc02), $PRINCIPAL);
					$PRINCIPAL = str_replace("{DISPLAY_PROFESION_FICHA_1}", 	utf8_encode($display_profesion_ficha_1), $PRINCIPAL);
				
			if($Resp_2[0]->idc03==""){$display_profesion_ficha_2=" display:none !important; ";}
					$PRINCIPAL = str_replace("{PROFESION_FICHA_2}", 		utf8_encode($Resp_2[0]->idc03), $PRINCIPAL);
					$PRINCIPAL = str_replace("{UNIVERSIDAD_FICHA_2}", 	utf8_encode($Resp_2[0]->idc04), $PRINCIPAL);
					$PRINCIPAL = str_replace("{DISPLAY_PROFESION_FICHA_2}", 	utf8_encode($display_profesion_ficha_2), $PRINCIPAL);

			if($Resp_2[0]->idc05==""){$display_postgrado_ficha_1=" display:none !important; ";}
					$PRINCIPAL = str_replace("{POSTGRADO_FICHA_1}", 		utf8_encode($Resp_2[0]->idc05), $PRINCIPAL);
					$PRINCIPAL = str_replace("{UNIVERSIDAD_POSTGRADO_FICHA_1}", 	utf8_encode($Resp_2[0]->idc06), $PRINCIPAL);
					$PRINCIPAL = str_replace("{DISPLAY_POSTGRADO_FICHA_1}", 	utf8_encode($display_postgrado_ficha_1), $PRINCIPAL);

			if($Resp_2[0]->idc07==""){$display_postgrado_ficha_2=" display:none !important; ";}
					$PRINCIPAL = str_replace("{POSTGRADO_FICHA_2}", 		utf8_encode($Resp_2[0]->idc07), $PRINCIPAL);
					$PRINCIPAL = str_replace("{UNIVERSIDAD_POSTGRADO_FICHA_2}", 	utf8_encode($Resp_2[0]->idc08), $PRINCIPAL);
					$PRINCIPAL = str_replace("{DISPLAY_POSTGRADO_FICHA_2}", 	utf8_encode($display_postgrado_ficha_2), $PRINCIPAL);

			if($Resp_2[0]->idc09==""){$display_idioma_ficha_1=" display:none !important; ";}
		$PRINCIPAL = str_replace("{IDIOMA_FICHA_1}", 		utf8_encode($Resp_2[0]->idc09), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NIVEL_IDIOMA_FICHA_1}", 	utf8_encode($Resp_2[0]->idc10), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_IDIOMA_FICHA_1}", 	utf8_encode($display_idioma_ficha_1), $PRINCIPAL);

		if($Resp_2[0]->idc11==""){$display_idioma_ficha_2=" display:none !important; ";}
		$PRINCIPAL = str_replace("{IDIOMA_FICHA_2}", 		utf8_encode($Resp_2[0]->idc11), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NIVEL_IDIOMA_FICHA_2}", 	utf8_encode($Resp_2[0]->idc12), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_IDIOMA_FICHA_2}", 	utf8_encode($display_idioma_ficha_2), $PRINCIPAL);
		
		
		$id_form="4";
		$Resp_4=Potencial_tbl_formularios_respuestas_data($id_form, $rut_col);		
		$fecha_4=Potencial_tbl_formularios_respuestas_data_ultimaFecha($id_form, $rut_col);
			$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al 
			".FechaDDMMYYYY($fecha_4)."</div>";

			if($Resp_4[0]->idc01==""){$display_cargo_anterior_ficha_1=" display:none !important; ";
				$PRINCIPAL = str_replace("{INFORMACION_TRAYECTORIA_LABORAL_ACTUALIZADA}", 		$sininformacion, $PRINCIPAL);
				} else {
				$PRINCIPAL = str_replace("{INFORMACION_TRAYECTORIA_LABORAL_ACTUALIZADA}", 		$informacion_actualizada, $PRINCIPAL);
				}
		
		if($Resp_4[0]->idc01==""){$display_cargo_anterior_ficha_1=" display:none !important; ";}		
		$PRINCIPAL = str_replace("{CARGO_ANTERIOR_FICHA_1}", 		utf8_encode($Resp_4[0]->idc01), $PRINCIPAL);
		if($Resp_4[0]->idc02==""){$Resp_4[0]->idc02="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{EMPRESA_ANTERIOR_FICHA_1}", 	utf8_encode($Resp_4[0]->idc02), $PRINCIPAL);
		if($Resp_4[0]->idc03==""){$Resp_4[0]->idc03="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_ANTERIOR_FICHA_1}", 	utf8_encode($Resp_4[0]->idc03), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_CARGO_ANTERIOR_FICHA_1}", 	utf8_encode($display_cargo_anterior_ficha_1), $PRINCIPAL);

		if($Resp_4[0]->idc04==""){$display_cargo_anterior_ficha_2=" display:none !important; ";}		
		$PRINCIPAL = str_replace("{CARGO_ANTERIOR_FICHA_2}", 		utf8_encode($Resp_4[0]->idc04), $PRINCIPAL);
		if($Resp_4[0]->idc05==""){$Resp_4[0]->idc05="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{EMPRESA_ANTERIOR_FICHA_2}", 	utf8_encode($Resp_4[0]->idc05), $PRINCIPAL);
		if($Resp_4[0]->idc06==""){$Resp_4[0]->idc06="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_ANTERIOR_FICHA_2}", 	utf8_encode($Resp_4[0]->idc06), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_CARGO_ANTERIOR_FICHA_2}", 	utf8_encode($display_cargo_anterior_ficha_2), $PRINCIPAL);

		if($Resp_4[0]->idc07==""){$display_cargo_anterior_ficha_3=" display:none !important; ";}		
		$PRINCIPAL = str_replace("{CARGO_ANTERIOR_FICHA_3}", 		utf8_encode($Resp_4[0]->idc07), $PRINCIPAL);
		if($Resp_4[0]->idc08==""){$Resp_4[0]->idc08="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{EMPRESA_ANTERIOR_FICHA_3}", 	utf8_encode($Resp_4[0]->idc08), $PRINCIPAL);
		if($Resp_4[0]->idc09==""){$Resp_4[0]->idc09="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_ANTERIOR_FICHA_3}", 	utf8_encode($Resp_4[0]->idc09), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_CARGO_ANTERIOR_FICHA_3}", 	utf8_encode(($display_cargo_anterior_ficha_3)), $PRINCIPAL);



		$id_form="5";
		$Resp_5=Potencial_tbl_formularios_respuestas_data($id_form, $rut_col);		
		$fecha_5=Potencial_tbl_formularios_respuestas_data_ultimaFecha($id_form, $rut_col);

			$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al 
			".FechaDDMMYYYY($fecha_5)."</div>";

			if($Resp_5[0]->idc01==""){$display_cargo_anterior_ficha_1=" display:none !important; ";
				$PRINCIPAL = str_replace("{INFORMACION_PROYECTOS_ACTUALIZADA}", 		$sininformacion, $PRINCIPAL);
				} else {
				$PRINCIPAL = str_replace("{INFORMACION_PROYECTOS_ACTUALIZADA}", 		$informacion_actualizada, $PRINCIPAL);
				}
		
		if($Resp_5[0]->idc01==""){$display_proyecto_ficha_1=" display:none !important; ";}			
		$PRINCIPAL = str_replace("{PROYECTO_FICHA_1}", 							utf8_encode($Resp_5[0]->idc01), $PRINCIPAL);
		if($Resp_5[0]->idc02==""){$Resp_5[0]->idc02="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{ROL_PROYECTO_FICHA_1}", 					utf8_encode($Resp_5[0]->idc02), $PRINCIPAL);
		if($Resp_5[0]->idc03==""){$Resp_5[0]->idc03="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_PROYECTO_FICHA_1}", 			utf8_encode($Resp_5[0]->idc03), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_PROYECTO_FICHA_1}", 			utf8_encode($display_proyecto_ficha_1), $PRINCIPAL);
		
		if($Resp_5[0]->idc04==""){$display_proyecto_ficha_2=" display:none !important; ";}			
		$PRINCIPAL = str_replace("{PROYECTO_FICHA_2}", 							utf8_encode($Resp_5[0]->idc04), $PRINCIPAL);
		if($Resp_5[0]->idc05==""){$Resp_5[0]->idc05="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{ROL_PROYECTO_FICHA_2}", 					utf8_encode($Resp_5[0]->idc05), $PRINCIPAL);
		if($Resp_5[0]->idc06==""){$Resp_5[0]->idc06="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_PROYECTO_FICHA_2}", 			utf8_encode($Resp_5[0]->idc06), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_PROYECTO_FICHA_2}", 			utf8_encode($display_proyecto_ficha_2), $PRINCIPAL);

		if($Resp_5[0]->idc07==""){$display_proyecto_ficha_3=" display:none !important; ";}			
		$PRINCIPAL = str_replace("{PROYECTO_FICHA_3}", 							utf8_encode($Resp_5[0]->idc07), $PRINCIPAL);
		if($Resp_5[0]->idc08==""){$Resp_5[0]->idc08="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{ROL_PROYECTO_FICHA_3}", 					utf8_encode($Resp_5[0]->idc08), $PRINCIPAL);
		if($Resp_5[0]->idc09==""){$Resp_5[0]->idc09="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_PROYECTO_FICHA_3}", 			utf8_encode($Resp_5[0]->idc09), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_PROYECTO_FICHA_3}", 			utf8_encode($display_proyecto_ficha_3), $PRINCIPAL);

		if($Resp_5[0]->idc10==""){$display_proyecto_ficha_4=" display:none !important; ";}			
		$PRINCIPAL = str_replace("{PROYECTO_FICHA_4}", 							utf8_encode($Resp_5[0]->idc10), $PRINCIPAL);
		if($Resp_5[0]->idc11==""){$Resp_5[0]->idc11="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{ROL_PROYECTO_FICHA_4}", 					utf8_encode($Resp_5[0]->idc11), $PRINCIPAL);
		if($Resp_5[0]->idc12==""){$Resp_5[0]->idc12="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_PROYECTO_FICHA_4}", 			utf8_encode($Resp_5[0]->idc12), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_PROYECTO_FICHA_4}", 			utf8_encode($display_proyecto_ficha_4), $PRINCIPAL);

		if($Resp_5[0]->idc13==""){$display_proyecto_ficha_5=" display:none !important; ";}			
		$PRINCIPAL = str_replace("{PROYECTO_FICHA_5}", 							utf8_encode($Resp_5[0]->idc13), $PRINCIPAL);
		if($Resp_5[0]->idc14==""){$Resp_5[0]->idc14="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{ROL_PROYECTO_FICHA_5}", 					utf8_encode($Resp_5[0]->idc14), $PRINCIPAL);
		if($Resp_5[0]->idc15==""){$Resp_5[0]->idc15="<span style='font-family: nunito_sans_regular;'>Sin informaci&oacute;n</span>";}
		$PRINCIPAL = str_replace("{PERIODO_PROYECTO_FICHA_5}", 			utf8_encode($Resp_5[0]->idc15), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_PROYECTO_FICHA_5}", 			utf8_encode($display_proyecto_ficha_5), $PRINCIPAL);


				
		$id_form="3";
		$Resp_3=Potencial_tbl_formularios_respuestas_data($id_form, $rut_col);
        //print_r($Resp_3);
		$fecha_3=Potencial_tbl_formularios_respuestas_data_ultimaFecha($id_form, $rut_col);

			$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($fecha_3)."</div>";

			if($Resp_3[0]->idc01==""){
				//echo "<h1>a</h1>";
				$display_cargo_recomendacion_ficha_1=" display:none !important; ";
						if($Resp_3[0]->idc07<>""){
							//echo "<h1>b</h1>";
							$texto_mostrar_potencial="<div class='bullet_lightBlue'></div>  Plan de Desarrollo: 
							<br><div class='c-brand-weight'  style='color:#002464;text-align: justify;padding-left:0px !important'>".utf8_encode($Resp_3[0]->idc07)."</div>";
						} else {
							//echo "<h1>c</h1>";
							$texto_mostrar_potencial=$sininformacion;
                            //$texto_mostrar_potencial="A";
						}
							
				
				$PRINCIPAL = str_replace("{INFORMACION_POTENCIAL_ACTUALIZADA}", 		$texto_mostrar_potencial, $PRINCIPAL);
			} else {
					//echo "<h1>d</h1>";
						if($Resp_3[0]->idc07<>""){
							//echo "<h1>e</h1>";
							$texto_mostrar_potencial="<div class='bullet_lightBlue'></div>  Plan de Desarrollo: 
							<br><div class='c-brand-weight' style='color:#002464;text-align: justify; padding-left:0px !important'>".utf8_encode($Resp_3[0]->idc07)."</div>";
						} else {
							//echo "<h1>f</h1>";
							$texto_mostrar_potencial=$sininformacion;
                            $texto_mostrar_potencial="";
                            //$texto_mostrar_potencial="";
						}				
				
				$PRINCIPAL = str_replace("{INFORMACION_POTENCIAL_ACTUALIZADA}", 		$texto_mostrar_potencial, $PRINCIPAL);
				}


		$PRINCIPAL = str_replace("{DISPLAY_POTENCIAL_FICHA_1}", 		utf8_encode($display_cargo_recomendacion_ficha_1), $PRINCIPAL);

		$PRINCIPAL = str_replace("{RECOMENDACION_POTENCIAL_FICHA_1}", 		    utf8_encode($Resp_3[0]->idc01), $PRINCIPAL);
		$PRINCIPAL = str_replace("{RECOMENDACION_PERIODO_FICHA_1}", 		    utf8_encode($Resp_3[0]->idc02), $PRINCIPAL);
		$PRINCIPAL = str_replace("{RECOMENDACION_OBSERVACIONES_FICHA_1}", 		utf8_encode($Resp_3[0]->idc03), $PRINCIPAL);				


//analizo cantidad
		//print_r($Lista_Potencial);	

		//print_r($Lista_Potencial);	
		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "sucesor");
		$Pot_Count_1=count($Lista_Potencial);
		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "sucesor_potencial");
		$Pot_Count_2=count($Lista_Potencial);
		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "reemplazo");
		$Pot_Count_3=count($Lista_Potencial);
		$sumaPot=$Pot_Count_1+$Pot_Count_2+$Pot_Count_3;	
				if($sumaPot>0 and $Pot_Count_1===0){
					
                    $row_sucesores.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: flow-root;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> Sucesor
													</span>
													<div class='bullet_lightwhite'></div> 
														Reclutamiento
														</div>
												</div>
												";
			        $row_sucesores_print.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> Sucesor
													</span>
													<div class='bullet_lightwhite'></div> 
														Reclutamiento
													</div>
												</div>
												";						
					
					
				}

		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "sucesor");
		//print_r($Lista_Potencial);	
		$Pot_Count_1=count($Lista_Potencial);
		//echo "count Pot_Count_1 ".$Pot_Count_1;
		
		foreach ($Lista_Potencial as $unico){
			$Usua=DatosUsuario_($unico->rut_col, $_SESSION["id_empresa"]);
			$foto_avatar_sucesor = VerificaFotoPersonal($unico->rut_col);
			if($unico->tipo=="reemplazo")						{$TxtTipo="Reemplazo Temporal";}
			if($unico->tipo=="sucesor")							{$TxtTipo="Sucesor";}
			if($unico->tipo=="sucesor_potencial")		        {$TxtTipo="Sucesor Potencial";}
			
			
			$coma="";if(Potencial_nomenclatura($Usua[0]->cargo)<>""){ $coma=", ";}
			if($Usua[0]->nombre_completo<>""){
			
				$row_sucesores.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: flow-root;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div> 
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo." $coma ".Potencial_nomenclatura($Usua[0]->cargo)."
														</div>
												</div>
												";
				$row_sucesores_print.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div> 
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo."
														</div>
												</div>
												";			
			}									
		}
		

		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "sucesor_potencial");

		$Pot_Count_2=count($Lista_Potencial);
		//echo "count Pot_Count_2 ".$Pot_Count_2;
		
		//print_r($Lista_Potencial);	
		foreach ($Lista_Potencial as $unico){
			$Usua=DatosUsuario_($unico->rut_col, $_SESSION["id_empresa"]);
			$foto_avatar_sucesor = VerificaFotoPersonal($unico->rut_col);
			if($unico->tipo=="reemplazo")						{$TxtTipo="Reemplazo Temporal";}
			if($unico->tipo=="sucesor")							{$TxtTipo="Sucesor";}
			if($unico->tipo=="sucesor_potencial")		{$TxtTipo="Sucesor Potencial";}
			
			$coma="";if(Potencial_nomenclatura($Usua[0]->cargo)<>""){ $coma=", ";}
			if($Usua[0]->nombre_completo<>""){
			
				$row_sucesores.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: flow-root;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div>
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo."$coma ".Potencial_nomenclatura($Usua[0]->cargo)."
														</div>
												</div>
												";
												
				$row_sucesores_print.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div>
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo."
														</div>
												</div>
												";	
			}											
		}

				
		$Lista_Potencial=Potencial_tbl_potencial_sucesion_tipo($rut_col, "reemplazo");
		//print_r($Lista_Potencial);	
		$Pot_Count_3=count($Lista_Potencial);
		//echo "count Pot_Count_3 ".$Pot_Count_3;		
		foreach ($Lista_Potencial as $unico){
			$Usua=DatosUsuario_($unico->rut_col, $_SESSION["id_empresa"]);
			$foto_avatar_sucesor = VerificaFotoPersonal($unico->rut_col);
		
			if($unico->tipo=="reemplazo")						{$TxtTipo="Reemplazo Temporal";}
			if($unico->tipo=="sucesor")							{$TxtTipo="Sucesor";}
			if($unico->tipo=="sucesor_potencial")		{$TxtTipo="Sucesor Potencial";}
			
			$coma="";if(Potencial_nomenclatura($Usua[0]->cargo)<>""){ $coma=", ";}
		if($Usua[0]->nombre_completo<>""){
			$row_sucesores.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: flow-root;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div>
													    <img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													    ".$Usua[0]->nombre_completo."$coma ".Potencial_nomenclatura($Usua[0]->cargo)."
													 </div>
												</div>
												";
			$row_sucesores_print.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
													<span style='display: inline;margin-top: 10px;font-weight: 600;color: #192857;font-size: 14px;  '>
													<div class='bullet_lightBlue'></div> ".$TxtTipo."
													</span>
													<div class='bullet_lightwhite'></div>
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo."
														</div>
												</div>
												";												
			}
		}

    $Lista_Potencial_Suceder=Potencial_tbl_potencial_a_suceder_tipo($rut_col);
    //print_r($Lista_Potencial_Suceder);
    $Pot_Count_3=count($Lista_Potencial);
    //echo "count Pot_Count_3 ".$Pot_Count_3;
    foreach ($Lista_Potencial_Suceder as $unico){
        //echo "<br>-> ".$unico->rut_col;
        $Usua=DatosUsuario_($unico->rut_col, $_SESSION["id_empresa"]);
        $foto_avatar_sucesor = VerificaFotoPersonal($unico->rut_col);


        $coma="";if(Potencial_nomenclatura($Usua[0]->silla_cargo)<>""){ $coma=", ";}
        if($Usua[0]->nombre_completo<>""){
            $row_cargos_a_suceder.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
											
													<div class='bullet_lightwhite'></div>
													    <img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													    ".$Usua[0]->nombre_completo."$coma ".Potencial_nomenclatura($Usua[0]->silla_cargo)."
													 </div>
												</div>
												";
            $row_cargos_a_suceder_print.="<div class='row'>
												<div class='col-lg-12 col-xs-12'>
						
													<div class='bullet_lightwhite'></div>
													<img src='".$foto_avatar_sucesor."' style='width: 24px;border-radius: 50%;'> 
													".$Usua[0]->nombre_completo."
														</div>
												</div>
												";
        }
    }


			
				//echo "count sumaPot ".$sumaPot;		
				
				if($sumaPot>0){
					$PRINCIPAL = str_replace("{DISPLAY_NONE_VISTA_CONSOLIDADA}", 	"", $PRINCIPAL);
				} else {
					$PRINCIPAL = str_replace("{DISPLAY_NONE_VISTA_CONSOLIDADA}", 	" display:none !important; ", $PRINCIPAL);
				}


				
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES}", 	$row_sucesores, $PRINCIPAL);
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES_PRINT}", 	$row_sucesores_print, $PRINCIPAL);

    $PRINCIPAL = str_replace("{LISTA_ROW_CARGOS_A_SUCEDER}", 	$row_cargos_a_suceder, $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_ROW_CARGOS_A_SUCEDER_PRINT}", 	$row_cargos_a_suceder_print, $PRINCIPAL);


//echo "<br>row sucesores .".$row_sucesores.".";
		$sininformacion="<div class='txt_sin_informacion'>Sin Información</div>";

		if($row_sucesores==""){
//echo "<h1>a</h1>";
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES}", 	$sininformacion, $PRINCIPAL);
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES_PRINT}", 	$sininformacion, $PRINCIPAL);
		$PRINCIPAL = str_replace("{INFORMACION_SUCESOR_ACTUALIZADA}", 	$sininformacion, $PRINCIPAL);

			
		} else {
//echo "<h1>b</h1>";	
			//$fecha_sucesion="2021-07-05";
			$Fecha_Suc=Potencial_BuscaFechaUltima($rut_col);
		$informacion_actualizada="<div class='txt_actualizada_al'>Informaci&oacute;n Actualizada al ".FechaDDMMYYYY($Fecha_Suc)."</div>";

		
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES}", 	$row_sucesores, $PRINCIPAL);
		$PRINCIPAL = str_replace("{LISTA_ROW_SUCESORES_PRINT}", 	$row_sucesores_print, $PRINCIPAL);
		$PRINCIPAL = str_replace("{INFORMACION_SUCESOR_ACTUALIZADA}", 	$informacion_actualizada, $PRINCIPAL);		
		}



    return ($PRINCIPAL);			
	
}

function Potencial_Talento_Sucesion_vista($PRINCIPAL, $rut){
	
		
		$Arreglo_Vistas=Potencial_Talento_Sucesores_vista_cerrados($rut);
		//print_r($Arreglo_Vistas);
		
		foreach ($Arreglo_Vistas as $unico){
			
		$uno=Encodear3("1");
			
		$Usu=DatosUsuario_($unico->rut_suceder,  $_SESSION["id_empresa"]);			
		$row_data_tables_cerrados.="
				<tr>
						<td>".Potencial_nomenclatura($Usu[0]->cargo)."</td>
						<td>".$Usu[0]->nombre_completo."</td>
						<td>".$Usu[0]->division."</td>
						<td><a href='?sw=home_talento_sucesion&rut_enc1=".Encodear4($unico->rut_suceder)."&vista=".$uno."' class='btn secondary'>Ver Mapa</a></td>
				</tr>";			
		
		}
		

	
		$PRINCIPAL = str_replace("{ROW_DATA_CADA_SUCESION_CERRADA}", 	$row_data_tables_cerrados, $PRINCIPAL);
    return ($PRINCIPAL);		
	
}
function Talento_Divisiones_Options($PRINCIPAL, $rut){
		$Div=Talento_Divisiones_Options_data($_SESSION["id_empresa"]);
		//print_r($Div);
		
		foreach ($Div as $unico){
			
				$options_div.="<option value='".$unico->division."'>".$unico->division."</option>";		
		
		}
		

	
		$PRINCIPAL = str_replace("{OPTIONS_DIVISIONES}", 	$options_div, $PRINCIPAL);
    return ($PRINCIPAL);	
	
}
function Potencial_Talento_Sucesion_vista_fichas($PRINCIPAL, $rut, $division){
	
				$Arreglo_Vistas=Potencial_Talento_lista_vista_divisiones($division);
				//print_r($Arreglo_Vistas);
		
				foreach ($Arreglo_Vistas as $unico){
					
						$Usu=DatosUsuario_($unico->rut,  $_SESSION["id_empresa"]);			
						
						
						if($Usu[0]->nombre_completo==""){
							continue;
						} else {
							$cuenta_linea_vista++;
						}
						$row_data_tables.="
						<tr>
								<td><input type='checkbox' name='".$cuenta_linea_vista."' id='".$cuenta_linea_vista."' value='".Encodear4($unico->rut)."'> ".$Usu[0]->nombre_completo."</td>
								<td>".$Usu[0]->cargo."</td>
								<td>".$Usu[0]->division."</td>
								<td><a href='?sw=home_talento_colaborador_ficha&rut_enc=".Encodear4($unico->rut)."&vista=".$uno."' class='btn secondary'>Ver Ficha</a></td>
						</tr>";			
				
				}
		

	
		$PRINCIPAL = str_replace("{ROW_DATA_CADA_COLABORADOR_DIVISION}", 	$row_data_tables, $PRINCIPAL);

		$PRINCIPAL = str_replace("{DIVISION_SELECCIONADA}", 	utf8_encode($division), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DIVISION_SELECCIONADA_ENC}", 	Encodear4($division), $PRINCIPAL);

    return ($PRINCIPAL);		
	
}

function PotencialTalento_Muestra_Home_Perfil($PRINCIPAL, $rut){
	
	
	$perfil=Potencial_Sucesion_BuscaPerfil($rut);
	//echo "perfil $perfil";
	
	if($perfil=="Editor_Visualizador"){
        $row_vista_perfil_editor.= file_get_contents("views/talento_2021/row_vista_editor.html");
        $row_vista_perfil_editor = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_editor);		
        $row_vista_perfil_editor = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_editor);		
        $row_vista_perfil_vis.= file_get_contents("views/talento_2021/row_vista_visualizador.html");
        $row_vista_perfil_vis = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_vis);		
        $row_vista_perfil_vis = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_vis);		

	} elseif($perfil=="Editor"){
        $row_vista_perfil_editor.= file_get_contents("views/talento_2021/row_vista_editor.html");
        $row_vista_perfil_editor = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_editor);		
        $row_vista_perfil_editor = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_editor);	
        $row_vista_perfil_vis="";			
	}
		elseif($perfil=="Visualizador"){
        $row_vista_perfil_vis.= file_get_contents("views/talento_2021/row_vista_visualizador.html");
        $row_vista_perfil_vis = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_vis);		
        $row_vista_perfil_vis = str_replace("{MEDALLAS}", $medallas, $row_vista_perfil_vis);		
        $row_vista_perfil_editor="";
	}
	
		$PRINCIPAL = str_replace("{VISTA_HOME_SEGUN_PERFIL_EDITOR}", 				$row_vista_perfil_editor, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VISTA_HOME_SEGUN_PERFIL_VISUALIZADOR}", 	$row_vista_perfil_vis, $PRINCIPAL);
    return ($PRINCIPAL);	
	
}



function MiEquipo2020_Colaboradores_equipo_consolidado($PRINCIPAL, $id_empresa, $rut, $miperfil)
{
	
	$boton_img_="";
	
	$periodo="2020";

							if($rut==$_SESSION["user_"]){
							
								$boton_img_="<br><a href='?sw=mi_perfil_2020_imagen' class='btn btn_link_mi_equipo' style='padding-left: 0px;'>Editar mi fotograf&iacute;a</a>";
							} else {
								
								$boton_img_="";
							}

	$boton_img_="";

if($miperfil=="MiPerfil"){
	$listado_colaboradores[0]->rut="$rut";

} else {
	$listado_colaboradores = TotalUsuariosDependientesJefe($rut);

}
	 foreach ($listado_colaboradores as $unico) {
		$array_res[5]=0;
        $array_res=BuscaCountResultadosMiEquipo($unico->rut,$id_empresa);

        $imagen_usuario=VerificaFotoPersonal($unico->rut);
        //echo "<br>imagen usuario <br>$imagen_usuario<br>";
        $rut_enc=Encodear3($unico->rut);
					if($array_res[5]>0){
						
						if($miperfil=="MiPerfil"){

							
							$boton_ver_equipo	=	"<a href='?sw=mi_equipo_consolidado_2020' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Equipo</a>
							
							
							";
						} else {
							$boton_ver_equipo	=	"<a href='?sw=mi_equipo_consolidado_2020&r_enc=".$rut_enc."' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Equipo</a><br><br>";	
						}
						
						
					} else {
						$boton_ver_equipo	=	"";
					}

//echo "rut $rut sesion ".$_SESSION["user_"];

if($unico->rut==$_SESSION["user_"]){
	
					//	echo "<h1>A</h1>";
						
	$row.=
				        			"
												<li class='contact'>
													
														<div class='wrap'>
															<span class='contact-status online'></span>
															<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																<img class='img-fluid' src='".$imagen_usuario."' alt='".$unico->nombre_completo."'>
															</a>
															<div class='meta'>
																	<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																		<h5 class='name'>".utf8_encode($unico->nombre_completo)."</h5>
																	</a>
																	<div>
																		<div class='far fa-heart fa-1x icon_mascon' 									title='Agradecimiento'		style='display: inline; display: inline; '></div> 	".$array_res[1]."
																		<div class='icon-star fa-1x icon_mascon pad_left_20'					title='Reconocimiento' 		style='display: inline; display: inline; '></div> 	".$array_res[2]."
																		<div class='icon-badge  fa-1x icon_mascon pad_left_20' 				title='Cursos'		style='display: inline; display: inline; '></div> 	".$array_res[3]."
																		<div class='icon-trophy   fa-1x icon_mascon pad_left_20'			title='Logros'		style='display: inline; display: inline; '></div> 	".$array_res[6]."
																		<div class='far fa-file-alt  fa-1x icon_mascon pad_left_20'		title='Amonestaciones/Recomendaciones'		style='display: inline; display: inline; '></div> ".$array_res[9]."
																			<br>													
																
																			
																			<div class='row' style='    max-width: 411px;'>

																				<div class='col-lg-12 pull-right'>
																							".$boton_img_."
																				</div>

																			
																						
																				<div class='col-lg-12 pull-right'>
																							".$boton_ver_equipo."
																				</div>
																				
																				
																				
																				<div class='col-lg-4'>
																				
																				</div>
	
																			</div>


																	</div>
															</div>
														</div>
												
												</li>
												
				    



						";
	
} else {
	
					//echo "<h1>B</h1>";
					
				//if($rut=="10033383" or $rut=="16886519" or $rut=="15537490" or $rut=="13279216" or $rut=="17085373" or $rut=="15371061" or $rut=="7619983")
				//{//
			

				$row.=
				        			"
												<li class='contact'>
													
														<div class='wrap'>
															<span class='contact-status online'></span>
															<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																<img class='img-fluid' src='".$imagen_usuario."' alt='".$unico->nombre_completo."'>
															</a>
															<div class='meta'>
																	<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																		<h5 class='name'>".utf8_encode($unico->nombre_completo)."</h5>
																	</a>
																	<div>
																		<div class='far fa-heart fa-1x icon_mascon' 									title='Agradecimiento'		style='display: inline; display: inline; '></div> 	".$array_res[1]."
																		<div class='icon-star fa-1x icon_mascon pad_left_20'					title='Reconocimiento' 		style='display: inline; display: inline; '></div> 	".$array_res[2]."
																		<div class='icon-badge  fa-1x icon_mascon pad_left_20' 				title='Cursos'		style='display: inline; display: inline; '></div> 	".$array_res[3]."
																		<div class='icon-trophy   fa-1x icon_mascon pad_left_20'			title='Logros'		style='display: inline; display: inline; '></div> 	".$array_res[6]."
																		<div class='far fa-file-alt  fa-1x icon_mascon pad_left_20'		title='Amonestaciones/Recomendaciones'		style='display: inline; display: inline; '></div> ".$array_res[9]."
																			<br>													
																
																			
																			<div class='row' style='    max-width: 411px;'>
			
																				<div class='col-lg-12 pull-right'>
																				
																							".$boton_ver_equipo."
																				
																				</div>

																			<div class='col-lg-4'>
																					<a href='?sw=com_vota_reconoce_home_form_agradecer_2020&pf=WS3EncP2$66&colsend=".Encodear5($unico->rut)."' style='display: inline; '>
																						<div class='btn btn-info btn-sm' style='margin-top: 5px;'> Agradecer <i class='far fa-heart'></i> </div>
																				 </a>
																			</div>
			
																			<div class='col-lg-4'>
																						<a href='?sw=com_vota_reconoce_home_form_amonestacion_2020&pf=WS3EncP2$66&colsend=".Encodear5($unico->rut)."' style='display: inline; '>
																							<div class='btn btn-info btn-sm' style='margin-top: 5px; width: 200px;'> Amonestar / Recomendar <i class='far fa-file-alt'></i> </div>
																					 </a>																
																				</div>

																			</div>

																		


																	</div>
															</div>
														</div>
												
												</li>
												
				    



						";
					
/*
				

				$row.=
				        			"
												<li class='contact'>
													
														<div class='wrap'>
															<span class='contact-status online'></span>
															<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																<img class='img-fluid' src='".$imagen_usuario."' alt='".$unico->nombre_completo."'>
															</a>
															<div class='meta'>
																	<a href='?sw=mi_perfil_2020&r_enc=".Encodear3($unico->rut)."' style='display: inline;'>
																		<h5 class='name'>".$unico->nombre_completo."</h5>
																	</a>
																	<div>
																		<div class='far fa-heart fa-1x icon_mascon' 									title='Agradecimiento'		style='display: inline; display: inline; '></div> 	".$array_res[1]."
																		<div class='icon-star fa-1x icon_mascon pad_left_20'					title='Reconocimiento' 		style='display: inline; display: inline; '></div> 	".$array_res[2]."
																		<div class='icon-badge  fa-1x icon_mascon pad_left_20' 				title='Cursos'		style='display: inline; display: inline; '></div> 	".$array_res[3]."
																		<div class='icon-trophy   fa-1x icon_mascon pad_left_20'			title='Logros'		style='display: inline; display: inline; '></div> 	".$array_res[6]."
																				<br>													
																			<br>													
																		<div class='row' style='    max-width: 411px;'>
																				
																				<div class='col-lg-4'>
																				
																			<a href='?sw=com_vota_reconoce_home_form_agradecer_2020&pf=WS3EncP2$66&colsend=".Encodear5($unico->rut)."' style='display: inline; '>
																				<div class='btn btn-info btn-sm' style='margin-top: 5px;'> Agradecer <i class='far fa-heart'></i> </div>
																		 </a>
																				
																				
																				</div>
																				<div class='col-lg-4'>
																				
																							".$boton_ver_equipo."
																				
																				</div>
																			</div>
																	</div>
															</div>
														</div>
												
												</li>
												
				    


						";
					*/
			
		
 }       

				$boton_ver_mas		=	"<a href='?sw=mi_equipo_consolidado_ficha&r_enc=".$rut_enc."' class='btn btn-info'>Ver Ficha Personal</a>";
	      $row = str_replace("{VER_EQUIPO}",   $boton_ver_equipo, $row);
	      $row = str_replace("{VER_MAS}",   $boton_ver_mas, $row);
        if($array_res[4]>0){
					 $array4= $array_res[4]+ $array4;
      	   $cuenta4++;
         }
        $array6=$array_res[6]+$array6;		 
				$array3=$array_res[3]+$array3;
        $array2=$array_res[2]+$array2;
        $array1=$array_res[1]+$array1;
        $array9=$array_res[9]+$array9;		 
	 }
     if($cuenta4>0){
         $array4_avg=round($array4/$cuenta4,2);
     }

    $PRINCIPAL = str_replace("{PROM_DESEMPENO}",        $array4_avg, $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_AGRA_RECIBIDOS}", $array1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_RECO_RECIBIDOS}", $array2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_CURSOS_REALIZADOS}", $array3, $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_LOGROS}", $array6, $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_AMONESTACIONES}", $array9, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RANGO1}", $rango1, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RANGO2}", $rango2, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RANGO3}", $rango3, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RANGO4}", $rango4, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RANGO5}", $rango5, $PRINCIPAL);
    return ($PRINCIPAL);
}






function colocarAgraRecoPorRut_MiEquipo($PRINCIPAL, $id_empresa, $rut)
{   
	
  $date_hace_12_meses = date("Y-m-d", strtotime(" -12 months"));
    //echo $date;
		//echo "rut $rut, id_empresa $id_empresa";

$array_agradecimiento=Busca_Reco_MiEquipo("GRACIAS", $id_empresa, $rut);
//print_r($array_agradecimiento);
if(count($array_agradecimiento)>0){
    foreach ($array_agradecimiento as $unico) {

        //echo "<br>Destinatario ".$unico->rut_destinatario." Remitente ".$unico->rut_remitente." Rut Sesion ".$rut;

        $row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<div class='far fa-heart fa-1x icon_mascon' title='Agradecimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="Agradecimiento Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<div class='far fa-heart fa-1x icon_mascon' title='Agradecimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="Agradecimiento - ".utf8_encode($unico->categorita);
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
		if($Usua[0]->nombre_completo==""){
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
		$row = str_replace("{ICO_RECO}", 		$ico_reco, $row);
        $row = str_replace("{TIPO_RECO}", 		$tipo_reco, $row);
        $row = str_replace("{CLASS_RECO}", 		$class_reco, $row);
		$row = str_replace("{FECHA_RECO}", 		$unico->fecha, $row);
        $row = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row);			
		$row = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row); 	
		$row = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row);		
		
	}
} else {
	$row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row = str_replace("{TEXTO_SIN}", 		"<br>Sin Agradecimientos<br>", $row);	
}
$array_reco=Busca_Reco_MiEquipo("RECONOCIMIENTO", $id_empresa, $rut);
//print_r($array_reco);
//echo "cuenta ".count($array_reco);
if(count($array_reco)>0){

    foreach ($array_reco as $unico) {		
        $row2 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		="Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;

			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				
				if($unico->categorita=="SELECCION DEL CHILE"){
					$tipo_reco="Selección del Chile";
				} elseif($unico->categorita=="COLABORACION"){
					$tipo_reco="Colaboración";
				} else {
					$tipo_reco="";
				}
				
				$ico_reco		="<div class='icon-star fa-1x icon_mascon' title='Reconocimiento' style='display: inline; display: inline; '></div>";	
				$tipo_reco		="Reconocimiento - ".($tipo_reco);
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
		
			
			
		
		$row2 = str_replace("{ICO_RECO}", 		$ico_reco, $row2);
        $row2 = str_replace("{TIPO_RECO}", 		$tipo_reco, $row2);
        $row2 = str_replace("{CLASS_RECO}", 		$class_reco, $row2);
		$row2 = str_replace("{FECHA_RECO}", 		$unico->fecha, $row2);
        $row2 = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row2);			
		$row2 = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row2); 	
		$row2 = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row2);			
	}
	
} else {
	$row2 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row2 = str_replace("{TEXTO_SIN}", 		"<br>Sin Reconocimientos<br>", $row2);
}

$array_AMON=Busca_Reco_MiEquipo("AMONESTACION", $id_empresa, $rut);
//print_r($array_AMON);

if(count($array_AMON)>0){
    foreach ($array_AMON as $unico) {		
    	
    	
    	//echo "<br>".$date_hace_12_meses." ".$unico->fecha;
    	if($date_hace_12_meses > $unico->fecha){ continue;}
    	
        $row3 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_amon.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		=utf8_encode($unico->categorita);
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<div class='far fa-file-alt  fa-1x icon_mascon' title='Amonestacion/Recomendaciones' style='display: inline; display: inline; '></div>";	
				$tipo_reco		=utf8_encode($unico->categorita);
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		
	
	

		
		if($unico->acuse_recibo<>""){
			$acuse_recibdo="
			
					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>
			
			
			<div class='alert alert-info'><center>Acuse de recibo realizado</center></div>";
		} else {
			
			if($rut==$_SESSION["user_"]){
$acuse_recibdo="

					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>

			<div class='alert alert-warning'><center>Acuse de recibo pendiente</center>
			<br>
			<center>
				<a href='?sw=mi_perfil_2020&acr=".Encodear3($unico->id)."' class='btn btn-info'>Acusar Recibo</a>
			</center>
			<br>
			<p>
                En caso que presente dudas por favor tome contacto con su jefatura y/o gestor de personas.<br>
                <strong>CONSECUENCIAS</strong>: La recomendación y/o amonestación cursada, como a su vez la ponderación en el análisis de decisiones respecto a la sanción notificada en este acto (tales como concursos internos, traslados, promociones, aumentos voluntarios de remuneración o término de contrato laboral), se encuentra establecida en el Reglamento Interno de Orden, Higiene y Seguridad, específicamente en su título décimo noveno, Artículo 99 y siguientes del citado ejemplar.			
            </p>
			
			</div>";				
			} else {
	$acuse_recibdo="

					<BR><span class='body'> ".utf8_encode($unico->mensaje)." </span>
			<br><span><i class='far fa-calendar-alt'></i> ".$unico->fecha."</span>
			<br>

			<div class='alert alert-warning'><center>Acuse de recibo pendiente</center>

			
			</div>";			
			}
			//echo "rut $rut session".$_SESSION["user_"];
			
			
		}
		
		$Usua=DatosUsuario_($rut_reco, $id_empresa);
		
		if($Usua[0]->id>0){
		} else {
			$Usua=DatosUsuario_historicos_($rut_reco, $id_empresa);
		}
					$row3 = str_replace("{ICO_RECO}", 		$ico_reco, $row3);
        $row3 = str_replace("{TIPO_RECO}", 		$tipo_reco, $row3);
        $row3 = str_replace("{CLASS_RECO}", 		$class_reco, $row3);
		
				$row3 = str_replace("{AVATAR_RECO}", VerificaFotoPersonal($rut_reco), $row3); 	
				$row3 = str_replace("{NOMBRE_RECO}", $Usua[0]->nombre_completo, $row3);		

				$row3 = str_replace("{ACUSE_RECIBO}", 		$acuse_recibdo, $row3);

		
	}
} else {
	$row3 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_amon_sin.html");
	$row3 = str_replace("{TEXTO_SIN}", 		"<br>Sin Amonestaciones<br>", $row3);	
}


    $PRINCIPAL = str_replace("{LISTA_AGRADECIMIENTO}", ($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_AMONESTACIONES}", ($row3), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_RECONOCIMIENTO}", ($row2), $PRINCIPAL);
    return ($PRINCIPAL);
}



function colocarAgraRecoPorRut_MiEquipo_Vista_jefe($PRINCIPAL, $id_empresa, $tipo_show, $jefe)
{   

//echo "rut $rut, id_empresa $id_empresa, tiposhow $tipo_show";

$array_agradecimiento=Busca_Reco_MiEquipo_Vista_jefe("GRACIAS", $id_empresa, $jefe);

if(count($array_agradecimiento)>0){
    foreach ($array_agradecimiento as $unico) {	
	
	
        $row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		="Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<i class='fas fa-indent'></i>";	
				$tipo_reco		="Recibido";
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		$Usua_dest=DatosUsuario_($unico->rut_destinatario, $id_empresa);
		$Usua_remi=DatosUsuario_($unico->rut_remitente, $id_empresa);
		$row = str_replace("{ICO_RECO}", 		$ico_reco, $row);
        $row = str_replace("{TIPO_RECO}", 		$tipo_reco, $row);
        $row = str_replace("{CLASS_RECO}", 		$class_reco, $row);
		$row = str_replace("{FECHA_RECO}", 		$unico->fecha, $row);
        $row = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row);			
		$row = str_replace("{AVATAR_RECO}", 	VerificaFotoPersonal($unico->rut_destinatario), $row); 	
		$row = str_replace("{NOMBRE_RECO}",		"De ".$Usua_remi[0]->nombre_completo." a ".$Usua_dest[0]->nombre_completo, $row);		
		
	}
} else {
	$row .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row = str_replace("{TEXTO_SIN}", 		"<br>Sin Agradecimientos<br>", $row);	
}

$array_reconocimiento=Busca_Reco_MiEquipo_Vista_jefe("RECONOCIMIENTO", $id_empresa, $jefe);

if(count($array_reconocimiento)>0){
    foreach ($array_reconocimiento as $unico) {	
	
	
        $row2.= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco.html");
			if($rut==$unico->rut_remitente){
				$ico_reco		="<i class='fas fa-outdent'></i>";	
				$tipo_reco		="Entregado";
				$class_reco		="primary";
				$rut_reco		=$unico->rut_destinatario;	
				//echo "<br>$rut_reco ".$unico->rut_destinatario;
			} 
			
			elseif($rut==$unico->rut_destinatario){ 
				$ico_reco		="<i class='fas fa-indent'></i>";	
				$tipo_reco		="Recibido";
				$class_reco		="info";
				$rut_reco		=$unico->rut_remitente;			
				//echo "<br>$rut_reco ".$unico->rut_remitente;
			}
		//echo "<br>$rut_reco $ico_reco $tipo_reco $class_reco $nombre_reco	$avatar_reco, RUT $rut, rut_remitente	".$unico->rut_remitente." rut destinatario ".$unico->rut_destinatario;
		$Usua_dest=DatosUsuario_($unico->rut_destinatario, $id_empresa);
		$Usua_remi=DatosUsuario_($unico->rut_remitente, $id_empresa);
		$row2 = str_replace("{ICO_RECO}", 		$ico_reco, $row2);
        $row2 = str_replace("{TIPO_RECO}", 		$tipo_reco, $row2);
        $row2 = str_replace("{CLASS_RECO}", 		$class_reco, $row2);
		$row2 = str_replace("{FECHA_RECO}", 		$unico->fecha, $row2);
        $row2 = str_replace("{MENSAJE_RECO}", 	utf8_encode($unico->mensaje), $row2);			
		$row2 = str_replace("{AVATAR_RECO}", 	VerificaFotoPersonal($unico->rut_destinatario), $row2); 	
		$row2 = str_replace("{NOMBRE_RECO}",		"De ".$Usua_remi[0]->nombre_completo." a ".$Usua_dest[0]->nombre_completo, $row2);		
		
	}
} else {
	$row2 .= file_get_contents("views/mi_equipo_consolidado/" . $id_empresa . "_mi_ficha_row_reco_sin.html");
	$row2 = str_replace("{TEXTO_SIN}", 		"<br>Sin Agradecimientos<br>", $row2);	
}



if($tipo_show=="reconocimientos"){
    $PRINCIPAL = str_replace("{LISTA_AGRADECIMIENTO}", (""), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_RECONOCIMIENTO}", ($row2), $PRINCIPAL);
} 

if($tipo_show=="agradecimientos"){
    $PRINCIPAL = str_replace("{LISTA_AGRADECIMIENTO}", ($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_RECONOCIMIENTO}", (""), $PRINCIPAL);
} 

    return ($PRINCIPAL);
	
}



function colocarReconocimiesBadgesPorRutEquipo($PRINCIPAL, $id_empresa, $rut)
{
    $cuenta_badges = 0;
    $listado       = lista_reconocimientos_badges_por_rut($rut, $id_empresa);
    foreach ($listado as $unico) {

        //print_r($unico);
        $row="hola";


    }
    $PRINCIPAL = str_replace("{LISTA_RECONOCIMIENTOS_PLATAFORMA}", ($row), $PRINCIPAL);
    return ($PRINCIPAL);
}

function colocarlogrosBadgesPorEquipo($PRINCIPAL, $id_empresa, $rut)
{
    $cuenta_badges = 0;
    $listado       = lista_badges_por_rut($rut, $id_empresa);
    // busca usuarios de equipo
    foreach ($listado as $unico) {
        if ($unico->fecha == "") {
            $estilo_badge = " img_grayscale_opacity";
            $texto        = "<br>" . $unico->nombre;
        } else {
            $estilo_badge = "";
            $texto        = "<br>" . $unico->nombre . "<br>" . $unico->fecha;
            $cuenta_badges++;
        }
        $row .= file_get_contents("views/badges/" . $id_empresa . "_lista_badges.html");
        $row = str_replace("{IMAGEN_BADGE}", $unico->imagen, $row);
        $row = str_replace("{ESTILO_IMAGEN_BADGE}", $estilo_badge, $row);
        $row = str_replace("{TEXTO_BADGE}", $texto, $row);
    }
    $PRINCIPAL = str_replace("{LISTA_BADGES_PLATAFORMA}", utf8_encode($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_BADGES}", ($cuenta_badges), $PRINCIPAL);
    return ($PRINCIPAL);
}
function colocarListaEquipoLogros($PRINCIPAL, $id_empresa, $rut)
{
    $listado = lista_PersonasEquipo($rut, $id_empresa);
    foreach ($listado as $unico) {
        $row .= file_get_contents("views/badges/" . $id_empresa . "_row_lista_equipo_logros.html");
        $num_badges = $unico->numbadges + $num_badges;
        $row        = str_replace("{RUT_DESTINATARIO}", $unico->rut, $row);
        $row        = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut), $row, $row);
        $row        = str_replace("{NOMBRE_DESTINATARIO}", utf8_decode($unico->nombre_destinatario), $row);
        $row        = str_replace("{CARGO_DESTINATARIO}", utf8_decode($unico->cargo_destinatario), $row);
        $row        = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row        = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);
        $row        = colocarlogrosBadgesPorRut($row, $id_empresa, $unico->rut);
    }
    $PRINCIPAL = str_replace("{NUM_BADGES}", ($num_badges), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_TABLA_BADGES_RECIBIDOS}", ($row), $PRINCIPAL);
    return ($PRINCIPAL);
}

function colocarListaEquipoReconocimientos($PRINCIPAL, $id_empresa, $rut)
{
    $listado = lista_PersonasReconocidasEquipo($rut, $id_empresa);
    foreach ($listado as $unico) {

        //print_r($unico);
     $row .= file_get_contents("views/reconoce_vota/" . $id_empresa . "_row_lista_equipo_reconocer.html");
        $num_badges = $unico->numbadges + $num_badges;
        $row        = str_replace("{RUT_DESTINATARIO}", $unico->rut, $row);
        $row        = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut), $row, $row);
        $row        = str_replace("{NOMBRE_DESTINATARIO}", utf8_decode($unico->nombre_destinatario), $row);
        $row        = str_replace("{CARGO_DESTINATARIO}", utf8_decode($unico->cargo_destinatario), $row);
        $row        = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row        = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);

    //echo "<br> num_reconocimientos_destinatario".$unico->num_reconocimientos_destinatario;

    $suma_reconocimientos=$unico->num_reconocimientos_destinatario+$suma_reconocimientos;
    $suma_gracias=$unico->num_gracias_destinatario+$suma_gracias;
    //echo "<br>suma_reconocimientos $suma_reconocimientos , unico ".$unico->num_reconocimientos_destinatario;


    $array_badge=lista_reconocimientos_badges_por_rut($unico->rut, $id_empresa);
    //print_r($array_badge);
    $lista_reconocimiento="";
    foreach ($array_badge as $unico2){

         $tipo_reco=""; $estilo_reco=="";
    if ($unico2->tipo == "RECONOCIMIENTO") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <STRONG>Reconocimiento de ".$unico2->remitente."</STRONG><BR>Mensaje:&nbsp;".utf8_encode($unico2->mensaje)."<br>Fecha: ".utf8_encode($unico2->fecha);
            $estilo_reco = "";
        } elseif ($unico2->tipo == "GRACIAS") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i> <STRONG>Agradecimiento de ".$unico2->remitente."</STRONG><BR>Mensaje:&nbsp;".utf8_encode($unico2->mensaje)."<br>Fecha: ".utf8_encode($unico2->fecha);
            $estilo_reco = "";
        }
        if ($unico2->tipo == "RECONOCIMIENTO" and $unico->rut_destinatario == $rut) {
            $tipo_reco   = "";
            $estilo_reco = "info";
        }
        if ($unico2->tipo == "GRACIAS" and $unico->rut_destinatario == $rut) {
            $tipo_reco   = "";
            $estilo_reco = "";
        }

        $lista_reconocimiento.="<br>$tipo_reco ";

    }





    $row        = str_replace("{ROW_RECONOCIMIENTOS_RECIBIDOS}", $lista_reconocimiento, $row);





    }

        $PRINCIPAL = str_replace("{NUM_BADGES}", ($num_badges), $PRINCIPAL);


    //echo "<br>$suma_reconocimientos";
    $PRINCIPAL = str_replace("{NUM_RECO}",     ($suma_reconocimientos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUM_GRAC}",             ($suma_gracias), $PRINCIPAL);

    $PRINCIPAL = str_replace("{ROW_TABLA_RECONOCIMIENTOS_RECIBIDOS}", ($row), $PRINCIPAL);
    return ($PRINCIPAL);
}

function colocarListaReconocimientosPorRut($PRINCIPAL, $id_empresa, $rut)
{
    $listado = TotalReconocimientosGraciasVistaPerfil($rut, $id_empresa, "recibidos");
    foreach ($listado as $unico) {
        $cuenta_reconocimiento_recibidos++;
        if ($unico->tipo == "RECONOCIMIENTO") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <BR>Reconocimiento";
            $estilo_reco = "";
        } elseif ($unico->tipo == "GRACIAS") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i> <BR>Agradecimiento";
            $estilo_reco = "";
        }
        if ($unico->tipo == "RECONOCIMIENTO" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "info";
        }
        if ($unico->tipo == "GRACIAS" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "";
        }
        //    echo "<br>".$tipo_reco;
        $row .= file_get_contents("views/reconoce_vota/" . $id_empresa . "_lista_reconocimientos_perfil_tabla.html");
        $row = str_replace("{ESTILO}", $estilo_reco, $row);
        $row = str_replace("{FECHA}", $unico->fecha, $row);
        $row = str_replace("{TIPO_RECO}", $tipo_reco, $row);
        $row = str_replace("{RUT_REMITENTE}", $unico->rut_remitente, $row);
        $row = str_replace("{AVATAR_REMITENTE}", "multimedia/" . utf8_decode($unico->avatar_remitente), $row);
        $row = str_replace("{NOMBRE_REMITENTE}", $unico->nombre_remitente, $row);
        $row = str_replace("{CARGO_REMITENTE}", $unico->cargo_remitente, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_REMITENTE}", $unico->num_reconocimientos_rut_remitente, $row);
        $row = str_replace("{NUM_GRACIAS_REMITENTE}", $unico->num_gracias_rut_remitente, $row);
        $row = str_replace("{RUT_DESTINATARIO}", $unico->rut_destinatario, $row);
        $row = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut_destinatario), $row, $row);
        $row = str_replace("{NOMBRE_DESTINATARIO}", $unico->nombre_destinatario, $row);
        $row = str_replace("{CARGO_DESTINATARIO}", $unico->cargo_destinatario, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);
        $row = str_replace("{MENSAJE}", ($unico->mensaje), $row);

        if($unico->tipo=="RECONOCIMIENTO"){$icono="<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i>"; $tipotext="Reconocimiento Selecci&oacute;n del Chile de ";}

				elseif($unico->tipo=="COLABORACION") {$icono="<i class='i-plain-sm nobottommargin i-azul icon-star'></i>"; $tipotext="Reconocimiento Colaboraci&oacute;n de ";}
         else {$icono="<i class='i-plain-sm nobottommargin i-azul icon-heart'></i>"; $tipotext="Agradecimiento de ";}
        $row = str_replace("{ICONO}", ($icono), $row);
        $row = str_replace("{TIPOTEXT}", ($tipotext), $row);

    }
    $PRINCIPAL = str_replace("{ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS_PERFIL}", utf8_encode($row), $PRINCIPAL);
    $row       = "";
    $listado   = TotalReconocimientosGraciasVistaPerfil($rut, $id_empresa, "enviados");
    foreach ($listado as $unico) {
        $cuenta_reconocimiento_entregados++;
        if ($unico->tipo == "RECONOCIMIENTO") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <BR>Reconocimiento";
            $estilo_reco = "";
        } 
        
        elseif ($unico->tipo == "COLABORACION") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <BR>Colaboración";
            $estilo_reco = "";
        }
        elseif ($unico->tipo == "GRACIAS") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i> <BR>Agradecimiento";
            $estilo_reco = "";
        }
        if ($unico->tipo == "RECONOCIMIENTO" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "info";
        }
        if ($unico->tipo == "GRACIAS" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "";
        }
        $row .= file_get_contents("views/reconoce_vota/" . $id_empresa . "_lista_reconocimientos_perfil_tabla.html");
        $row = str_replace("{ESTILO}", $estilo_reco, $row);
        $row = str_replace("{FECHA}", $unico->fecha, $row);
        $row = str_replace("{TIPO_RECO}", $tipo_reco, $row);
        $row = str_replace("{RUT_REMITENTE}", $unico->rut_remitente, $row);
        $row = str_replace("{AVATAR_REMITENTE}", VerificaFotoPersonal($unico->rut_remitente), $row);
        $row = str_replace("{NOMBRE_REMITENTE}", $unico->nombre_remitente, $row);
        $row = str_replace("{CARGO_REMITENTE}", $unico->cargo_remitente, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_REMITENTE}", $unico->num_reconocimientos_rut_remitente, $row);
        $row = str_replace("{NUM_GRACIAS_REMITENTE}", $unico->num_gracias_rut_remitente, $row);
        $row = str_replace("{RUT_DESTINATARIO}", $unico->rut_destinatario, $row);
        $row = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut_destinatario), $row, $row);
        $row = str_replace("{NOMBRE_DESTINATARIO}", $unico->nombre_destinatario, $row);
        $row = str_replace("{CARGO_DESTINATARIO}", $unico->cargo_destinatario, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);
        $row = str_replace("{MENSAJE}", ($unico->mensaje), $row);
    }
    $PRINCIPAL = str_replace("{ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", utf8_encode($row), $PRINCIPAL);
    if ($cuenta_reconocimiento_entregados > 0) {
        $rowT      = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_lista_reconocimientos_perfil_tabla_enviados.html");
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT), $PRINCIPAL);
        $rowT2     = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_fin_lista_reconocimientos_perfil_enviados.html");
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT2), $PRINCIPAL);
    } else {
        $rowT      = "";
        $rowT2     = "";
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT), $PRINCIPAL);
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT2), $PRINCIPAL);
    }
    if ($cuenta_reconocimiento_recibidos > 0) {
        $rowT      = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_lista_reconocimientos_perfil_tabla_recibidos.html");
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT), $PRINCIPAL);
        $rowT2     = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_fin_lista_reconocimientos_perfil_tabla_recibidos.html");
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT2), $PRINCIPAL);
    } else {
        $rowT      = "";
        $rowT2     = "";
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT), $PRINCIPAL);
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT2), $PRINCIPAL);
    }
    return ($PRINCIPAL);
}
function colocarListaReconocimientosPorEquipo($PRINCIPAL, $id_empresa, $rut)
{
    $listado = TotalReconocimientosGraciasVistaPerfilEquipo($rut, $id_empresa, "recibidos");
    foreach ($listado as $unico) {
        $cuenta_reconocimiento_recibidos++;
        if ($unico->tipo == "RECONOCIMIENTO") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <BR>Reconocimiento";
            $estilo_reco = "";
        } elseif ($unico->tipo == "GRACIAS") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i> <BR>Agradecimiento";
            $estilo_reco = "";
        }
        if ($unico->tipo == "RECONOCIMIENTO" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "info";
        }
        if ($unico->tipo == "GRACIAS" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "";
        }
        //  echo "<br>".$tipo_reco;
        $row .= file_get_contents("views/reconoce_vota/" . $id_empresa . "_lista_reconocimientos_perfil_tabla.html");
        $row = str_replace("{ESTILO}", $estilo_reco, $row);
        $row = str_replace("{FECHA}", $unico->fecha, $row);
        $row = str_replace("{TIPO_RECO}", $tipo_reco, $row);
        $row = str_replace("{RUT_REMITENTE}", $unico->rut_remitente, $row);
        $row = str_replace("{AVATAR_REMITENTE}", VerificaFotoPersonal($unico->rut_remitente), $row);
        $row = str_replace("{NOMBRE_REMITENTE}", $unico->nombre_remitente, $row);
        $row = str_replace("{CARGO_REMITENTE}", $unico->cargo_remitente, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_REMITENTE}", $unico->num_reconocimientos_rut_remitente, $row);
        $row = str_replace("{NUM_GRACIAS_REMITENTE}", $unico->num_gracias_rut_remitente, $row);
        $row = str_replace("{RUT_DESTINATARIO}", $unico->rut_destinatario, $row);
        $row = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut_destinatario), $row, $row);
        $row = str_replace("{NOMBRE_DESTINATARIO}", $unico->nombre_destinatario, $row);
        $row = str_replace("{CARGO_DESTINATARIO}", $unico->cargo_destinatario, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);
        $row = str_replace("{MENSAJE}", ($unico->mensaje), $row);
    }
    $PRINCIPAL = str_replace("{ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", utf8_encode($row), $PRINCIPAL);
    $row       = "";
    $listado   = TotalReconocimientosGraciasVistaPerfilEquipo($rut, $id_empresa, "enviados");
    foreach ($listado as $unico) {
        $cuenta_reconocimiento_entregados++;
        if ($unico->tipo == "RECONOCIMIENTO") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul icon-star'></i> <BR>Reconocimiento";
            $estilo_reco = "";
        } elseif ($unico->tipo == "GRACIAS") {
            $tipo_reco   = "<i class='i-plain-sm nobottommargin i-azul far fa-heart'></i> <BR>Agradecimiento";
            $estilo_reco = "";
        }
        if ($unico->tipo == "RECONOCIMIENTO" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "info";
        }
        if ($unico->tipo == "GRACIAS" and $unico->rut_destinatario == $rut) {
            $estilo_reco = "";
        }
        $row .= file_get_contents("views/reconoce_vota/" . $id_empresa . "_lista_reconocimientos_perfil_tabla.html");
        $row = str_replace("{ESTILO}", $estilo_reco, $row);
        $row = str_replace("{FECHA}", $unico->fecha, $row);
        $row = str_replace("{TIPO_RECO}", $tipo_reco, $row);
        $row = str_replace("{RUT_REMITENTE}", $unico->rut_remitente, $row);
        $row = str_replace("{AVATAR_REMITENTE}", VerificaFotoPersonal($unico->rut_destinatario), $row);
        $row = str_replace("{NOMBRE_REMITENTE}", $unico->nombre_remitente, $row);
        $row = str_replace("{CARGO_REMITENTE}", $unico->cargo_remitente, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_REMITENTE}", $unico->num_reconocimientos_rut_remitente, $row);
        $row = str_replace("{NUM_GRACIAS_REMITENTE}", $unico->num_gracias_rut_remitente, $row);
        $row = str_replace("{RUT_DESTINATARIO}", $unico->rut_destinatario, $row);
        $row = str_replace("{AVATAR_DESTINATARIO}", VerificaFotoPersonal($unico->rut_destinatario), $row, $row);
        $row = str_replace("{NOMBRE_DESTINATARIO}", $unico->nombre_destinatario, $row);
        $row = str_replace("{CARGO_DESTINATARIO}", $unico->cargo_destinatario, $row);
        $row = str_replace("{NUM_RECONOCIMIENTOS_DESTINATARIO}", $unico->num_reconocimientos_destinatario, $row);
        $row = str_replace("{NUM_GRACIAS_DESTINATARIO}", $unico->num_gracias_destinatario, $row);
        $row = str_replace("{MENSAJE}", ($unico->mensaje), $row);
    }
    $PRINCIPAL = str_replace("{ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", utf8_encode($row), $PRINCIPAL);
    if ($cuenta_reconocimiento_entregados > 0) {
        $rowT      = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_lista_reconocimientos_perfil_tabla_enviados.html");
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT), $PRINCIPAL);
        $rowT2     = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_fin_lista_reconocimientos_perfil_enviados.html");
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT2), $PRINCIPAL);
    } else {
        $rowT      = "";
        $rowT2     = "";
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT), $PRINCIPAL);
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_ENVIADOS}", ($rowT2), $PRINCIPAL);
    }
    if ($cuenta_reconocimiento_recibidos > 0) {
        $rowT      = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_lista_reconocimientos_perfil_tabla_recibidos.html");
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT), $PRINCIPAL);
        $rowT2     = file_get_contents("views/reconoce_vota/" . $id_empresa . "_titulo_fin_lista_reconocimientos_perfil_tabla_recibidos.html");
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT2), $PRINCIPAL);
    } else {
        $rowT      = "";
        $rowT2     = "";
        $PRINCIPAL = str_replace("{TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT), $PRINCIPAL);
        $PRINCIPAL = str_replace("{FIN_TITULO_ROW_REC_TABLA_RECONOCIMIENTOS_GRACIAS_RECIBIDOS}", ($rowT2), $PRINCIPAL);
    }
    return ($PRINCIPAL);
}
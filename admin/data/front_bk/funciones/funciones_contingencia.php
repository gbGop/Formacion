<?php
function turnos_dias($rc,$start_date,$end_date)
{
    $current_date = strtotime($start_date);
    $end_date = strtotime($end_date);

    while ($current_date <= $end_date) {
        $dayOfWeek = date('N', $current_date);
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            $name_day=date('D', $current_date);
            //echo date('Y-m-d', $current_date) . " - ".$name_day."<br>";
            //echo "<br>---> ".$_POST[$name_day];
            GuardaTurnosColaborador($rc, date('Y-m-d', $current_date), $_POST[$name_day], $_SESSION["user_"], date('Y-m-d'));
            //exit();
        }
        $current_date = strtotime('+1 day', $current_date);
    }
}
function Week(){
	
	
	
		$diaSemana = date("w");
		# Calcular el tiempo (no la fecha) de cuándo fue el inicio de semana
		$tiempoDeInicioDeSemana = strtotime("-" . $diaSemana . " days"); # Restamos -X days
		# Y formateamos ese tiempo
		$fechaInicioSemana = date("Y-m-d", $tiempoDeInicioDeSemana);
		# Ahora para el fin, sumamos
		$tiempoDeFinDeSemana = strtotime("+" . $diaSemana . " days", $tiempoDeInicioDeSemana); # Sumamos +X days, pero partiendo del tiempo de inicio
		# Y formateamos
		$fechaFinSemana = date("Y-m-d", $tiempoDeFinDeSemana);

		# Listo. Hora de imprimir
		//echo "Hoy es " . date("Y-m-d") . ". ";
		//echo "El inicio de semana es $fechaInicioSemana y el fin es $fechaFinSemana";
		//hoy 
		$arreglo[0]=date("Y-m-d");
		//inicio 
		$arreglo[1]=$fechaInicioSemana;
		//fin semana
		$arreglo[2]=$fechaFinSemana;
		
		
				return($arreglo);
					
	
}

function Contingencia_2021_NuevoRegimen_DadoActual($regimen,$motivo){
	//echo "<br><h1>$regimen $motivo</h1>";
	$array=Contingencia_2021_NuevoRegimen_DadoActual_data($regimen,$motivo);
	//echo "<br><br><br>";
	//print_r($array);
	$option.="";
	$puede_modificar="";
	if(count($array)>0){

		foreach($array as $unico){
			$option.="<option value='".$unico->regimen_nuevo."-".$unico->motivo_nuevo."'>".$unico->regimen_nuevo."-".$unico->motivo_nuevo."</option>";
			
			if($unico->muestra_modificar_regimen=="1"){
				$puede_modificar="1";
			}
		}
		
		
		$fecha_inicio=$unico->fecha_inicio;
		$fecha_fin=$unico->fecha_fin;

		
	} else {
		$option="";
		$puede_modificar="";
		$fecha_inicio="";
		$fecha_fin="";
	}

$arreglo[0]=$option;
$arreglo[1]=$puede_modificar;
$arreglo[2]=$fecha_inicio;
$arreglo[3]=$fecha_fin;


return $arreglo;	
	
}


function MiEquipo2021_Colaboradores_equipo_consolidado_contingencia($PRINCIPAL, $id_empresa, $rut)
{
		$array_usuario				 = Contingencia_2021_MiEquipoo($rut, $id_empresa);
		$hoy=date("Y-m-d");
		$ayer= date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $hoy) ) ));
		$hoy2 = date("d-m-Y", strtotime($hoy));  
  	$ayer2 = date("d-m-Y", strtotime($ayer));  
		$cuenta_usuario_equipo=count($array_usuario);
	
		$array_contigencia_ubicacion=ContingenciaBuscaDireccion();
		foreach ($array_contigencia_ubicacion as $unico){
				$array_options_direccion.="<option value='".utf8_encode($unico->id)."'>".utf8_encode($unico->direccion)." ".utf8_encode($unico->piso)." ".utf8_encode($unico->referencia).", ".utf8_encode($unico->comuna).", ".utf8_encode($unico->ciudad)."</option>";	
		}
					
					
						$cuenta_colaborador=0;
						$cuenta_colaborador_ausente=0;
						$cuenta_colaborador_presencial=0;
						$cuenta_colaborador_trabajando=0;
						$cuenta_colaborador_sintrabajar=0;					
					
					$oculta_modificar_regimen=" ";
	 foreach ($array_usuario as $unico) {
								 	//$BuscaDataAyer	=	MiEquipoContingenciaStatus($unico->rut,$ayer,$id_empresa);
								 	//$BuscaDataHoy		=	MiEquipoContingenciaStatusHoy($unico->rut,$hoy,$id_empresa);
                                     $BuscaData = new stdClass();

                                     $oculta_modificar_regimen=" ";
								 	$BuscaData=	MiEquipoContingenciaStatus2021($unico->rut,$hoy,$id_empresa);
								 	//print_r($BuscaData);
								 	//echo "<br>";
								 	$detalle_ayer	="";
								 	$status_ayer		=	"";
								 	$detalle_hoy	= "";
								 	$status_hoy ="";
								 	$Futuro_V2 = Contingencia_2021_Futuro_data($unico->rut,$hoy,$id_empresa);
								 	//print_r($Futuro_V2);
								 	if($Futuro_V2[0]->regimen<>"" and $BuscaData[0]->regimen_futuro==""){
								 		$BuscaData[0]->regimen_futuro=$Futuro_V2[0]->regimen;
								 		$BuscaData[0]->motivo_futuro=$Futuro_V2[0]->motivo;
								 		$BuscaData[0]->fecha_inicio_futuro=$Futuro_V2[0]->fecha_inicio;
								 		$BuscaData[0]->fecha_termino_futuro=$Futuro_V2[0]->fecha_termino;
								 	}
								 	
								 	
								 	if($BuscaData[0]->regimen_futuro<>""){
								 		

								 		$datos_futuro="
								 		
											<div class='alert alert-primary' style='color: #fff !important;background-color: #3d4971 !important;border-color: #3d4971 !important;'>Regimen Futuro</div>

								 		
								 		<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Regimen Futuro</div>
								 	
								 		<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
																<i class='fas fa-angle-right'></i> ".$BuscaData[0]->regimen_futuro."</div>
															
													<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Motivo Futuro</div>																
													<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
																<i class='fas fa-angle-right'></i> ".$BuscaData[0]->motivo_futuro."</div>
															
													<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Fechas</div>
												<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='far fa-calendar-alt'></i> Desde ".FechaDDMMYYYY($BuscaData[0]->fecha_inicio_futuro)." a ".FechaDDMMYYYY($BuscaData[0]->fecha_termino_futuro)."
														</div>																														
								 								
								 						
								 		
								 		";
								 		
								 		//echo "<br>rut ".$unico->rut." fecha futuro desde ".$BuscaData[0]->fecha_inicio_futuro." - hoy ".$hoy;
								 		if($hoy>=$BuscaData[0]->fecha_inicio_futuro){
								 			$datos_futuro="";	
								 		}								 		
								 		
								 	} else {
								 		$datos_futuro="";	
								 	}
								 	
								 	
								 	//CUENTAS RESUMENES
								 	$cuenta_colaborador++;
								 	//echo "<br>--> Regimen ".$BuscaData[0]->regimen;
								 	if($BuscaData[0]->regimen=="Ausente")																{$cuenta_colaborador_ausente++;}
								 	if($BuscaData[0]->regimen=="Presencial")														{ $cuenta_colaborador_presencial++;}
								 	if($BuscaData[0]->regimen=="Trabajando en Casa por contingencia" or $BuscaData[0]->regimen=="Trabajando en Casa")		{ $cuenta_colaborador_trabajando++;}
								 	if($BuscaData[0]->regimen=="En Casa Sin Trabajar por contingencia" or $BuscaData[0]->regimen=="En Casa Sin Trabajar")	{ $cuenta_colaborador_sintrabajar++;}
								 	
								 	
								 	//$ayer3 = date("d-m-Y", strtotime($BuscaUltimaFechaEscritaQueNoSeaHoy[0]->fecha));  
							$vacio=0;
								if($BuscaData[0]->regimen<>"")	{
									$detalle_ayer	=	"
													<div class=''>
													<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
																			Regimen</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> ".$BuscaData[0]->regimen."</div>
														<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Motivo</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> ".$BuscaData[0]->motivo."
														</div>
														<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Fechas</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='far fa-calendar-alt'></i> 
															Desde ".FechaDDMMYYYY($BuscaData[0]->fecha_inicio)." a ".FechaDDMMYYYY($BuscaData[0]->fecha_termino)."
														</div><br>
														<div style='    font-size: 11px;    margin-bottom: 0;    font-weight: 400;    padding-bottom: 15px;      padding-top: 5px; padding-left: 8px;'>
														".$comentario_numrut."
														</div>
														
																	
									</div>";		
								} else {
										
										
										$detalle_ayer="
										
										<div class=''>
													<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
																			Regimen</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> Presencial</div>
														<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Motivo</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> Permanente
														</div>
											</div>			
										";
										$detalle_ayer="
										
										<div class=''>
												<div class='alert alert-warning'><center style='font-size: 11px;'>Favor seleccionar r&eacute;gimen/motivo
												</center></div>
										</div>
													<!--<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
																			Regimen</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> Presencial xxx</div>
														<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);    width: 100%;
														    padding-bottom: 3px;
														    padding-top: 3px;'>
														Motivo</div>
														<div style='    font-size: 13px;    margin-bottom: 0;    font-weight: 600;    padding-bottom: 15px;    color: #192857;    padding-top: 5px; padding-left: 8px;'>
															<i class='fas fa-angle-right'></i> Permanente
														</div>
											</div>		-->	
										";
										

										$muestra_form=1;
										$vacio=1;
										}						
								

								if($BuscaDataHoy[0]->detalle<>"")		{$detalle_hoy		=	"<br><div class='sspd_review_liked_3'><a href='#'><span class='txt_contigencia'>".$BuscaDataHoy[0]->detalle."</span></a></div><br><div class='pull pull-right'>
									<a href='?sw=mi_equipo_consolidado_contingencia&d=1&hy={HOY_ENC}&rut_col=".Encodear3($unico->rut)."' class='btn btn-link'><span style='font-size: 12px;
							    color: #1882bf;'>Editar</span></a></div>";		} 
							    else {$detalle_hoy="<center>Sin Informaci&oacute;n (.)</center>";
							    	}							
								if($BuscaDataHoy[0]->status<>"" and $BuscaDataHoy[0]->edit=="")		
								{
											$status_hoy		=	"<div class='sspd_review_liked_2'><a href='#'><span class='txt_contigencia'>".$BuscaDataHoy[0]->status."</span></a></div>";			
													$cuenta_con_actualizacion++;
									
											} 	else {
                                    if ($vacio == "1") {
                                        if (!empty($BuscaData[0])) {
                                            $BuscaData[0]->regimen = "Presencial";
                                            $BuscaData[0]->motivo = "Permanente";
                                        } else {
                                        }
                                    }
					//echo "-> ".$unico->rut." ".$BuscaData[0]->regimen." ".$BuscaData[0]->motivo;
					$NuevoRegimenStatus=Contingencia_2021_NuevoRegimen_DadoActual($BuscaData[0]->regimen,$BuscaData[0]->motivo);
					
					$option_dinamico_nuevo_regimen=$NuevoRegimenStatus[0];
					$muestra_form=$NuevoRegimenStatus[1];
					
					if($muestra_form>0 or $vacio=="1"){
						$oculta_modificar_regimen=" ";
					} else {
						$oculta_modificar_regimen=" display:none !important;";
					}
					
					
				


					
					$inicio=date("Y-m-d");
					$year=date("Y");
					$hasta=$year."-12-31";

                    $tiene_turno=BuscaTurnosUsuarioData($unico->rut);
                   // echo "<br>-> ".$unico->rut." ".$tiene_turno;
                    $boton_turnos="";
                    if($tiene_turno>0){
                        //echo "<br>A<br>";
                        $boton_turnos="
                                                    <a class='btn btn_link_mi_equipo' href='?sw=turnos&rc=".Encodear3($unico->rut)."' style='padding: 0px;'>
                                                        Gestionar Turnos Teletrabajo
                                                      </a>                        
                                                      ";
                        //echo $boton_turnos;
                    }

					$detalle_hoy="

                           


                                                                                <!--<center>
                                                                                    <a href='?sw=mi_equipo_consolidado_contingencia&same=1&ay={AYER_ENC}&hy={HOY_ENC}&rut_col=".Encodear3($unico->rut)."' class='btn btn-info' style='width: 230px;font-size: 14px;'>Todo sigue igual que ayer</a>
                                                                                </center>
                                                                            <br>	<hr> <br>	-->
                                                                            
                                                                            <p>
                                          <a class='btn btn-link pull-right' data-toggle='collapse' href='#collapseExample".$unico->rut."' role='button' aria-expanded='false' aria-controls='collapseExample".$unico->rut."'
                                          style='    
                                            width: 100%;
                                            text-align: left;
                                            display: block;
                                            float: left;".$oculta_modificar_regimen."'>
                                            <span style='color: #192857;    color: #192857;font-size: 14px;padding-bottom: 6px;'>Modificar Regimen</span>
                                          </a>
                                        </p>
                                        <div class='collapse' id='collapseExample".$unico->rut."'>
                                          <div class='card card-body' style='width: 100%;background: transparent;'>
                                        
                                        <div class='txt_2020_reporte_hoy' style=''>
                                                                                                        <div class='alert alert-primary'>Modificando Regimen Actual</div></div>  
                                          <br>
                                        
                                          
                                          
                                          
                                          
                                            <form id='contingencia_".$unico->rut."' name='contigencia_".$unico->rut."' method='post' 
                                                                        action='?sw=mi_equipo_consolidado_contingencia_21'>	
                                                                                                <div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Nuevo Status</div>									
                                                                                                                <select name='status_".$unico->rut."' class='form form-control' required>
                                                                                                                    <option value=''></option>														
                                                                                                                    ".$option_dinamico_nuevo_regimen."
                                                                                                                    
                                                                                                                </select>
                                                                                                                
                                                                                                                <!--<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px; border-radius: 1px;    padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Fecha Desde</div>-->
                                                                                                                <input type='hidden' name='fecha_desde_".$unico->rut."' class='form form-control' value='".$inicio."'>
                                        
                                                                                                                <div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Fecha Hasta</div>									
                                                                                                                <input type='date' name='fecha_hasta_".$unico->rut."' min='".$hoy."' class='form form-control' value='".$hasta."'>
                                                                                                    
                                                                                                                <center><input type='submit' value='Guardar Modificacion' class='btn btn-info' style=' font-size: 14px;'></center>										
                                                                                                                <input type='hidden' name='save_day' value='1'>
                                                                                                                <input type='hidden' name='hye' value='{HOY_ENC}'>
                                                                                                                <input type='hidden' name='rut_enc_post' value='".Encodear3($unico->rut)."'>
                                                                                                            </form>
                                          </div>
                                        </div>
                                        
                                        
                                        <p>
                                          <a class='btn btn-link pull-right' data-toggle='collapse' href='#collapseExample_f_".$unico->rut."' role='button' aria-expanded='false' aria-controls='collapseExample_f_".$unico->rut."'
                                          style='    
                                            width: 100%;
                                            text-align: left;
                                            display: block;
                                            float: left;".$oculta_modificar_regimen."'>
                                            <span style='color: #192857;    color: #192857;font-size: 14px;padding-bottom: 6px;'>Planificar Regimen Futuro</span>
                                          </a>
                                        </p>
                                        <div class='collapse' id='collapseExample_f_".$unico->rut."'>
                                          <div class='card card-body' style='width: 100%;background: transparent;'>
                                        
                                        <div class='txt_2020_reporte_hoy' style=''>
                                                                                                        <div class='alert alert-primary'>Planificar Regimen Futuro</div></div>  
                                          <br>
                                        
                                          
                                          
                                          
                                          
                                            <form id='contigencia_f_".$unico->rut."' name='contigencia_f_".$unico->rut."' method='post' 
                                                                        action='?sw=mi_equipo_consolidado_contingencia_21'>	
                                                                                                <div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Nuevo Status</div>									
                                                                                                                <select name='status_".$unico->rut."' class='form form-control' required>
                                                                                                                    <option value=''></option>														
                                                                                                                    ".$option_dinamico_nuevo_regimen."
                                                                                                                    
                                                                                                                </select>
                                                                                                                
                                                                                                                <div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px; border-radius: 1px;    padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Fecha Desde</div>
                                                                                                                <input type='date' name='fecha_desde_".$unico->rut."' class='form form-control' min='".$hoy."' value='".$inicio."'>
                                        
                                                                                                                <div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Fecha Hasta</div>									
                                                                                                                <input type='date' name='fecha_hasta_".$unico->rut."' class='form form-control'  min='".$hoy."'  value='".$hasta."'>
                                                                                                    
                                                                                                                <center><input type='submit' value='Guardar Planificación' class='btn btn-info' style=' font-size: 14px;'></center>										
                                                                                                                <input type='hidden' name='save_day' value='1'>
                                                                                                                <input type='hidden' name='hye' value='{HOY_ENC}'>
                                                                                                                <input type='hidden' name='rut_enc_post' value='".Encodear3($unico->rut)."'>
                                                                                                            </form>
                                                                                                            
                                                    
                                                                                                            
                                          </div>
                                        </div>
                                                                            
								
																";
								}
 	
 	//echo "<br> Fecha ".$BuscaData[0]->fecha_actualizacion;
 	if($BuscaData[0]->fecha_actualizacion<>"0000-00-00"){
 		$detalle_hoy="
 		<div class='alert alert-info' style='margin-bottom: 2px;'>
 			<center>Regimen Modificado</center>
 		</div>
 		<small>
 				<center><a href='?sw=Contingencia_21_reset&idr=".Encodear3($BuscaData[0]->id)."' class='btn btn-link' style='font-size:13px; color:#192857;'>Deshacer Modificación</a></center>
 	 </small>
 								
 								";
 	}
 	
										$array_res[5]=0;
						        $array_res=BuscaCountResultadosMiEquipo($unico->rut,$id_empresa);
						        $imagen_usuario=VerificaFotoPersonal($unico->rut);
						        $rut_enc=Encodear3($unico->rut);
											if($array_res[5]>0){
												$boton_ver_equipo	=	"<a href='?sw=mi_equipo_consolidado_contingencia_21&r_enc=".$rut_enc."' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Equipo</a>";
											} else {
												$boton_ver_equipo	=	"";
											}
											
											$Busca_alerta=ContingenciaBuscaAlertas($unico->rut, $id_empresa);
											//print_r($Busca_alerta);
											
											if($Busca_alerta[0]->alerta<>""){
												$alerta="<div class='alert alert-info'><small><strong>Informaci&oacute;n Centralizada:</strong></small><br>".utf8_encode($Busca_alerta[0]->alerta)."</div>";
											} else {
												$alerta="";
											}
											//echo $unico->rut;
											$Us=ContingenciaDatosUsuario_($unico->rut, $id_empresa);
											
								$fecha_inicio="2021-05-01";
								$fecha_hoy		= date("Y-m-d");		
								$total_dias=ContingenciaDiasHabiles($fecha_inicio, $fecha_hoy);
								
											$Recuento=ContingenciaRecuento_DatosUsuario_21_data($unico->rut, $fecha_inicio, $id_empresa);
											//print_r($Recuento);											exit();
								
								//$vacios=$total_dias-$Recuento[2]-$Recuento[1]-$Recuento[0];
											
											$Recuento_Resumen="
																						<br><div>
																						
																						<i class='fas fa-dot-circle'></i> ".$Recuento[1]." Presencial 
																						<br>
																						<i class='fas fa-dot-circle' style='color: #03c2ff;'></i> ".$Recuento[2]." Trabajando en Casa 
																						<br>
																						<i class='fas fa-dot-circle' style='color: #b7b7b7;'></i> ".$Recuento[3]." En Casa Sin Trabajar																					
																						<br>
																						<i class='fas fa-dot-circle' style='color: #b7b7b7;'></i> ".$Recuento[4]." Ausente
																						</div>
																				";
											
											//Rut Retornado 
											/*$UsuarioRetorno=Contingencia_2021_usuario_retorno($unico->rut);
											
											if($UsuarioRetorno>0){
												$alerta_rut_retorno="<div class='alert alert-info' style='padding: 3px;font-size: 12px;'>Colaborador retornado con puesto asignado</div>";
											} else {
												$alerta_rut_retorno="<div class='alert alert-info' style='padding: 3px;font-size: 12px;'>Colaborador no retornado a trabajo presencial</div>";
											}

                                            if($Us[0]->teletrabajo=="1"){
                                                $alerta_rut_retorno="<div class='alert alert-info' style='padding: 3px;font-size: 12px;'>Colaborador en modalidad Teletrabajo</div>";
                                            }*/
                                            $alerta_rut_retorno="";
											
											$UsuarioRetornoM=Contingencia_2023_modalidadtrabajo($unico->rut);
											
											if($UsuarioRetornoM[0]->id>0){
												$alerta_rut_retorno="<div class='alert alert-info' style='padding: 3px;font-size: 12px;'>R&eacute;gimen ".$UsuarioRetornoM[0]->modalidad."</div>";
											} else {
												$alerta_rut_retorno="";
											}

                                            //$Antigeno_ultimaActualizacion=ContigenciaBuscaAntigenoFecha($unico->rut, $id_empresa);
											//<div>".utf8_encode($Antigeno_ultimaActualizacion)."</div>
											//$alerta_rut_retorno="";
											//Ubicacion Actual y Editar											
											$Ub=ContingenciaDatosUsuarioUbicacion_($unico->rut, $id_empresa);
											//print_r($Ub);
											$modulo_ubicacion="
													<h6 class='name'>
														<div><strong>".($Ub[0]->temporalidad)." 																	
																		<small style='margin-left: 30px;'  data-toggle='collapse' 
																				href='#collapseEditUbicacion_".$unico->rut."'
																				role='button' 
																				aria-expanded='false' 
																				aria-controls='collapseEditUbicacion_".$unico->rut."'>
																					<i class='fas fa-pen'></i> Editar Ubicacion</small>
																	</strong></div>
														<div>".utf8_encode($Ub[0]->direccion)."</div>
														<div>".utf8_encode($Ub[0]->piso)." ".utf8_encode($Ub[0]->referencia)."</div>
														<div>".utf8_encode($Ub[0]->comuna)." ".utf8_encode($Ub[0]->ciudad)."</div>
														".$alerta_rut_retorno."
														<div>".utf8_encode($Recuento_Resumen)."</div>
														
													</h6>

												<div class='collapse' id='collapseEditUbicacion_".$unico->rut."'>
												  <div class='card card-body'  style='padding: 0px;'>
															<form action='?sw=mi_equipo_consolidado_contingencia_21' method='post'>
																	<div><small>Elegir nueva ubicaci&oacute;n</small></div>
																	<select class='form form-control' name='Ub' required>
																			<option value=''></option>
																			".$array_options_direccion."
																	</select>
																	
																	<input type='radio' id='temporalidad' name='temporalidad' value='Ubicaci&oacute;n Permanente' required>
  																<label for='ubicacion' style='font-size: 10px;display: inline;'>Ubicaci&oacute;n Permanente</label>
  																<input type='radio' id='temporalidad' name='temporalidad' value='Ubicaci&oacute;n Provisoria' required>
																	<label for='ubicacion' style='font-size: 10px;display: inline;'>Ubicaci&oacute;n Provisoria</label><br>
																	
																	<input type='hidden' name='rutEd' value='".Encodear5($unico->rut)."'
																	<input type='hidden' name='EdUb' value='1'> 
																	<input type='submit' value='Actualizar' class='btn btn-info'>
															</form>
												  </div>
												</div>


													
													";
													
											
											
											if($Us[0]->nombre_completo==""){ continue;}
											
											if($ayer3=="31-12-1969"){
												$display_ayer3=" display:none; ";
											} else {
												$display_ayer3="  ";
											}
							//if($rut=="12345" or $rut=="12254603"){
									$row_col="<br><a href='?sw=mi_equipo_consolidado_contingencia_colaborador_21&col=".Encodear3($unico->rut)."' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Detalle Diario</a>";
							//} else {
									//$row_col="";
							//}
						        $row.=
						        			"
													<li class='contact'>
														<div class='row' style='background: #F5F9FF;border-radius: 10px;padding-bottom: 20px;padding-top: 20px;'>
															<div class='col col-lg-4'> 												
																<div class='wrap'>
																	<span class='contact-status online'></span>
																	<a href='#' style='display: inline;'>
																		<img class='img-fluid' src='".$imagen_usuario."' alt='".$Us[0]->nombre_completo."'>
																	</a>
																	<div class='meta'>
																			<a href='#' style='display: inline;'>
																				<h5 class='name'>".$Us[0]->nombre_completo."<br>
																				<small>".utf8_encode($Us[0]->cargo)."</small></h5>
																			</a>
																			".$modulo_ubicacion."
																			".$alerta."
																			<div>
																					".$row_col."
																					<br>".$boton_ver_equipo."
																					 <br>".$boton_turnos."
																			</div>
																	</div>
																</div>
														</div>
														<div class='col col-lg-5' > 
															<div class='txt_2020_reporte_hoy' style='".$display_ayer3."'>
																<div class='alert alert-primary' style='color: #fff !important;background-color: #3d4971 !important;border-color: #3d4971 !important;'>Regimen Actual</strong></div></div>
																".utf8_encode($status_ayer)."
																".utf8_encode($detalle_ayer)."
																".utf8_encode($datos_futuro)."
																
														</div>
														<div class='col col-lg-3'> 
															<div class='txt_2020_reporte_hoy'>
																
																	".utf8_encode($status_hoy)."
																	".utf8_encode($detalle_hoy)."
																	</div>			
														</div>
											</li>
										";
						        
							      $row = str_replace("{VER_EQUIPO}",   $boton_ver_equipo, $row);
							      $row = str_replace("{VER_MAS}",   $boton_ver_mas, $row);

							 
							 }
						 	$falta=$cuenta_usuario_equipo-$cuenta_con_actualizacion;
						 	if($cuenta_con_actualizacion>$cuenta_usuario_equipo and $cuenta_usuario_equipo>0){
						 		$equipo_estado_progreso="<div class='alert alert-primary' style='font-size: 11px;padding: 5px;'>Actualizaci&oacute;n Completa</div>";
						 	} else {
						 		$equipo_estado_progreso="<div class='alert alert-info'  style='font-size: 11px;padding: 5px;'>Falta actualizar informaci&oacute;n de ".$falta." colaboradores </div>";
						 		}

						$PRINCIPAL = str_replace("{EQUIPO_ACTUALIZADO_COMPLETO_HOY}", $equipo_estado_progreso, $PRINCIPAL);


						$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
						 	
						$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
						$PRINCIPAL = str_replace("{AYER_ENC}", Encodear3($ayer), $PRINCIPAL);
						$PRINCIPAL = str_replace("{HOY_ENC}",  Encodear3($hoy), $PRINCIPAL);

						$PRINCIPAL = str_replace("{cuenta_colaborador}", 							$cuenta_colaborador, $PRINCIPAL);
						$PRINCIPAL = str_replace("{cuenta_colaborador_ausente}", 			$cuenta_colaborador_ausente, $PRINCIPAL);
						$PRINCIPAL = str_replace("{cuenta_colaborador_presencial}",	 	$cuenta_colaborador_presencial, $PRINCIPAL);
						$PRINCIPAL = str_replace("{cuenta_colaborador_trabajando}", 	$cuenta_colaborador_trabajando, $PRINCIPAL);
						$PRINCIPAL = str_replace("{cuenta_colaborador_sintrabajar}", 	$cuenta_colaborador_sintrabajar, $PRINCIPAL);

    return ($PRINCIPAL);
}

function FechaExcelPhpFront($string)  {
	
	$porciones = explode("/", $string);
	
	$y=$porciones[2];
	$m=$porciones[1];
	$d=$porciones[0];

	
  $UNIX_DATE = ($number - 25569) * 86400;
$fecha=gmdate("Y-m-d", $UNIX_DATE);

	$fecha = "$y-$m-$d";
return $fecha;
}


function MiEquipo2020_Colaboradores_equipo_consolidado_contingencia($PRINCIPAL, $id_empresa, $rut)
{
		$array_usuario				 = Contingencia_2020_MiEquipoo($rut, $id_empresa);
		$hoy=date("Y-m-d");
		$ayer= date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $hoy) ) ));
		$hoy2 = date("d-m-Y", strtotime($hoy));  
  	$ayer2 = date("d-m-Y", strtotime($ayer));  
		$cuenta_usuario_equipo=count($array_usuario);
	
		$array_contigencia_ubicacion=ContingenciaBuscaDireccion();
		foreach ($array_contigencia_ubicacion as $unico){
				$array_options_direccion.="<option value='".utf8_encode($unico->id)."'>".utf8_encode($unico->direccion)." ".utf8_encode($unico->piso)." ".utf8_encode($unico->referencia).", ".utf8_encode($unico->comuna).", ".utf8_encode($unico->ciudad)."</option>";	
		}
	
	 foreach ($array_usuario as $unico) {
								 	$BuscaDataAyer	=	MiEquipoContingenciaStatus($unico->rut,$ayer,$id_empresa);
								 	$BuscaDataHoy		=	MiEquipoContingenciaStatusHoy($unico->rut,$hoy,$id_empresa);
								 	$BuscaUltimaFechaEscritaQueNoSeaHoy=Contingencia_MiEquipoContingenciaStatusUltimaFechaEscritaNoHoyData($unico->rut,$hoy,$id_empresa);
								 	$detalle_ayer	="";
								 	$status_ayer		=	"";
								 	$detalle_hoy	= "";
								 	$status_hoy ="";
								 	$ayer3 = date("d-m-Y", strtotime($BuscaUltimaFechaEscritaQueNoSeaHoy[0]->fecha));  

								if($BuscaUltimaFechaEscritaQueNoSeaHoy[0]->detalle<>"")	{$detalle_ayer	=	"<br><div class='sspd_review_liked_3'><a href='#'><span class='txt_contigencia'>".$BuscaUltimaFechaEscritaQueNoSeaHoy[0]->detalle."</span></a></div>";		} else {$detalle_ayer="";}						
								if($BuscaUltimaFechaEscritaQueNoSeaHoy[0]->status<>"")		{$status_ayer		=	"<div class='sspd_review_liked_2'><a href='#'><span class='txt_contigencia'>".$BuscaUltimaFechaEscritaQueNoSeaHoy[0]->status."</span></a></div>";		} else {$status_ayer="";}
								if($BuscaDataHoy[0]->detalle<>"")		{$detalle_hoy		=	"<br><div class='sspd_review_liked_3'><a href='#'><span class='txt_contigencia'>".$BuscaDataHoy[0]->detalle."</span></a></div><br><div class='pull pull-right'>
									<a href='?sw=mi_equipo_consolidado_contingencia&d=1&hy={HOY_ENC}&rut_col=".Encodear3($unico->rut)."' class='btn btn-link'><span style='font-size: 12px;
							    color: #1882bf;'>Editar</span></a></div>";		} else {$detalle_hoy="";}							
								if($BuscaDataHoy[0]->status<>"" and $BuscaDataHoy[0]->edit=="")		{
											$status_hoy		=	"<div class='sspd_review_liked_2'><a href='#'><span class='txt_contigencia'>".$BuscaDataHoy[0]->status."</span></a></div>";			
													$cuenta_con_actualizacion++;
									
											} 	else {
						$detalle_hoy="
										<center>
								 			<a href='?sw=mi_equipo_consolidado_contingencia&same=1&ay={AYER_ENC}&hy={HOY_ENC}&rut_col=".Encodear3($unico->rut)."' class='btn btn-info' style='width: 230px;font-size: 14px;'>Todo sigue igual que ayer</a>
								 		</center>
								 	<br>	<hr> <br>	
								<form ida='contingencia_".$unico->rut."' name='contigencia_".$unico->rut."' method='post' action='?sw=mi_equipo_consolidado_contingencia'>	
														<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px;    border-radius: 1px;padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Nuevo Status</div>									
																		<select name='status_".$unico->rut."' class='form form-control' required>
																			<option value=''></option>														
																			<option value='No Trabajando / Licencia Medica'>No Trabajando / Licencia Medica</option>	
																			<option value='No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)'>No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)</option>										
																			<option value='No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social'>No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social</option>
																			<option value='No Trabajando / Vacaciones'>No Trabajando / Vacaciones</option>
																			<option value='No trabajando / Sin información del colaborador'>No trabajando / Sin información del colaborador</option>
																			<option value='No Trabajando / otro motivo'>No Trabajando / otro motivo</option>															
																			<option value='Trabajando / Trabajo en Casa'>Trabajando / Trabajo en Casa</option>
																			<option value='Trabajando / Trabajo en Casa con Permiso por Precaución COVID 19'>Trabajando / Trabajo en Casa con Permiso por Precaución COVID 19</option>
																			<option value='Trabajando / Instalación Banco'>Trabajando / Instalación Banco	</option>
																			<option value='Trabajando / Ubicación Alterna Ciudad de los Valles'>Trabajando / Ubicación Alterna Ciudad de los Valles</option>
																			<option value='Trabajando / Ubicación Alterna Estadio'>Trabajando / Ubicación Alterna Estadio</option>
																			<option value='Trabajando / Ubicación Alterna Sotero Sanz'>Trabajando / Ubicación Alterna Sotero Sanz</option>
																		</select>
																		<div style='font-weight: 600;    font-size: 13px;    background-color: #ddd;    width: 102px; border-radius: 1px;    padding-left: 5px;    color: #666;    background-color: rgb(237, 239, 247);'>Nuevo Detalle</div>
								     							<select name='detalle_".$unico->rut."' class='form form-control' required>
																			<option value=''></option>
																			<option value='Sin observaciones'>Sin observaciones</option>	
																			<option value='Tiene condición de salud riesgosa'>Tiene condición de salud riesgosa</option>
																			<option value='Presenta problemáticas familiares que limitan su asistencia al trabajo'>Presenta problemáticas familiares que limitan su asistencia al trabajo</option>
																			<option value='Estuvo recientemente en un país declarado como riesgoso'>Estuvo recientemente en un país declarado como riesgoso</option>
																			<option value='Estuvo en contacto con persona en condición de riesgo de contagio'>Estuvo en contacto con persona en condición de riesgo de contagio</option>
																			<option value='Sucursal en Cuarentena'>Sucursal en Cuarentena</option>
																			<option value='Turno 50% Dotación Sucursal'>Turno 50% Dotación Sucursal</option>
																			<option value='Cuarentena Obligatoria'>Cuarentena Obligatoria</option>
																			<option value='Sucursal Cerrada'>Sucursal Cerrada</option>
																		</select>
																		<center><input type='submit' value='Guardar' class='btn btn-info' style=' font-size: 14px;'></center>										
																		<input type='hidden' name='save_day' value='1'>
																		<input type='hidden' name='hye' value='{HOY_ENC}'>
																		<input type='hidden' name='rut_enc_post' value='".Encodear3($unico->rut)."'>
																	</form>
																";
								}
 	
										$array_res[5]=0;
						        $array_res=BuscaCountResultadosMiEquipo($unico->rut,$id_empresa);
						        $imagen_usuario=VerificaFotoPersonal($unico->rut);
						        $rut_enc=Encodear3($unico->rut);
											if($array_res[5]>0){
												$boton_ver_equipo	=	"<a href='?sw=mi_equipo_consolidado_contingencia&r_enc=".$rut_enc."' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Equipo</a>";
											} else {
												$boton_ver_equipo	=	"";
											}
											
											$Busca_alerta=ContingenciaBuscaAlertas($unico->rut, $id_empresa);
											//print_r($Busca_alerta);
											
											if($Busca_alerta[0]->alerta<>""){
												$alerta="<div class='alert alert-info'><small><strong>Informaci&oacute;n Centralizada:</strong></small><br>".utf8_encode($Busca_alerta[0]->alerta)."</div>";
											} else {
												$alerta="";
											}
											//echo $unico->rut;
											$Us=ContingenciaDatosUsuario_($unico->rut, $id_empresa);
											
								$fecha_inicio="2020-03-18";
								//echo "<br>fecha_inicio $fecha_inicio - Fecha ingreso ".$Us[0]->fecha_ingreso;
								if($Us[0]->fecha_ingreso>$fecha_inicio){
									$fecha_inicio=$Us[0]->fecha_ingreso;
								}
								$fecha_hoy		= date("Y-m-d");		
								$total_dias=ContingenciaDiasHabiles($fecha_inicio, $fecha_hoy);
								
											$Recuento=ContingenciaRecuento_DatosUsuario_data($unico->rut, $fecha_inicio, $id_empresa);
											//print_r($Recuento);
								
								$vacios=$total_dias-$Recuento[2]-$Recuento[1]-$Recuento[0];
											
											$Recuento_Resumen="
																						<br><div>
																						
																						<i class='fas fa-dot-circle'></i> ".$Recuento[2]." Presencial 
																							| 
																						<i class='fas fa-dot-circle' style='color: #03c2ff;'></i> ".$Recuento[1]." Remoto 
																							| 
																						<i class='fas fa-dot-circle' style='color: #b7b7b7;'></i> ".$Recuento[0]." No Trabajando 
																						
																						<br>
																						<i class='fas fa-dot-circle' style='color: #b7b7b7;'></i> ".$vacios." Sin Registros
																						</div>
																				";
											
											//Ubicacion Actual y Editar											
											$Ub=ContingenciaDatosUsuarioUbicacion_($unico->rut, $id_empresa);
											//print_r($Ub);
											$modulo_ubicacion="
													<h6 class='name'>
														<div><strong>".utf8_encode($Ub[0]->temporalidad)." 																	
																		<small style='margin-left: 30px;'  data-toggle='collapse' 
																				href='#collapseEditUbicacion_".$unico->rut."'
																				role='button' 
																				aria-expanded='false' 
																				aria-controls='collapseEditUbicacion_".$unico->rut."'>
																					<i class='fas fa-pen'></i> Editar Ubicacion</small>
																	</strong></div>
														<div>".utf8_encode($Ub[0]->direccion)."</div>
														<div>".utf8_encode($Ub[0]->piso)." ".utf8_encode($Ub[0]->referencia)."</div>
														<div>".utf8_encode($Ub[0]->comuna)." ".utf8_encode($Ub[0]->ciudad)."</div>
														
														<div>".utf8_encode($Recuento_Resumen)."</div>
													</h6>

												<div class='collapse' id='collapseEditUbicacion_".$unico->rut."'>
												  <div class='card card-body'  style='padding: 0px;'>
															<form action='?sw=mi_equipo_consolidado_contingencia' method='post'>
																	<div><small>Elegir nueva ubicaci&oacute;n</small></div>
																	<select class='form form-control' name='Ub' required>
																			<option value=''></option>
																			".$array_options_direccion."
																	</select>
																	
																	<input type='radio' id='temporalidad' name='temporalidad' value='Ubicaci&oacute;n Permanente' required>
  																<label for='ubicacion' style='font-size: 10px;display: inline;'>Ubicaci&oacute;n Permanente</label>
  																<input type='radio' id='temporalidad' name='temporalidad' value='Ubicaci&oacute;n Provisoria' required>
																	<label for='ubicacion' style='font-size: 10px;display: inline;'>Ubicaci&oacute;n Provisoria</label><br>
																	
																	<input type='hidden' name='rutEd' value='".Encodear3($unico->rut)."'
																	<input type='hidden' name='EdUb' value='1'> 
																	<input type='submit' value='Actualizar' class='btn btn-info'>
															</form>
												  </div>
												</div>


													
													";
													
											
											
											if($Us[0]->nombre_completo==""){ continue;}
											
											if($ayer3=="31-12-1969"){
												$display_ayer3=" display:none; ";
											} else {
												$display_ayer3="  ";
											}
							//if($rut=="12345" or $rut=="12254603"){
									$row_col="<br><a href='?sw=mi_equipo_consolidado_contingencia_colaborador&col=".Encodear3($unico->rut)."' class='btn btn_link_mi_equipo' style='padding: 0px;'>Ver Detalle Diario</a>";
							//} else {
									//$row_col="";
							//}
						        $row.=
						        			"
													<li class='contact'>
														<div class='row'>
															<div class='col col-lg-4'> <br>													
																<div class='wrap'>
																	<span class='contact-status online'></span>
																	<a href='#' style='display: inline;'>
																		<img class='img-fluid' src='".$imagen_usuario."' alt='".$Us[0]->nombre_completo."'>
																	</a>
																	<div class='meta'>
																			<a href='#' style='display: inline;'>
																				<h5 class='name'>".$Us[0]->nombre_completo."<br><small>".$Us[0]->cargo."</small></h5>
																			</a>
																			".$modulo_ubicacion."
																			".$alerta."
																			<div>
																					".$row_col."
																					<br>".$boton_ver_equipo."
																			</div>
																	</div>
																</div>
														</div>
														<div class='col col-lg-4' > 
															<div class='txt_2020_reporte_hoy' style='".$display_ayer3."'>
																<div class='alert alert-primary'>Reporte al Dia  <strong>".$ayer3."</strong></div></div>
																".utf8_encode($status_ayer)."
																".utf8_encode($detalle_ayer)."
														</div>
														<div class='col col-lg-4'> 
															<div class='txt_2020_reporte_hoy'>
																<div class='alert alert-primary'>Reporte al Dia de Hoy <strong>".$hoy2."</strong></div></div>
																	".utf8_encode($status_hoy)."
																	".utf8_encode($detalle_hoy)."
																	</div>			
														</div>
											</li>
										";
						        
							      $row = str_replace("{VER_EQUIPO}",   $boton_ver_equipo, $row);
							      $row = str_replace("{VER_MAS}",   $boton_ver_mas, $row);

							 
							 }
						 	$falta=$cuenta_usuario_equipo-$cuenta_con_actualizacion;
						 	if($cuenta_con_actualizacion>$cuenta_usuario_equipo and $cuenta_usuario_equipo>0){
						 		$equipo_estado_progreso="<div class='alert alert-primary' style='font-size: 11px;padding: 5px;'>Actualizaci&oacute;n Completa</div>";
						 	} else {
						 		$equipo_estado_progreso="<div class='alert alert-info'  style='font-size: 11px;padding: 5px;'>Falta actualizar informaci&oacute;n de ".$falta." colaboradores </div>";
						 		}

						$PRINCIPAL = str_replace("{EQUIPO_ACTUALIZADO_COMPLETO_HOY}", $equipo_estado_progreso, $PRINCIPAL);


						$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
						 	
						$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
						$PRINCIPAL = str_replace("{AYER_ENC}", Encodear3($ayer), $PRINCIPAL);
						$PRINCIPAL = str_replace("{HOY_ENC}",  Encodear3($hoy), $PRINCIPAL);

    return ($PRINCIPAL);
}

 function Contingencia_Individual_Estado($status){
 	
 	if($status=="Trabajando / Instalación Banco"){
 		$txt_status="<div class='alert alert-azul'>Trabajando<br>Instalación Banco</div>";
 			$txt_color="#1782BF";
 	} 
 	elseif($status=="Trabajando / Trabajo en Casa"){
 		$txt_status="<div class='alert alert-verde'>Trabajando / Trabajo en Casa</div>";
 			$txt_color="#8BB851";
 	} 

 	elseif($status=="Trabajando / Trabajo en Casa con Permiso por Precaución COVID 19"){
 		$txt_status="<div class='alert alert-verde'>Trabajando / Trabajo en Casa con Permiso por Precaución COVID 19</div>";
 		$txt_color="#8BB851";
 	} 

 	elseif($status=="No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social"){
 		$txt_status="<div class='alert alert-salmon'>No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social</div>";
 		$txt_color="#F36016";
 	} 

 	elseif($status=="No Trabajando / Licencia Medica"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Licencia Medica</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No trabajando / Sin información del colaborador"){
 		$txt_status="<div class='alert alert-gris'>No trabajando / Sin información del colaborador</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No Trabajando / Vacaciones"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Vacaciones </div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No Trabajando / otro motivo"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / otro motivo</div>";
 		$txt_color="#929292";
 	} 

 	else{
 		$txt_status="<div class='alert alert-blanco'>No Reportado</div>";
 		$txt_color="#ccc";
 	} 

return $txt_color;
 	
 }
 
 
 function Contingencia_Individual_Estado_21($status){
 	
 	


//echo "<br>-->status $status";

 	
 	if($status=="Presencial"){
 		$txt_status="<div class='alert alert-azul'>Trabajando<br>Instalación Banco</div>";
 			$txt_color="#1782BF";
 	} 
 	elseif($status=="Trabajando en Casa"){
 		$txt_status="<div class='alert alert-verde'>Trabajando / Trabajo en Casa</div>";
 			$txt_color="#8BB851";
 	} 

 	elseif($status=="Trabajando en Casa por contingencia"){
 		$txt_status="<div class='alert alert-verde'>Trabajando / Trabajo en Casa con Permiso por Precaución COVID 19</div>";
 		$txt_color="#8BB851";
 	} 

 	elseif($status=="No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social"){
 		$txt_status="<div class='alert alert-salmon'>No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social</div>";
 		$txt_color="#F36016";
 	} 

 	elseif($status=="En Casa Sin Trabajar"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Licencia Medica</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="En Casa Sin Trabajar por contingencia"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No trabajando / Sin información del colaborador"){
 		$txt_status="<div class='alert alert-gris'>No trabajando / Sin información del colaborador</div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="No Trabajando / Vacaciones"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / Vacaciones </div>";
 		$txt_color="#929292";
 	} 

 	elseif($status=="Ausente"){
 		$txt_status="<div class='alert alert-gris'>No Trabajando / otro motivo</div>";
 		$txt_color="#929292";
 	} 

 	else{
 		$txt_status="<div class='alert alert-blanco'>No Reportado</div>";
 		$txt_color="#ccc";
 	} 

return $txt_color;
 	
 }

function Contingencia_ColocaEventosCalendario($PRINCIPAL, $id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col)
{
	
	
		//echo "$rut_col";
		
		$Us=ContingenciaDatosUsuario_($rut_col, $id_empresa);
		
    $eventos          = Contingencia_ColocaEventosCalendario_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col);
    //print_r($eventos);
    $contador_eventos = 1;
    $fecha_actual     = date("Y-m-d");
    foreach ($eventos as $eve) {
      		//$eve->status
      		
      		$estado_diario="";
      		if($eve->status<>""){
      			$estado_diario=Contingencia_Individual_Estado($eve->status);
      		}
      		
        $html_evento .= file_get_contents("views/mi_equipo_consolidado/eventos/" . $id_empresa . "_row_basic-views.html");
   
  			$txt_color=Contingencia_Individual_Estado($eve->status);
   
        $html_evento = str_replace("{NOMBRE}", $eve->status, $html_evento);
        $html_evento = str_replace("{FECHA_INICIO}", $eve->fecha, $html_evento);
        $html_evento = str_replace("{FECHA_TERMINO}", $eve->fecha, $html_evento);
				$html_evento = str_replace("{URL_EXTERNA}", "", $html_evento);
        $html_evento = str_replace("{IDEVENTO}", $eve->id, $html_evento);
        $html_evento = str_replace("{EVENT_BACKGROUND_COLOR}", $txt_color, $html_evento);
        
        
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


function Contingencia_ColocaEventosCalendario_21($PRINCIPAL, $id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col)
{
	
	
		//echo "$rut_col";
		
		$Us=ContingenciaDatosUsuario_($rut_col, $id_empresa);
		
    $eventos          = Contingencia_ColocaEventosCalendario_21_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col);
    //print_r($eventos);
    $contador_eventos = 1;
    $fecha_actual     = date("Y-m-d");
    foreach ($eventos as $eve) {
      		//$eve->status
      		
      		$estado_diario="";
      		if($eve->status<>""){
      			$estado_diario=Contingencia_Individual_Estado($eve->status);
      		}
      		
        $html_evento .= file_get_contents("views/mi_equipo_consolidado/eventos/" . $id_empresa . "_row_basic-views.html");
   
  			$txt_color=Contingencia_Individual_Estado_21($eve->regimen);
   
        $html_evento = str_replace("{NOMBRE}", $eve->regimen." ".$eve->motivo, $html_evento);
        $html_evento = str_replace("{FECHA_INICIO}", $eve->fecha, $html_evento);
        $html_evento = str_replace("{FECHA_TERMINO}", $eve->fecha, $html_evento);
				$html_evento = str_replace("{URL_EXTERNA}", "", $html_evento);
        $html_evento = str_replace("{IDEVENTO}", $eve->id, $html_evento);
        $html_evento = str_replace("{EVENT_BACKGROUND_COLOR}", $txt_color, $html_evento);
        
        
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
function Contingencia_Turnos_EventosCalendario_23($PRINCIPAL, $id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col, $rut_jefe)
{
    $Us=ContingenciaDatosUsuario_($rut_col, $id_empresa);

    $eventos          = Contingencia_TurnosEventosCalendario_23_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_col, $rut_jefe);

    $contador_eventos = 1;
    $fecha_actual     = date("Y-m-d");
    foreach ($eventos as $eve) {
        //$eve->status
        $estado_diario="";
        if($eve->status<>""){
            $estado_diario=Contingencia_Individual_Estado($eve->status);
        }

        $html_evento .= file_get_contents("views/mi_equipo_consolidado/eventos/row_turnos.html");
        $txt_color=Contingencia_Individual_Estado_21($eve->regimen);

        if($eve->modalidad=="Presencial"){
            $txt_color="#192857";
        } else {
            $txt_color="#03c2ff";
        }


        $html_evento = str_replace("{NOMBRE}", $eve->nombre_completo." ".$eve->modalidad, $html_evento);
        $html_evento = str_replace("{FECHA_INICIO}", $eve->fecha, $html_evento);
        $html_evento = str_replace("{FECHA_TERMINO}", $eve->fecha, $html_evento);
        $html_evento = str_replace("{URL_EXTERNA}", "", $html_evento);
        $html_evento = str_replace("{IDEVENTO}", $eve->id, $html_evento);
        $html_evento = str_replace("{EVENT_BACKGROUND_COLOR}", $txt_color, $html_evento);

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

function MiEquipo2020_Colaboradores_equipo_consolidado_contingencia_colaborador($PRINCIPAL, $id_empresa, $rut)
{


  //calendario



		$PRINCIPAL = str_replace("{EQUIPO_ACTUALIZADO_COMPLETO_HOY}", $equipo_estado_progreso, $PRINCIPAL);


		$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
		 	
		$PRINCIPAL = str_replace("{ROW_LISTA_MI_EQUIPO_2020}", $row, $PRINCIPAL);
		$PRINCIPAL = str_replace("{AYER_ENC}", Encodear3($ayer), $PRINCIPAL);
		$PRINCIPAL = str_replace("{HOY_ENC}",  Encodear3($hoy), $PRINCIPAL);

    return ($PRINCIPAL);
}


<?php
function DatosCamposOrganizacionalesDadoCargo($cargo) {
    $connexion = new DatabasePDO();

    $sql = "SELECT DISTINCT cui, descripcion_cui FROM tbl_sillas_planificacion_foto WHERE division = :id_division ORDER BY cui ASC";


    $connexion->query($sql);
    $connexion->bind(':id_division', $id_division);
    $resultado = $connexion->resultset();
    return $resultado;
}

function PlanificadorUltimoProcesoData($id, $division, $arreglo_post, $cui_filtro, $cargo_filtro) {

   

    if($arreglo_post){
        $connexion = new DatabasePDO();

        if($arreglo_post["division"]){
            $filtro_division="and division='".$arreglo_post["division"]."'";
        }

        if($arreglo_post["area"]){
            $filtro_area="and area='".$arreglo_post["area"]."'";
        }

        if($arreglo_post["departamento"]){
            $filtro_departamento="and departamento='".$arreglo_post["departamento"]."'";
        }

        if($arreglo_post["zona"]){
            $filtro_zona="and zona='".$arreglo_post["zona"]."'";
        }

        if($arreglo_post["seccion"]){
            $filtro_seccion="and seccion='".$arreglo_post["seccion"]."'";
        }
        if($arreglo_post["oficina"]){
            $filtro_oficina="and oficina='".$arreglo_post["oficina"]."'";
        }

        if($arreglo_post["unidad"]){
            $filtro_unidad="and tbl_sillas_planificacion_foto.cui='".$arreglo_post["unidad"]."'";
        }

        if($arreglo_post["cui"]){
            $filtro_unidad_ingresada="and tbl_sillas_planificacion_foto.cui='".$arreglo_post["cui"]."'";
        }

        if($arreglo_post["division"]=="1645"){
            if(!$arreglo_post["area"]){
                echo "<script>
                        alert('Te recordamos que en la Division comercial debes seleccionar el Area');
                        location.href='?sw=planificador_index';
</script>";
                exit;
            }
        }

        if($arreglo_post["division"]=="1900"){
            if(!$arreglo_post["area"]){
                echo "<script>
                        alert('Te recordamos que para  DIVOT debes seleccionar el Area');
                        location.href='?sw=planificador_index';
</script>";
                exit;
            }
        }

        if($arreglo_post["cargo"]){
            $cantidad_cargos=count($arreglo_post["cargo"]);

            $filtro_por_cargo=" and (";
            for($i=1;$i<=$cantidad_cargos;$i++){

                $filtro_por_cargo.=" tbl_sillas_planificacion_foto.cargo='".$arreglo_post["cargo"][$i-1]."'";
                if($i<$cantidad_cargos){
                    $filtro_por_cargo.=" or ";
                }

            }
            $filtro_por_cargo.=" )";
        }





        $sql = "SELECT
	tbl_sillas_planificacion_foto.*, tbl_sillas_planificacion_comentario_controller.comentario, comentario_admin
FROM
	tbl_sillas_planificacion_foto 

left join tbl_sillas_planificacion_comentario_controller on tbl_sillas_planificacion_comentario_controller.cui=tbl_sillas_planificacion_foto.cui 
and tbl_sillas_planificacion_comentario_controller.cargo=tbl_sillas_planificacion_foto.cargo
	
	
	
WHERE
	tbl_sillas_planificacion_foto.id_proceso = '$id'
    $filtro_division  $filtro_area $filtro_departamento $filtro_zona $filtro_seccion $filtro_oficina $filtro_unidad $filtro_unidad_ingresada $filtro_por_cargo $filtro_cui_cargo 
	  ";


        $connexion->query($sql);
        //$connexion->bind(':id', $id);
        $cod = $connexion->resultset();
        
        
    }else{
        $connexion = new DatabasePDO();

        
        if($cui_filtro && $cargo_filtro){

            $sql = "SELECT
	tbl_sillas_planificacion_foto.*, tbl_sillas_planificacion_comentario_controller.comentario, comentario_admin
FROM
	tbl_sillas_planificacion_foto 

left join tbl_sillas_planificacion_comentario_controller on tbl_sillas_planificacion_comentario_controller.cui=tbl_sillas_planificacion_foto.cui 
and tbl_sillas_planificacion_comentario_controller.cargo=tbl_sillas_planificacion_foto.cargo
	
	
	
WHERE
	tbl_sillas_planificacion_foto.id_proceso = '$id'
     and (tbl_sillas_planificacion_foto.cui='$cui_filtro' and tbl_sillas_planificacion_foto.cargo='$cargo_filtro')
	  ";

        }


        $connexion->query($sql);
        //$connexion->bind(':id', $id);
        $cod = $connexion->resultset();

    }





   

    //print_r($cod);

    return $cod;

}

function actualizoCuiDeProcesoSeleccion($id_proceso_seleccion, $nuevo_cui) {

    $connexion = new DatabasePDO();


    $sql = "UPDATE tbl_sillas_planificacion_diario_procesos SET cui = :nuevo_cui WHERE id_proceso_seleccion = :id_proceso_seleccion";

    $stmt = $pdo->prepare($sql);

// Asignar valores a los parámetros
    $stmt->bindParam(':nuevo_cui', $nuevo_cui);
    $stmt->bindParam(':id_proceso_seleccion', $id_proceso_seleccion);

// Ejecutar la consulta
    $stmt->execute();


    $sql = "UPDATE tbl_sillas_planificacion_procesos_seleccion SET cod_cui = :nuevo_cui WHERE proceso = :id_proceso_seleccion";
    $stmt = $pdo->prepare($sql);

// Asignar valores a los parámetros
    $stmt->bindParam(':nuevo_cui', $nuevo_cui);
    $stmt->bindParam(':id_proceso_seleccion', $id_proceso_seleccion);

// Ejecutar la consulta
    $stmt->execute();
    


}


function TRaigoCuisPorDivision($id_division) {

    $connexion = new DatabasePDO();


    $sql = "SELECT DISTINCT cui, descripcion_cui FROM tbl_sillas_planificacion_foto WHERE division = :id_division ORDER BY cui ASC";


    $connexion->query($sql);
    $connexion->bind(':id_division', $id_division);
    $cod = $connexion->resultset();


    return $cod;

}
function TRaigoIdDatosDivisionDadoIdCUI($id_cui) {

    $connexion = new DatabasePDO();

    $sql = "SELECT division, descripcion_division FROM tbl_sillas_planificacion_foto WHERE cui = :id_cui LIMIT 1";

    $connexion->query($sql);
    $connexion->bind(':id_cui', $id_cui);
    $cod = $connexion->resultset();





    return $cod;

}

function PlanificacionUpdateDiarioDesdeSeleccion($ano, $mes, $cui, $cargo, $cantidad, $id_seleccion) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $autor = $_SESSION["user_"];
    $sql = "UPDATE tbl_sillas_planificacion_diario_procesos SET ano=:ano, mes=:mes, cui=:cui, cargo=:cargo WHERE id_proceso_seleccion=:id_seleccion";
    $connexion->query($sql);
    $connexion->bind(':ano', $ano);
    $connexion->bind(':mes', $mes);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':id_seleccion', $id_seleccion);
    $connexion->execute();
}

function EliminaRegistroProcesoParalelo($id_proceso){
    $connexion = new DatabasePDO();
    $sql = "DELETE FROM tbl_sillas_planificacion_diario_procesos WHERE id_proceso_seleccion=:id_proceso";
    $connexion->query($sql);
    $connexion->bind(':id_proceso', $id_proceso);
    $connexion->execute();
}

function VerificoProcesoEnTablaDiariaPorIdProceso($id_proceso) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sillas_planificacion_diario_procesos WHERE id_proceso_seleccion=:id_proceso";
    $connexion->query($sql);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}

function PlanificacionVerificaDiarioPorProcesoPorMesAnoCargoCui($ano, $mes, $cui, $cargo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as total FROM tbl_sillas_planificacion_diario_procesos WHERE ano=:ano AND mes=:mes AND cui=:cui AND cargo=:cargo";
    $connexion->query($sql);
    $connexion->bind(':ano', $ano);
    $connexion->bind(':mes', $mes);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;
}


function PlanificacionInsertaDiarioDesdeSeleccion($ano, $mes, $cui, $cargo, $cantidad, $id_seleccion) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $autor=$_SESSION["user_"];
    $sql = "INSERT INTO tbl_sillas_planificacion_diario_procesos (ano, mes, cargo, cui, cantidad, fecha, hora, autor, id_proceso_seleccion, tipo_proceso) VALUES (:ano,:mes,:cargo,:cui,:cantidad,:fecha,:hora,:autor,:id_seleccion,'SELECCION')";
    $connexion->query($sql);
    $connexion->bind(':ano', $ano);
    $connexion->bind(':mes', $mes);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cantidad', $cantidad);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':autor', $autor);
    $connexion->bind(':id_seleccion', $id_seleccion);
    $connexion->execute();
}

function lanificador_ProcesosDeSeleccionCuiCargo($cui, $cargo, $id_proceso_seleccion) {
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_sillas_planificacion_procesos_seleccion.*, tbl_sillas_planificacion_diario_procesos.ano, tbl_sillas_planificacion_diario_procesos.mes FROM tbl_sillas_planificacion_procesos_seleccion LEFT JOIN tbl_sillas_planificacion_diario_procesos ON tbl_sillas_planificacion_diario_procesos.cui = tbl_sillas_planificacion_procesos_seleccion.cod_cui AND tbl_sillas_planificacion_diario_procesos.cargo = tbl_sillas_planificacion_procesos_seleccion.cod_cargo and tbl_sillas_planificacion_diario_procesos.id_proceso_seleccion=:id_proceso_seleccion WHERE cod_cargo = :cargo AND cod_cui = :cui";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':id_proceso_seleccion', $id_proceso_seleccion);
    $cod = $connexion->resultset();
    return $cod;
}

function TraigoDatosDiarios($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_sillas_planificacion_diario.*, (SELECT comentario FROM tbl_sillas_planificacion_comentario_controller WHERE tbl_sillas_planificacion_comentario_controller.cui=tbl_sillas_planificacion_diario.cui AND tbl_sillas_planificacion_comentario_controller.cargo=tbl_sillas_planificacion_diario.cargo) AS total_comentarios FROM tbl_sillas_planificacion_diario WHERE autor = :rut AND (estado IS NULL OR estado = '')";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function EliminaControllerPlanificador($id){
    $connexion = new DatabasePDO();
    $sql = "DELETE from tbl_sillas_planificacion_controller where id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function actualizaEditableController($rut, $division, $editable){
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_sillas_planificacion_controller SET editable=:editable WHERE rut=:rut AND codigo_division=:division";
    $connexion->query($sql);
    $connexion->bind(':editable', $editable);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':division', $division);
    $connexion->execute();
}

function FiltrosPlanificacionQueryOrder($campo_filtrado, $campo_a_buscar, $valor, $campo_a_buscar_id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT($campo_a_buscar) AS valor, $campo_a_buscar_id AS valor_id FROM tbl_sillas_planificacion_foto WHERE $campo_filtrado=:valor AND $campo_a_buscar<>'' ORDER BY $campo_a_buscar_id ASC";
    $connexion->query($sql);
    $connexion->bind(':valor', $valor);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function EliminaCombinacionCuiCargo($cui, $cargo){

    $connexion = new DatabasePDO();
    $sql = "DELETE FROM tbl_sillas_planificacion_foto WHERE cui = :cui AND cargo = :cargo";

    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->execute();
}


function ListadoTotalLOGS() {
    $connexion = new DatabasePDO();
    $sql = "SELECT * 
            FROM tbl_sillas_planificacion_diario_LOG 
            INNER JOIN tbl_sillas_planificacion_foto ON tbl_sillas_planificacion_foto.cui = tbl_sillas_planificacion_diario_LOG.cui 
            AND tbl_sillas_planificacion_foto.cargo = tbl_sillas_planificacion_diario_LOG.cargo";

    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}


function PlanificacionInsertaDiarioLOG($ano, $mes, $cui, $cargo, $cantidad) {
    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $autor = $_SESSION["user_"];

    $sql = "INSERT INTO tbl_sillas_planificacion_diario_LOG (ano, mes, cargo, cui, cantidad, fecha, hora, autor)
            VALUES (:ano, :mes, :cargo, :cui, :cantidad, :fecha, :hora, :autor)";
    $connexion->query($sql);
    $connexion->bind(':ano', $ano);
    $connexion->bind(':mes', $mes);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cantidad', $cantidad);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':autor', $autor);
    $connexion->execute();
}


function TraigoCombinacionesCuiCargoPorCuiCargoProceso($cui, $cargo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sillas_planificacion_foto WHERE cui=:cui AND cargo=:cargo AND id_proceso='6'";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;
}

function TRaigoDatosCargoPlan($cargo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT silla_cargo FROM tbl_usuario WHERE silla_id_cargo=:cargo";
    $connexion->query($sql);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;
}

function TraigoCombinacionesCuiCargo() {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sillas_planificacion_foto WHERE manual='1'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaCombCuiCargo($cui, $cargo) {
    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $datos_organizacionales = DatosCamposOrganizacionalesDadoCui($connexion, $cui);
    $datos_organizacionales_cargo=DatosCamposOrganizacionalesDadoCargo($cargo);

    $detalle_cargo = TRaigoDatosCargoPlan($conexion, $cargo);

    $sql = "
    INSERT INTO tbl_sillas_planificacion_foto (cui, cargo, descripcion_cargo, plan, descripcion_cui, division, descripcion_division, area, descripcion_area, departamento, descripcion_departamento, zona, descripcion_zona, seccion, descripcion_seccion, oficina, descripcion_oficina, 
                                               id_proceso, nivel,  plazo_indefinido, plazo_fijo, manual)
    VALUES (:cui, :cargo, :descripcion_cargo, '0', :descripcion_cui, :id_division, :division, :id_area, :area, :id_departamento, :departamento, :id_zona, :zona, :id_seccion, :seccion, :id_oficina, :oficina, '6', :perfil_evaluacion,  '0', '0', '1')";

    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':descripcion_cargo', $detalle_cargo[0]->silla_cargo);
    $connexion->bind(':descripcion_cui', $datos_organizacionales[0]->silla_unidad);
    $connexion->bind(':id_division', $datos_organizacionales[0]->silla_id_division);
    $connexion->bind(':division', $datos_organizacionales[0]->silla_division);
    $connexion->bind(':id_area', $datos_organizacionales[0]->silla_id_area);
    $connexion->bind(':area', $datos_organizacionales[0]->silla_area);
    $connexion->bind(':id_departamento', $datos_organizacionales[0]->silla_id_departamento);
    $connexion->bind(':departamento', $datos_organizacionales[0]->silla_departamento);
    $connexion->bind(':id_zona', $datos_organizacionales[0]->silla_id_zona);
    $connexion->bind(':zona', $datos_organizacionales[0]->silla_zona);
    $connexion->bind(':id_seccion', $datos_organizacionales[0]->silla_id_seccion);
    $connexion->bind(':seccion', $datos_organizacionales[0]->silla_seccion);
    $connexion->bind(':id_oficina', $datos_organizacionales[0]->silla_id_oficina);
    $connexion->bind(':oficina', $datos_organizacionales[0]->silla_oficina);
    $connexion->bind(':perfil_evaluacion', $datos_organizacionales_cargo[0]->perfil_evaluacion);
    $conexion->resultset();
}

function DatosCamposOrganizacionalesDadoCui($connexion, $cui) {
    $sql = "select * from tbl_usuario where silla_id_unidad=:cui limit 1";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $cod = $connexion->resultset();
    return $cod;
}

function Filtros06_Planificacion($campo_filtrado_1, $campo_filtrado_2, $campo_filtrado_3, $campo_filtrado_4, $campo_filtrado_5, $campo_filtrado_6, $division, $area, $departamento, $zona, $seccion, $oficina, $campo_a_buscar, $campo_a_buscar_id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT $campo_a_buscar AS valor, $campo_a_buscar_id AS valor_id FROM tbl_sillas_planificacion_foto WHERE $campo_filtrado_1=:division AND $campo_filtrado_2=:area AND $campo_filtrado_3=:departamento AND $campo_filtrado_4=:zona AND $campo_filtrado_5=:seccion AND $campo_filtrado_6=:oficina AND $campo_a_buscar<>'' ORDER BY $campo_a_buscar ASC";
    $connexion->query($sql);
    $connexion->bind(':division', $division);
    $connexion->bind(':area', $area);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':zona', $zona);
    $connexion->bind(':seccion', $seccion);
    $connexion->bind(':oficina', $oficina);
    $cod = $connexion->resultset();
    return $cod;
}

function Filtros05_Planificacion($campo_filtrado_1, $campo_filtrado_2, $campo_filtrado_3, $campo_filtrado_4, $campo_filtrado_5, $division, $area, $departamento, $zona, $seccion, $campo_a_buscar, $campo_a_buscar_id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT $campo_a_buscar AS valor, $campo_a_buscar_id AS valor_id FROM tbl_sillas_planificacion_foto WHERE $campo_filtrado_1=:division AND $campo_filtrado_2=:area AND $campo_filtrado_3=:departamento AND $campo_filtrado_4=:zona AND $campo_filtrado_5=:seccion AND $campo_a_buscar<>'' ORDER BY $campo_a_buscar ASC";
    $connexion->query($sql);
    $connexion->bind(':division', $division);
    $connexion->bind(':area', $area);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':zona', $zona);
    $connexion->bind(':seccion', $seccion);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificoController($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sillas_planificacion_controller WHERE rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function TRaigoCombinacionCuiCargoMesAno($cui, $cargo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sillas_planificacion_foto WHERE cui = :cui AND cargo = :cargo AND id_proceso = '6'";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;
}

function UpdateControllerComentarioAdmin($cui, $cargo, $comentario, $rut){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $sql = "UPDATE tbl_sillas_planificacion_comentario_controller SET comentario_admin = :comentario, fecha_admin = :fecha, hora_admin = :hora WHERE cui = :cui AND cargo = :cargo";

    $connexion->query($sql);
    $connexion->bind(':comentario', nl2br($comentario));
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);

    $connexion->execute();
}

function InsertaControllerComentarioAdmin($cui, $cargo, $comentario, $rut) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $sql = "INSERT INTO tbl_sillas_planificacion_comentario_controller (cui, cargo, comentario_admin, autor, fecha_admin, hora_admin) VALUES (:cui, :cargo, :comentario, :rut, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);

    $connexion->execute();
}

function UpdateControllerComentario($cui, $cargo, $comentario, $rut){
    $connexion = new DatabasePDO();
    
    $sql = "UPDATE tbl_sillas_planificacion_comentario_controller SET comentario = :comentario WHERE cui = :cui AND cargo = :cargo";
    $connexion->query($sql);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->execute();
    }
    
function InsertaControllerComentario($cui, $cargo, $comentario, $rut) {
    $connexion = new DatabasePDO();
    
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    
    $sql = "INSERT INTO tbl_sillas_planificacion_comentario_controller (cui, cargo, comentario, autor, fecha, hora) VALUES (:cui, :cargo, :comentario, :autor, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':autor', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
    }
    
function ExisteComentarioPlanificacionCuiCargoController($cui, $cargo) {
    $connexion = new DatabasePDO();
    
    $sql = "SELECT * FROM tbl_sillas_planificacion_comentario_controller WHERE cui = :cui AND cargo = :cargo";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function ListadoUsuariosControllersTotal() {
    $connexion = new DatabasePDO();

    $sql = "SELECT
	tbl_sillas_planificacion_controller.*,
	nombre_completo,
	tbl_usuario.cargo AS descripcion_del_cargo,
	silla_division,
	silla_cargo,
	( SELECT descripcion_division FROM tbl_sillas_planificacion_foto WHERE tbl_sillas_planificacion_foto.division = tbl_sillas_planificacion_controller.codigo_division LIMIT 1 ) AS division_asignada 
FROM
	tbl_sillas_planificacion_controller
	INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_sillas_planificacion_controller.rut";

    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
    }

function InsertaController($rut, $division, $editable) {
        $connexion = new DatabasePDO();
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");
    
        $sql = "INSERT INTO tbl_sillas_planificacion_controller (rut, codigo_division, editable) VALUES (:rut, :division, :editable)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':division', $division);
        $connexion->bind(':editable', $editable);
        $connexion->execute();
    }
    
function ListadoUsuariosControllers() {
        $connexion = new DatabasePDO();
        $sql = "SELECT rut, nombre_completo, cargo FROM tbl_usuario ORDER BY nombre ASC";
        $connexion->query($sql);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function Filtros04_Planificacion($campo_filtrado_1, $campo_filtrado_2, $campo_filtrado_3, $campo_filtrado_4, $division, $area, $departamento, $zona, $campo_a_buscar, $campo_a_buscar_id) {
        $connexion = new DatabasePDO();
        $sql = "SELECT DISTINCT($campo_a_buscar) AS valor, $campo_a_buscar_id AS valor_id FROM tbl_sillas_planificacion_foto WHERE $campo_filtrado_1=:division AND $campo_filtrado_2=:area AND $campo_filtrado_3=:departamento AND $campo_filtrado_4=:zona AND $campo_a_buscar<>'' ORDER BY $campo_a_buscar ASC";
        $connexion->query($sql);
        $connexion->bind(':division', $division);
        $connexion->bind(':area', $area);
        $connexion->bind(':departamento', $departamento);
        $connexion->bind(':zona', $zona);
        $cod = $connexion->resultset();
        return $cod;
    }    

function Filtros03_Planificacion($campo_filtrado_1, $campo_filtrado_2, $campo_filtrado_3, $division, $area, $departamento, $campo_a_buscar, $campo_a_buscar_id) {
        $connexion = new DatabasePDO();
        $sql = "select distinct($campo_a_buscar) as valor,  $campo_a_buscar_id as valor_id from tbl_sillas_planificacion_foto where $campo_filtrado_1=:division and  $campo_filtrado_2=:area and  $campo_filtrado_3=:departamento and $campo_a_buscar<>'' order by $campo_a_buscar asc  ";
        $connexion->query($sql);
        $connexion->bind(':division', $division);
        $connexion->bind(':area', $area);
        $connexion->bind(':departamento', $departamento);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function Filtros02_Planificacion($campo_filtrado_1, $campo_filtrado_2, $division, $area, $campo_a_buscar, $campo_a_buscar_id) {
        $connexion = new DatabasePDO();
        $sql = "select distinct($campo_a_buscar) as valor,  $campo_a_buscar_id as valor_id from tbl_sillas_planificacion_foto where $campo_filtrado_1=:division and  $campo_filtrado_2=:area and $campo_a_buscar<>'' order by $campo_a_buscar asc  ";
        $connexion->query($sql);
        $connexion->bind(':division', $division);
        $connexion->bind(':area', $area);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function PlaificacionActualizoEstadoDiario($cui, $cargo, $estado, $comentario, $mes, $ano){
        $connexion = new DatabasePDO();
        $sql = "update tbl_sillas_planificacion_diario set estado=:estado, comentario=:comentario where cui=:cui and cargo=:cargo and mes=:mes and ano=:ano";
        $connexion->query($sql);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':comentario', $comentario);
        $connexion->bind(':cui', $cui);
        $connexion->bind(':cargo', $cargo);
        $connexion->bind(':mes', $mes);
        $connexion->bind(':ano', $ano);
        $connexion->execute();
    }
    
function FiltrosPlanificacionQuery($campo_filtrado, $campo_a_buscar, $valor, $campo_a_buscar_id) {
        $connexion = new DatabasePDO();
        $sql = "select distinct($campo_a_buscar) as valor,  $campo_a_buscar_id as valor_id from tbl_sillas_planificacion_foto where $campo_filtrado=:valor and $campo_a_buscar<>'' order by $campo_a_buscar asc  ";
        $connexion->query($sql);
        $connexion->bind(':valor', $valor);
        $cod = $connexion->resultset();
        return $cod;
    }    

function PlanificacionActualizadiario($ano, $mes, $cui, $cargo, $cantidad) {
        $connexion = new DatabasePDO();
        $sql = "UPDATE tbl_sillas_planificacion_diario SET cantidad = :cantidad, estado = null WHERE ano = :ano AND mes = :mes AND cui = :cui AND cargo = :cargo";
        $connexion->query($sql);
        $connexion->bind(':ano', $ano);
        $connexion->bind(':mes', $mes);
        $connexion->bind(':cui', $cui);
        $connexion->bind(':cargo', $cargo);
        $connexion->bind(':cantidad', $cantidad);
        $connexion->execute();
    }
    
function PlanificacionInsertaDiario($ano, $mes, $cui, $cargo, $cantidad) {
        $connexion = new DatabasePDO();
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");
        $autor = $_SESSION["user_"];
        $sql = "INSERT INTO tbl_sillas_planificacion_diario (ano, mes, cargo, cui, cantidad, fecha, hora, autor) VALUES (:ano, :mes, :cargo, :cui, :cantidad, :fecha, :hora, :autor)";
        $connexion->query($sql);
        $connexion->bind(':ano', $ano);
        $connexion->bind(':mes', $mes);
        $connexion->bind(':cargo', $cargo);
        $connexion->bind(':cui', $cui);
        $connexion->bind(':cantidad', $cantidad);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':autor', $autor);
        $connexion->execute();
    }
    
function PlanificacionVerificaDiario($ano, $mes, $cui, $cargo) {
        $connexion = new DatabasePDO();
        $sql = "SELECT * FROM tbl_sillas_planificacion_diario WHERE ano = :ano AND mes = :mes AND cui = :cui AND cargo = :cargo";
        $connexion->query($sql);
        $connexion->bind(':ano', $ano);
        $connexion->bind(':mes', $mes);
        $connexion->bind(':cui', $cui);
        $connexion->bind(':cargo', $cargo);
        $datos = $connexion->resultset();
        return $datos;
    }    

function PlanificadorDistinctCampoBK($nombre_campo, $nombre_campo_a_mostrar) {
    $connexion = new DatabasePDO();
    $es_controller = VerificoController($_SESSION["user_"]);

    if ($es_controller[0]->codigo_division > 0) {
        $_POST["division"] = $es_controller[0]->codigo_division;
    }

    $filtros_previos = "";

    if ($nombre_campo_a_mostrar == "descripcion_division") {
        if ($es_controller[0]->codigo_division > 0) {
            $filtros_previos = " and division=:division";
            $connexion->bind(":division", $_POST["division"]);
        }
    }

    if ($nombre_campo_a_mostrar == "descripcion_area") {
        if ($_POST["division"]) {
            $filtros_previos = " and division=:division";
            $connexion->bind(":division", $_POST["division"]);
        }
    }

    if ($nombre_campo_a_mostrar == "descripcion_cargo") {
        if ($_POST["division"]) {
            $filtros_previos = " and division=:division";
            $connexion->bind(":division", $_POST["division"]);
        }
    }

    if ($nombre_campo_a_mostrar == "descripcion_departamento") {
        if ($_POST["division"]) {
            $filtros_previos = " and division=:division";
            $connexion->bind(":division", $_POST["division"]);
        }

        if ($_POST["area"]) {
            $filtros_previos .= " and area=:area";
            $connexion->bind(":area", $_POST["area"]);
        }
    }

    if ($nombre_campo_a_mostrar == "descripcion_zona") {
        if ($_POST["division"]) {
            $filtros_previos = " and division=:division";
            $connexion->bind(":division", $_POST["division"]);
        }

        if ($_POST["area"]) {
            $filtros_previos .= " and area=:area";
            $connexion->bind(":area", $_POST["area"]);
        }

        if ($_POST["departamento"]) {
            $filtros_previos .= " and departamento=:departamento";
            $connexion->bind(":departamento", $_POST["departamento"]);
        }
    }

        if ($nombre_campo_a_mostrar == "descripcion_seccion") {
        if ($_POST["division"]) { $filtros_previos = " and division=:division"; $connexion->bind(':division', $_POST["division"]); }
        if ($_POST["area"]) { $filtros_previos .= " and area=:area"; $connexion->bind(':area', $_POST["area"]); }
        if ($_POST["departamento"]) { $filtros_previos .= " and departamento=:departamento"; $connexion->bind(':departamento', $_POST["departamento"]); }
        if ($_POST["zona"]) { $filtros_previos .= " and zona=:zona"; $connexion->bind(':zona', $_POST["zona"]); }
    }

    if ($nombre_campo_a_mostrar == "descripcion_oficina") {
        if ($_POST["division"]) { $filtros_previos = " and division=:division"; $connexion->bind(':division', $_POST["division"]); }
        if ($_POST["area"]) { $filtros_previos .= " and area=:area"; $connexion->bind(':area', $_POST["area"]); }
        if ($_POST["departamento"]) { $filtros_previos .= " and departamento=:departamento"; $connexion->bind(':departamento', $_POST["departamento"]); }
        if ($_POST["zona"]) { $filtros_previos .= " and zona=:zona"; $connexion->bind(':zona', $_POST["zona"]); }
        if ($_POST["seccion"]) { $filtros_previos .= " and seccion=:seccion"; $connexion->bind(':seccion', $_POST["seccion"]); }
    }

    if ($nombre_campo_a_mostrar == "descripcion_cui") {
        if ($_POST["division"]) { $filtros_previos = " and division=:division"; $connexion->bind(':division', $_POST["division"]); }
        if ($_POST["area"]) { $filtros_previos .= " and area=:area"; $connexion->bind(':area', $_POST["area"]); }
        if ($_POST["departamento"]) { $filtros_previos .= " and departamento=:departamento"; $connexion->bind(':departamento', $_POST["departamento"]); }
        if ($_POST["zona"]) { $filtros_previos .= " and zona=:zona"; $connexion->bind(':zona', $_POST["zona"]); }
        if ($_POST["seccion"]) { $filtros_previos .= " and seccion=:seccion"; $connexion->bind(':seccion', $_POST["seccion"]); }
        if ($_POST["oficina"]) { $filtros_previos .= " and oficina=:oficina"; $connexion->bind(':oficina', $_POST["oficina"]); }
    }

    $sql = "SELECT DISTINCT $nombre_campo as valor, $nombre_campo_a_mostrar as valor_a_mostrar 
            FROM tbl_usuario
            WHERE $nombre_campo_a_mostrar <> '' $filtros_previos 
            ORDER BY $nombre_campo_a_mostrar ASC";

    $sql = "SELECT DISTINCT $nombre_campo as valor, $nombre_campo_a_mostrar as valor_a_mostrar 
            FROM tbl_sillas_planificacion_foto
            WHERE $nombre_campo_a_mostrar <> '' $filtros_previos 
            ORDER BY $nombre_campo_a_mostrar ASC";

    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}


function PlanificadorDistinctCampo($nombre_campo, $nombre_campo_a_mostrar) {

    $connexion = new DatabasePDO();
    $es_controller = VerificoController($_SESSION["user_"]);

    if($es_controller[0]->codigo_division>0){
        $_POST["division"]=$es_controller[0]->codigo_division;
    }

    if($nombre_campo_a_mostrar=="descripcion_division"){
        if($es_controller[0]->codigo_division>0){
            if(count($es_controller)>1){
                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";
            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }

        }
    }

    if($nombre_campo_a_mostrar=="descripcion_area"){
        if($_POST["division"]){
            if(count($es_controller)>1 ){

                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";


            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }

        }
    }



    if($nombre_campo_a_mostrar=="descripcion_cargo"){
        if($_POST["division"]){
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";


            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }


        }
    }

    if($nombre_campo_a_mostrar=="descripcion_departamento"){
        if($_POST["division"]){
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";



            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }
        }

        if($_POST["area"]){
            $filtros_previos.=" and area='".$_POST["area"]."'";
        }

    }

    if($nombre_campo_a_mostrar=="descripcion_zona"){
        if($_POST["division"]){
            //$filtros_previos=" and division='".$_POST["division"]."'";
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";



            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }
        }

        if($_POST["area"]){
            $filtros_previos.=" and area='".$_POST["area"]."'";
        }

        if($_POST["departamento"]){
            $filtros_previos.=" and departamento='".$_POST["departamento"]."'";
        }

    }

    if($nombre_campo_a_mostrar=="descripcion_seccion"){
        if($_POST["division"]){
            //$filtros_previos=" and division='".$_POST["division"]."'";
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";


            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }
        }

        if($_POST["area"]){ $filtros_previos.=" and area='".$_POST["area"]."'"; }

        if($_POST["departamento"]){ $filtros_previos.=" and departamento='".$_POST["departamento"]."'";     }

        if($_POST["zona"]){ $filtros_previos.=" and zona='".$_POST["zona"]."'";     }

    }

    if($nombre_campo_a_mostrar=="descripcion_oficina"){
        if($_POST["division"]){
            //$filtros_previos=" and division='".$_POST["division"]."'";
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";



            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }
        }
        if($_POST["area"]){ $filtros_previos.=" and area='".$_POST["area"]."'"; }
        if($_POST["departamento"]){ $filtros_previos.=" and departamento='".$_POST["departamento"]."'";     }
        if($_POST["zona"]){ $filtros_previos.=" and zona='".$_POST["zona"]."'";     }
        if($_POST["seccion"]){ $filtros_previos.=" and seccion='".$_POST["seccion"]."'";     }

    }

    if($nombre_campo_a_mostrar=="descripcion_cui"){
        //if($_POST["division"]){ $filtros_previos=" and division='".$_POST["division"]."'";   }
        if($_POST["division"]){
            //$filtros_previos=" and division='".$_POST["division"]."'";
            if(count($es_controller)>1){


                $contador=1;
                $filtros_previos=" and (";
                foreach($es_controller as $escon){
                    $filtros_previos.=" division='".$escon->codigo_division."'";
                    if($contador<count($es_controller)){
                        $filtros_previos.=" or ";
                    }
                    $contador++;
                }
                $filtros_previos.=" )";



            }else{
                $filtros_previos=" and division='".$_POST["division"]."'";
            }
        }

        if($_POST["area"]){ $filtros_previos.=" and area='".$_POST["area"]."'"; }
        if($_POST["departamento"]){ $filtros_previos.=" and departamento='".$_POST["departamento"]."'";     }
        if($_POST["zona"]){ $filtros_previos.=" and zona='".$_POST["zona"]."'";     }
        if($_POST["seccion"]){ $filtros_previos.=" and seccion='".$_POST["seccion"]."'";     }
        if($_POST["oficina"]){ $filtros_previos.=" and oficina='".$_POST["oficina"]."'";     }


    }









    $sql = "select 
        distinct($nombre_campo) as valor,  
        $nombre_campo_a_mostrar as valor_a_mostrar 

            from tbl_sillas_planificacion_foto  
            
        where  
           $nombre_campo_a_mostrar<>''
        
            $filtros_previos 
        
        
        order by $nombre_campo_a_mostrar asc  ";


    
    $connexion->query($sql);
    $cod = $connexion->resultset();


    return $cod;

}
function PlanificadorUltimoProceso() {
    $connexion = new DatabasePDO();
    $sql = "SELECT MAX(id_proceso) AS id_proceso FROM tbl_sillas_planificacion_foto";
    $connexion->query($sql);
    $cod = $connexion->single();

    return $cod;
}

function PlanificadorUltimoProcesoDataBK($id, $division, $arreglo_post) {


    if($arreglo_post){
        $connexion = new DatabasePDO();
        if($arreglo_post["division"]){
            $filtro_division="and division='".$arreglo_post["division"]."'";
        }
    
        if($arreglo_post["area"]){
            $filtro_area="and area='".$arreglo_post["area"]."'";
        }
    
        if($arreglo_post["departamento"]){
            $filtro_departamento="and departamento='".$arreglo_post["departamento"]."'";
        }
    
        if($arreglo_post["zona"]){
            $filtro_zona="and zona='".$arreglo_post["zona"]."'";
        }
    
        if($arreglo_post["seccion"]){
            $filtro_seccion="and seccion='".$arreglo_post["seccion"]."'";
        }
        if($arreglo_post["oficina"]){
            $filtro_oficina="and oficina='".$arreglo_post["oficina"]."'";
        }
    
        if($arreglo_post["unidad"]){
            $filtro_unidad="and tbl_sillas_planificacion_foto.cui='".$arreglo_post["unidad"]."'";
        }
    
        if($arreglo_post["cui"]){
            $filtro_unidad_ingresada="and tbl_sillas_planificacion_foto.cui='".$arreglo_post["cui"]."'";
        }
    
        if($arreglo_post["division"]=="1645"){
            if(!$arreglo_post["area"]){
                echo "<script>
                            alert('Te recordamos que en la Division comercial debes seleccionar el Area');
                            location.href='?sw=planificador_index_2022';
                </script>";
                exit;
            }
        }
    
        if($arreglo_post["cargo"]){
            $cantidad_cargos=count($arreglo_post["cargo"]);
    
            $filtro_por_cargo=" and (";
            for($i=1;$i<=$cantidad_cargos;$i++){
    
                $filtro_por_cargo.=" tbl_sillas_planificacion_foto.cargo='".$arreglo_post["cargo"][$i-1]."'";
                if($i<$cantidad_cargos){
                    $filtro_por_cargo.=" or ";
                }
    
            }
            $filtro_por_cargo.=" )";
        }
    
    
        $sql = "SELECT
            tbl_sillas_planificacion_foto.*, tbl_sillas_planificacion_comentario_controller.comentario, comentario_admin
        FROM
            tbl_sillas_planificacion_foto 
    
        left join tbl_sillas_planificacion_comentario_controller on tbl_sillas_planificacion_comentario_controller.cui=tbl_sillas_planificacion_foto.cui 
        and tbl_sillas_planificacion_comentario_controller.cargo=tbl_sillas_planificacion_foto.cargo
    
    
    
        WHERE
            tbl_sillas_planificacion_foto.id_proceso = :id
            $filtro_division  $filtro_area $filtro_departamento $filtro_zona $filtro_seccion $filtro_oficina $filtro_unidad $filtro_unidad_ingresada $filtro_por_cargo
          ";
    
        $connexion->query($sql);
        //$connexion->bind(':id', $id);
        $cod = $connexion->resultset();
    }
    

    
    //print_r($cod);
    
    return $cod;
    }

?>
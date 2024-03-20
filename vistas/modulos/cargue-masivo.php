<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataCliente'])) {
    $tipo       = $_FILES['dataCliente']['type'];
    $tamanio    = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lineas     = file($archivotmp);
    $i = 0;

    foreach ($lineas as $linea) {
        $cantidad_registros = count($lineas);
        $cantidad_regist_agregados = ($cantidad_registros - 1);

        if ($i != 0) {

            $datos = explode(";", $linea);
            $codigo = !empty($datos[0]) ? $datos[0] : '';
            $stock = !empty($datos[5]) ? intval($datos[5]) : 0; 
            /* $codigo = !empty($datos[0]) ? "'" . $datos[0] . "'" : "''";
            $stock = !empty($datos[5]) ? intval($datos[5]) : 0;
 */
            $checkCodigoDuplicado = "SELECT codigo FROM productos WHERE codigo = :codigo ";
            $stmtCheck = Conexion::conectar()->prepare($checkCodigoDuplicado);
            $stmtCheck->bindParam(':codigo', $codigo);
            $stmtCheck->execute();
            $cantDuplicados = $stmtCheck->rowCount();

            // No existe registros duplicados
            if ($cantDuplicados != 0) {
                $updateData = "UPDATE productos SET stock = :stock WHERE codigo = :codigo";
                $stmtUpdate = Conexion::conectar()->prepare($updateData);
                $stmtUpdate->bindParam(':stock', $stock);
                $stmtUpdate->bindParam(':codigo', $codigo);
                $stmtUpdate->execute();
            }
        }

        $i++;
    }
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
       
        setTimeout(function() {
            Swal.fire({
                title: 'Cargue Masivo completado!',
                text: 'El cargue se ha completado correctamente',
                icon: 'success'
            });
        }, 40000);
        window.location.href = 'movimientos';
    </script>";

}else{
    echo "<script>
        
        setTimeout(function() {
            Swal.fire({
                title: 'Error en el Cargue!',
                text: 'Ha ocurrido un Error en la carga del docuemento',
                icon: 'success'
            });
        }, 40000);
        window.location.href = 'movimientos';
    </script>";
}

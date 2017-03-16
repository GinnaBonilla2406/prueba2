<?php

    /**
     * Este php me permite usar el poder del php con un ejemplo de la tecnología AngularJS...
     * incluso desde el administrador de este sitio.
     */

    if( isset( $_GET[ 'cadena' ] ) )
    {   
     
        include( "config.php" );
        
        /*Esta conexión se realiza para la prueba con angularjs*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        
        $conn = new mysqli( $servidor, $usuario, $clave, $bd );
        
        //Se busca principalmente por alias.
        $sql="";
            $sql.=  "SELECT enfermedad , COUNT(t1.id_enfermedad) as conteo_sintomas , ";
            $sql.= " (SELECT COUNT(t4.id_enfermedad) conteo_total ";
            $sql.= " FROM tb_resultado t4 ";
            $sql.= "  where t1.id_enfermedad = t4.id_enfermedad ";
            $sql.= " GROUP BY id_enfermedad) as conteo_total  ";
            $sql.= " FROM tb_resultado t1, tb_enfermedades t2 ";
            $sql.= " WHERE t1.id_enfermedad = t2.id_enfermedad AND id_signo in (".$_GET['cadena'] .") ";
            $sql.= " GROUP BY t1.id_enfermedad";
        //echo $sql;
        
        //La tabla que se cree debe tener la tabla aquí requerida, y los campos requeridos abajo.
        $result = $conn->query( $sql );
        
        $outp = "";
        
        while($rs = mysqli_fetch_assoc($result)) 
        {
            //Mucho cuidado con esta sintaxis, hay una gran probabilidad de fallo con cualquier elemento que falte.
            if ($outp != "") {$outp .= ",";}
            $outp .= '{"Alias":"'.$rs["enfermedad"].'",';            // <-- La tabla MySQL debe tener este campo.
            $outp .= '"Nombres":"'.$rs["conteo_sintomas"].'",'; 
             $outp .= '"Documento":"'.$rs["conteo_total"].'"}';        // <-- La tabla MySQL debe tener este campo.
           // $outp .= '"Apellidos":"'.$rs["apellidos"].'"';     // <-- La tabla MySQL debe tener este campo.
        }
        
        $outp ='{"records":['.$outp.']}';
        $conn->close();
        
        echo($outp);
    
    }
?> 
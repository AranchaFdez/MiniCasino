<?php
session_start();
$visitas=0;
//si esta declarada la cookie vuelca el valor en la variable visitas 
if(isset($_COOKIE['visitas'])){
    $visitas= $_COOKIE['visitas'];
}
function generarNumero(){
    return mt_rand(1,100);
}
function generarResultado($num) {
    $isPar="Impar";
    if($num%2==0) $isPar="Par";
       return $isPar;
}
?>
<html>
<head><title>Casino</title>
</head>
<body>
<div>
    <?php 
    //si no esta declarada la sesion muestra este formulario
    //Mensaje de bienvenida y la cantidad con la que quiere jugar 
    if(!isset($_SESSION['cantidadInicio'])){
    	echo "<h1>BIENVENIDO AL CASINO!!!</h1>";
    	echo "Nº Visitas:  ".$visitas;
    	echo"<form method='post' >";
    	echo"Saldo inicial :<input type='number' name='cantidadInicial'>";
    	echo"</form>";
    	//si la cantidad es superior a 0 y esta declarada  refresca la pag
    	if(!empty($_POST['cantidadInicial'])&& isset($_POST['cantidadInicial'])&&$_POST['cantidadInicial']>0){
    	    $_SESSION['cantidadInicio'] = $_POST['cantidadInicial'];
    	    $visitas++;
    	    setcookie('visitas', $visitas, time() + 30*24*3600);//un mes
    	    header("refresh: 0;"); // refresca la página
    	    
    	}
    }else{
        //si esta declaraca la session volcamos en una variable la sesion y mostramos otro formulario
        $cantidad=$_SESSION['cantidadInicio'];
       
        echo "<h3>Tu Saldo es : ".$cantidad."</h3>";
        echo"<form method='post' >";
        echo"Tu apuesta es: <input type='number' name='apuesta'><br>";
        echo"Tipo de apuesta :  Par<input type='radio' value='Par' name='par'>  Impar<input type='radio' value='Impar' name='impar'><br>";
        echo "<input type='submit' value='Apostar Cantidad' name='enviar'><input type='submit' value='Abandonar Casino 'name='salir'>";
        echo"</form>";
    }
    if(isset($_POST['salir'])){
        echo "<h3>Su resultado final es de ".$_SESSION['cantidadInicio']."€ </h3>";
        session_destroy();
        header("refresh: 3;");
        exit();
    }
    if(!empty($_POST['apuesta'])&& isset($_POST['apuesta'])&&$_POST['apuesta']>0){
        if($_POST['apuesta']<=$_SESSION['cantidadInicio']){
        $num=generarNumero();  
        if(isset($_POST['par'])|| isset($_POST['impar'])){
            if(($num%2==0)==isset($_POST['par'])){
                $resultado="Has ganado!!";
                $_SESSION['cantidadInicio']+=$_POST['apuesta'];
            }elseif(!($num%2==0)==isset($_POST['impar'])){
                $resultado="Has ganado!!";
                $_SESSION['cantidadInicio']+=$_POST['apuesta'];
            }else{
                $resultado="Has perdido!!";
                $_SESSION['cantidadInicio']-=$_POST['apuesta'];
            }
            header("refresh: 3;"); // refresca la página
            echo "El resultado de la apuesta es :".generarResultado($num);
            echo "<h2>".$resultado."</h2>";
            }
        }else{
            echo "<h3 style='color: red;'>No puede apostar mas del saldo</h3>";
        }    
        if( $_SESSION['cantidadInicio']==0  ){
            echo "<h3 style='color: red;'>No puede seguir jugando.No tiene saldo</h3>";
            session_destroy();
            exit();
        }
    } 
    ?>
</div>
</body>
</html>

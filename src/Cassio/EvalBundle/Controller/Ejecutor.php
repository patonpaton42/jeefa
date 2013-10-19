<?php

namespace Cassio\EvalBundle\Controller;
//define("C", "1");
//define("CPP", "2");

class Ejecutor
{
  private $nombre_ejecutable;
  //private $path_codigo_fuente;
  private $comando_p_compilar;
  private $tiempo_maximo_ejecucion; # en segundos

  public function getComandoPCompilar()
  {
    return $this->comando_p_compilar;
  }

  public function getNombreEjecutable()
  {
    return $this->nombre_ejecutable;
  }

  public function __construct($leng, $cod_fuente, $memoria_limite, $tiempo_maximo_ejecucion)
  {
    $this->nombre_ejecutable = $cod_fuente.'.bin';
    $this->memoria_limite = $memoria_limite;
    $this->tiempo_maximo_ejecucion = $tiempo_maximo_ejecucion;

    switch($leng)
    {
      case "C": 
        $this->comando_p_compilar = "gcc ".$cod_fuente." -o ".$this->nombre_ejecutable;
        break;
      case "C++":
        $this->comando_p_compilar = "g++ ".$cod_fuente." -o ".$this->nombre_ejecutable;
        break;
    }
    //echo $this->comando_p_compilar."<br>";
  }

  private function timeMemParser($command_out)
  {
    $datos = array();
    $tiempo = -1;
    $memoria = -1;
    $regexp = "/u:(\d+\.\d+)\ss:(\d+\.\d+)\sm:(\d+)/";
    if (preg_match($regexp, $command_out, $matches)) {
      $tiempo = $matches[1] + $matches[2];
      $memoria = $matches[3];
    }

    $datos['tiempo'] = $tiempo;
    $datos['memoria'] = $memoria;

    return $datos;
  }

  //retorna 0 si todo esta bien
  public function compilar()
  {
    //$salida = shell_exec($this->comando_p_compilar);
    exec($this->comando_p_compilar, $output, $return);
    return $return;
  }
  public function compararSalidas_orig($path_archivo_salida, $path_salida_ejecucion)
  {
    $archivo_salida_tc = fopen($path_archivo_salida, "r") or exit("No se puede abrir el archivo!");
    $archivo_salida_ej = fopen($path_salida_ejecucion, "r") or exit("No se puede abrir el archivo!");
    $iguales = "true";
    //echo "path_archivo_salida ".$path_archivo_salida."<br>";
    //echo "path_salida_ejecucion ".$path_salida_ejecucion."<br>";

    //Output a line of the file until the end is reached
    while(!feof($archivo_salida_tc) || !feof($archivo_salida_ej))
    {
      $linea_f1 = fgets($archivo_salida_tc);
      $linea_f2 = fgets($archivo_salida_ej);
      //echo "Linea f1: ".$linea_f1."\n";
      //echo "Linea f2: ".$linea_f2."\n";

      if($linea_f1 != $linea_f2)
      {
        $iguales = false;
        break;
      }
    }
    fclose($archivo_salida_tc);
    fclose($archivo_salida_ej);

    return $iguales;
  }
  public function compararSalidas($path_archivo_salida, $path_salida_ejecucion)
  {
    $archivo_salida_tc = fopen($path_archivo_salida, "r") or exit("No se puede abrir el archivo!");
    $archivo_salida_ej = fopen($path_salida_ejecucion, "r") or exit("No se puede abrir el archivo!");
    $iguales = "true";
    //echo "path_archivo_salida ".$path_archivo_salida."<br>";
    //echo "path_salida_ejecucion ".$path_salida_ejecucion."<br>";

    //Output a line of the file until the end is reached
    while(!feof($archivo_salida_tc) || !feof($archivo_salida_ej))
    {
      $linea_f1 = fgets($archivo_salida_tc);
      $linea_f2 = fgets($archivo_salida_ej);
      //echo "Linea f1: ".$linea_f1."\n";
      //echo "Linea f2: ".$linea_f2."\n";

      if($linea_f1 != $linea_f2)
      {
        $iguales = false;
        break;
      }
    }
    fclose($archivo_salida_tc);
    fclose($archivo_salida_ej);

    return $iguales;
  }

  public function validarEjecucion($path_archivo_entrada)
  {
    $salida = array();
    $salida['tiempo'] = false;
    $salida['memoria'] = false;
    $salida['res_ejecucion'] = "";

    #$linea_ejecucion = $this->nombre_ejecutable.' < '.$path_entrada;
    #TODO arreglar los nombres
  
    //puede fallar esto, deshabilitando
    //$comando_mem_limite = 'ulimit -Sv '.$this->memoria_limite;
    $comando_tiempo_ejecucion = ' /usr/bin/time -f "u:%U s:%S m:%M" '; 
    $comando_ejecutar_solucion = $this->nombre_ejecutable.' < '.$path_archivo_entrada.' 2>&1 1> '.$this->nombre_ejecutable.'.out';
  
    $linea_ejecucion = $comando_tiempo_ejecucion.$comando_ejecutar_solucion;

    //echo "\nLinea ejecucion: ". $linea_ejecucion."\n";
    $salida_ejecucion = exec($linea_ejecucion);
    //echo "salida_ejec: ".$salida_ejecucion."\n";
    $datos_de_ejecucion = $this->timeMemParser($salida_ejecucion);
    //print_r($datos_de_ejecucion);

    if ($datos_de_ejecucion['tiempo'] >= 0 && $datos_de_ejecucion['memoria'] >= 0)
    {
      //echo "tiempo: ";
      //print_r($this->tiempo_maximo_ejecucion);
      //echo "memoria: ";
      //print_r($this->memoria_limite);

      $salida['tiempo'] = ($datos_de_ejecucion['tiempo'] <= $this->tiempo_maximo_ejecucion) ? true: false;
      $salida['memoria'] = ($datos_de_ejecucion['memoria'] <= $this->memoria_limite) ? true: false;
      $salida['res_ejecucion'] = $this->nombre_ejecutable.'.out';
      //echo "salida: ";
      //print_r($salida);
    }

    return $salida;
  }

  //corre test cases
  public function obtenerPuntaje($problema, $repository)
  {
    $puntaje = 0;
    $total_puntaje = 0;
    $test_cases = $repository->findBy(
      array('id_problema' => $problema)
    );
    //var_dump($test_cases);
    foreach ($test_cases as $test_case){
      $salida = $this->ejecutar($test_case->getEntrada());
      //echo "Salida ejecucion: ".$salida."<br>";
      //echo "TC BD: ".$test_case->getSalida()."<br>";

      //$Handle = fopen("error.log", 'w');
      //fwrite($Handle, "Salida ejecucion:\n"); 
      //fwrite($Handle, $salida);
      //fwrite($Handle, "TC BD:\n"); 
      //fwrite($Handle, $test_case->getSalida()); 
      //fclose($Handle); 

      if ($salida == $test_case->getSalida())
      {
        $puntaje++;
      }
      $total_puntaje++;
    }
    return ($puntaje / $total_puntaje) * 100;
  }
}
?>

<?php
namespace Cassio\EvalBundle\Tests\Controller;

use Cassio\EvalBundle\Controller\Ejecutor;

class EjecutorTest extends \PHPUnit_Framework_TestCase
{
  public function testConstructor()
  {
    $ejecutor = new Ejecutor("c", "codigo.c", 1024, 1);
    $comando = $ejecutor->getComandoPCompilar();
    $this->assertEquals("gcc codigo.c -o codigo.c.bin", $comando);

    $ejecutor = new Ejecutor("cpp", "codigo.cpp", 1204, 1);
    $comando = $ejecutor->getComandoPCompilar();
    //echo $comando;
    $this->assertEquals("g++ codigo.cpp -o codigo.cpp.bin", $comando);

  }

  public function testCompilar()
  {
    //prueba si existen los compiladores
    $this->assertFileExists('/usr/bin/gcc');
    $this->assertFileExists('/usr/bin/g++');

    $ejecutor = new Ejecutor("c", "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller/suma.c", 1024, 1);
    $compilador = $ejecutor->compilar();
    $this->assertFileExists('/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller/suma.c.bin');
  }
/*
  public function testEjecutar()
  {
    $path = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller";
    //para c
    $ejecutor = new Ejecutor("c", $path."/suma.c");
    $compilador = $ejecutor->compilar();
    $salida = $ejecutor->ejecutar($path."/entrada.in");
    $this->assertEquals("2+2=4", $salida);

    //para c++
    $ejecutor = new Ejecutor("cpp", $path."/suma.cpp");
    $compilador = $ejecutor->compilar();
    $salida = $ejecutor->ejecutar("2 2");
    $this->assertEquals("2+2=4", $salida);
    shell_exec("rm ".$path."/*.bin");
  }
*/
  public function testTimeMemParser()
  {
    $ejecutor = new Ejecutor("c", "suma.c", 1024, 1);

    $salida = $ejecutor->timeMemParser("u:0.01 s:0.01 m:500");
    $this->assertEquals("0.02", $salida['tiempo']);

    $salida = $ejecutor->timeMemParser("u:0.13 s:0.13 m:600");
    $this->assertEquals("0.26", $salida['tiempo']);

    $salida = $ejecutor->timeMemParser("u:1.03 s:1.03 m:700");
    $this->assertEquals("2.06", $salida['tiempo']);

    $salida = $ejecutor->timeMemParser("u:80.03 s:81.03 m:800");
    $this->assertEquals("161.06", $salida['tiempo']);

    $salida = $ejecutor->timeMemParser("sfsd");
    $this->assertEquals("-1", $salida['tiempo']);

    $salida = $ejecutor->timeMemParser("u:80.03 s:81.03 m:800");
    $this->assertEquals("800", $salida['memoria']);

    $salida = $ejecutor->timeMemParser("u:80.03 s:81.03 m:0");
    $this->assertEquals("0", $salida['memoria']);

    $salida = $ejecutor->timeMemParser("asdasd");
    $this->assertEquals("-1", $salida['memoria']);
  }
/*
  public function testValidar()
  {
    $path = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller";

    $ejecutor = new Ejecutor("c", $path."/suma.c", 1024, 1);
    $res = $ejecutor->validar($path."\entrada.in");

  }
*/
  public function testCompararSalidas()
  {
    $path = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller/";
    $path_tests = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller/ficheros";

    //$ejecutor = new Ejecutor("c", $path."/suma.c", 50024, 1);
    $ejecutor = new Ejecutor("c", $path."/suma.c", 2048, 1);

    $resultado = $ejecutor->compararSalidas($path_tests."/01_1.out", $path_tests."/01_2.out");
    $this->assertTrue($resultado);

    $resultado = $ejecutor->compararSalidas($path_tests."/02_1.out", $path_tests."/02_2.out");
    $this->assertFalse($resultado);

    $path = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller";
    //prueba de un problema real
    $res = $ejecutor->validarEjecucion($path."/entrada.in");

    if ($res['tiempo'] && $res['memoria'])
    {
      $resultado = $ejecutor->compararSalidas($path."/salida.out", $res['res_ejecucion']);
      $this->assertTrue($resultado);
    }
 
  }

  public function testValidarEjecucion()
  {
    $path = "/srv/http/cassiopeia/src/Cassio/EvalBundle/Tests/Controller/";
    $ejecutor = new Ejecutor("c", $path."/suma.c", 600, 1);

    $res = $ejecutor->validarEjecucion($path."/entrada.in");
    
    $this->assertTrue($res['tiempo']);
    $this->assertTrue($res['memoria']);

    $ejecutor = new Ejecutor("c", $path."/suma.c", 0, 0);

    $res = $ejecutor->validarEjecucion($path."/entrada.in");
    
    $this->assertTrue($res['tiempo']);
    $this->assertFalse($res['memoria']);
  }

  //public function testObtenerPuntaje() 
  //{
    
  //}
}

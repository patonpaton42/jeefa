<?php
// src/Cassio/EvalBundle/Controller/DefaultController.php
namespace Cassio\EvalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cassio\EvalBundle\Entity\SubirSol;
use Cassio\EvalBundle\Entity\Problema;

use Symfony\Component\HttpFoundation\Request;

use Cassio\EvalBundle\Controller\Ejecutor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    private function listaProblemas()
    {
        /*$repository = $this->getDoctrine()
          ->getRepository('CassioEvalBundle:Problema');
        $problemas = $repository->findAll();
        //var_dump($problemas);
        */
        //muestra solo los problemas con test cases
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT DISTINCT p.id, p.titulo
            FROM CassioEvalBundle:Problema p, CassioEvalBundle:TestCase t
            WHERE t.id_problema=p.id'
        );
        $problemas = $query->getResult();
        //var_dump($problemas);

        $lista_prob = array();
        foreach ($problemas as $problema)
        {
          $lista_prob[$problema['id']] = $problema['titulo'];
        }
        return $lista_prob;
    }
  public function listarLenguajes()
  {
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery(
        'SELECT l.id, l.nombre
         FROM CassioEvalBundle:Lenguaje l'
    );
    $lenguajes = $query->getResult();
    //var_dump($problemas);

    $lista_leng = array();
    foreach ($lenguajes as $lenguaje)
    {
      $lista_leng[$lenguaje['id']] = $lenguaje['nombre'];
    }
    return $lista_leng;
  }

  public function subirAction(Request $request)
  {
      $subirsol = new SubirSol();

      $form = $this->createFormBuilder($subirsol)
          ->add('lenguaje', 'choice', array ('choices' => $this->listarLenguajes()))
          ->add('problema', 'choice', array ('choices' => $this->listaProblemas()))
          ->add('file')
          ->add('enviar', 'submit')
          ->getForm();
      
      $form->handleRequest($request);
    
      if ($form->isValid()) {
        $subirsol->upload();

        //echo ."<br>";
        //$memoria_limite = 
        //$tiempo_maximo_ejecucion = 
        //public function __construct($leng, $cod_fuente, $memoria_limite, $tiempo_maximo_ejecucion)
        $respuesta = "Error de ejecución";

        //echo "lenguaje ".$subirsol->getLenguaje();

        $ejecutor = new Ejecutor($this->nombreLenguaje($subirsol->getLenguaje()), $subirsol->getAbsolutePath(), 1000, 1);
        //$ejecutor = new Ejecutor("c", $subirsol->getAbsolutePath(), 1000, 1);
        $puntaje = 0;
        if($ejecutor->compilar() == 0)
        {
          //$salida = $ejecutor->ejecutar("3");
          $repository = $this->getDoctrine()
            ->getRepository('CassioEvalBundle:TestCase');
          $test_cases = $repository->findBy(
          array('id_problema' => $subirsol->getProblema()));

          foreach ($test_cases as $test_case){
            $path_archivo_entrada = __DIR__.'/../../../../web/uploads/tc/'.$test_case->getPathEntrada();
            $salida = $ejecutor->validarEjecucion($path_archivo_entrada);
            //print_r($salida);
            if ($salida['tiempo'] == true && $salida['memoria'] && $salida['res_ejecucion'] != "")
            {
              $resultado = $ejecutor->compararSalidas($salida['res_ejecucion'], __DIR__.'/../../../../web/uploads/tc/'.$test_case->getPathSalida());
              //echo '$resultado: '.$resultado.'<br>';
              if($resultado)
              {
                $puntaje = $puntaje + $test_case->getPuntaje();
              }
            }
          }
          $respuesta = "OK puntaje: ".$puntaje;
        }
        $response = new Response();

        $response->setContent('<html><body><h1>'.$respuesta.'</h1></body></html>');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/html');

        // prints the HTTP headers followed by the content
        $response->send();
      }

      return $this->render('CassioEvalBundle:Default:new.html.twig', array(
          'form' => $form->createView(),
      ));
  }

  public function nombreLenguaje($id)
  {
    $repository = $this->getDoctrine()->getRepository('CassioEvalBundle:Lenguaje');
    $lenguaje = $repository->findOneById($id);
    return $lenguaje->getNombre();
  }

  public function uploadAction(Request $request)
  {
    $document = new Document();
    $form = $this->createFormBuilder($document)
        ->add('name')
        ->add('file')
        ->add('enviar', 'submit')
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        #$em = $this->getDoctrine()->getManager();
        
        $document->upload();

        #$em->persist($document);
        #$em->flush();

        #return $this->redirect($this->generateUrl(...));
    }

    return $this->render('CassioEvalBundle:Default:upload.html.twig', array('form' => $form->createView()));
  }
  public function showTCAction(Request $request)
  {
    $testcase = $this->getDoctrine()
        ->getRepository('CassioEvalBundle:TestCase')
        //->find($id);
        ->find("1");

    if (!$testcase) {
        throw $this->createNotFoundException(
            'No test case found for id '.$id
        );
    }

    echo "Entrada: ".$testcase->getEntrada()."<br>";
    echo "Salida : ".$testcase->getSalida()."<br>";
  }

  public function subirProblemaAction(Request $request)
  {
    $repository = $this->getDoctrine()
      ->getRepository('CassioEvalBundle:Usuario');
    $usuarios = $repository->findAll();

    $lista_usuarios = array();
    foreach ($usuarios as $usuario)
    {
      $lista_usuarios[$usuario->getId()] = $usuario->getNombreCompleto();
    }
    $problema = new Problema();
    $form = $this->createFormBuilder($problema)
            ->add('id_usuario', 'choice', array ('choices' => $lista_usuarios, 'label' => 'Usuario'))
            ->add('titulo', 'text', array('label' => "Título"))
            //->add('descripcion', 'textarea', array('label' => "Descripción"))
            ->add('descripcion','ckeditor', array('label' => "Descripción",
                  'config' => array(
                    'toolbar' => array(
                      array(
                        'name'  => 'document',
                        'items' => array('Source'),
                      ),
                      '/',
                      array(
                        'name'  => 'basicstyles',
                        'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                      ),
                      array(
                        'name'  => 'clipboard',
                        'items' => array('Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo'),
                      ),
                      '/',
                      array(
                        'name'  => 'paragraph',
                        'items' => array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'),
                      ),
                      '/',
                      array(
                        'name'  => 'colors',
                        'items' => array('TextColor','BGColor'),
                      ),
                      array(
                        'name'  => 'insert',
                        'items' => array('Table','HorizontalRule','SpecialChar','PageBreak'),
                      ),
                    ),
                    'uiColor' => '#ffffff',
                  //...
                  ),
            ))
            //->add('label','label')
            ->add('file','file', array('label' => "Subir solución: "))
            ->add('enviar', 'submit')
            ->getForm();


    $form->handleRequest($request);

    if ($form->isValid()) {
      $problema->upload();
      $em = $this->getDoctrine()->getManager();
      $em->persist($problema);
      $em->flush();
    }

    return $this->render('CassioEvalBundle:Default:subir.html.twig', array('form' => $form->createView()));
  }
}



<?php

namespace Cassio\EvalBundle\Controller;

use Cassio\EvalBundle\Entity\Problema;
use Cassio\EvalBundle\Entity\Autor;
use Cassio\EvalBundle\Entity\Categoria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SubirProblemaController extends Controller {

    public function subirProblemaAction(Request $request) {
        $lista_usuarios = $this->getValor('Usuario');
        $problema = new Problema();
        $form = $this->createFormBuilder($problema)
                ->add('id_usuario', 'choice', array('choices' => $lista_usuarios, 'label' => 'Usuario'))
                ->add('titulo', 'text', array('label' => "Título del problema"))
                //->add('descripcion', 'textarea', array('label' => "Descripción"))
                //->add('file')
            ->add('descripcion','ckeditor', array('required'=>true, 'label' => "Descripción",
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
                      array(
                        'name'  => 'styles',
                        'items' => array('Styles','Format'),
                      ),
                    ),
                    'uiColor' => '#ffffff',
                  //...
                  ),
            ))
            //->add('label','label')
            ->add('file','file', array('label' => "Solución propuesta: ", 'required'=>false))
                ->add('autor', 'text', array('label' => "Autor "))
                ->add('categoria', 'text', array('label' => "Tags " , 'attr' => array('class'=>'wickEnabled')))
                ->add('enviar', 'submit')
                ->getForm();

        $form->handleRequest($request);


        if ($form->isValid()) {
            $problema->upload();
            $this->guardarTags($problema->getCategoria(), $problema);
            $autor = $this->guardarAutor($problema->getAutor()); //, $problema);

            $problema->setAutorR($autor);
      
            $em = $this->getDoctrine()->getManager();
            $em->persist($problema);
            $em->flush();

        }

        $tags = $this->recuperarTags();

        return $this->render('CassioEvalBundle:Default:subir.html.twig', array('tags' => $tags, 'form' => $form->createView()));
    }

    private function getValor($nombreTabla) {
        $repository = $this->getDoctrine()->getRepository('CassioEvalBundle:' . $nombreTabla);
        $autores = $repository->findAll();
        $lista_autores = array();
        foreach ($autores as $autor) {
            $lista_autores[$autor->getId()] = $autor->getNombre();
        }
        return $lista_autores;
    }

    private function recuperarTags()
    {
        $nombreTabla = "Categoria";
        $repository = $this->getDoctrine()->getRepository('CassioEvalBundle:' . $nombreTabla);
        $categorias = $repository->findAll();
        $lista_categorias = array();
        foreach ($categorias as $categoria) {
          array_push($lista_categorias, $categoria->getNombre());
        }
        return $lista_categorias;
    }

    private function guardarTags($cadena_tags, Problema $problema)
    {
      $repository = $this->getDoctrine()->getRepository('CassioEvalBundle:Categoria');
     
      $tags = explode(",", $cadena_tags);
      foreach ( $tags as $tag)
      {
        $tag = trim($tag);
        $cat_db = $repository->findOneByNombre($tag);
        if($cat_db == null)
        {
          $categoria = new Categoria();  
          $categoria->setNombre($tag);
        }
        else
        {
          $categoria = $cat_db;
        }
        $problema->addCategoria($categoria);
      }
    }

    private function guardarAutor($nombre_autor) //, Problema $problema)
    {
        $repository = $this->getDoctrine()->getRepository('CassioEvalBundle:Autor');
        $autor = $repository->findOneByNombre($nombre_autor);
        if($autor == null)
        {
          $autor = new Autor();
          $autor->setNombre($nombre_autor);
        }        
        /*
        $em = $this->getDoctrine()->getManager();
        $em->persist($autor);
        $em->flush();*/
        return $autor;
    }
}

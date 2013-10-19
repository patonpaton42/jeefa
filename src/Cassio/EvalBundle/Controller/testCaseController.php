<?php
namespace Cassio\EvalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cassio\EvalBundle\Entity\TestCase;
use Cassio\EvalBundle\Form\testCaseType;
use Cassio\EvalBundle\Form\entradaType;
use Cassio\EvalBundle\Entity\EntradaTc;
//use Cassio\EvalBundle\Entity\Tc_out;

class testCaseController extends Controller{

 public function tcAction(Request $request)
    {
          $id_problema=$request->query->get('problema');
          $tc = new TestCase();

          $repository = $this->getDoctrine()
          ->getRepository('CassioEvalBundle:Problema');
          
          $puntaje = $this->getDoctrine()
          ->getRepository('CassioEvalBundle:TestCase');
             
          $problemas = $repository->findAll();
             $lista_prob = array();
             foreach ($problemas as $problema){
                   $lista_prob[$problema->getId()] = $problema->getTitulo();
             }
             $limite=100;
              if($id_problema!=NULL){
              $em = $this->getDoctrine()->getManager();
              $query = $em->createQuery(
                'SELECT SUM(t.puntaje)
                 FROM CassioEvalBundle:Problema p, CassioEvalBundle:TestCase t
                 WHERE t.id_problema=p.id and p.id='.$id_problema
              );
                  
                $suma_puntaje = $query->getResult();
                //var_dump($suma_puntaje);
                $limite = (100 - $suma_puntaje[0][1]);
              }
              
          $form = $this->createForm(new testCaseType,$tc)
                   ->add('id_problema', 'choice', array ('choices' => $lista_prob, 'label' => "Problema: "))
                   ->add('entrada','file')
                   ->add('salida','file')
                   ->add('guardar', 'submit');                  ;
          $form->handleRequest($request);
            if ($form->isValid()) {
                $tc->upload();
                if(!is_null($tc->getIdProblema()))
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($tc);
                    $em->flush();
                }
            }
//            if($request->getMethod()=='POST'){
//                form->bindRequest($)
//            }
            $this->get('router')->generate('subirTc',array('problema'=>1));
            
            return $this->render('CassioEvalBundle:Default:testCase.html.twig', array('problema'=>1,'limite'=>$limite,'form' => $form->createView(),));

//        $tc = new TestCase();
////        $tc_in = new Tc_in();
////        $tc_out = new Tc_out();
//        $repository = $this->getDoctrine()
//          ->getRepository('CassioEvalBundle:Problema');
//        $problemas = $repository->findAll();
//        $lista_prob = array();
//        foreach ($problemas as $problema){
//          $lista_prob[$problema->getId()] = $problema->getTitulo();
//        }
//        // $formulario = new
//
//        $form = $this->createFormBuilder($tc)
//            ->add('id_problema', 'choice', array ('choices' => $lista_prob))
//            ->add('titulo', 'text')
//            ->add('puntaje', 'integer')
////            ->add('in', 'file')
////            ->add('out', 'file')
//            ->add('guardar', 'submit')
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//            if(!is_null($tc->getIdProblema()))
//            {
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($tc,$tc_in,$tc_out);
//                $em->flush();
//            }
//        }
//       return $this->render('CassioEvalBundle:Default:subir.html.twig', array('form' => $form->createView(),));
    }
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        $builder
//            ->add('username')
//            ->add('profile', new ProfileType(), array(
//                'attr' => array(
//                    'class' => 'well'
//                )
//            ))
//            ->add('addresses', 'collection', array(
//                'type'           => new AddressType(),
//                'label'          => 'Direcciones',
//                'by_reference'   => false,
//                'prototype_data' => new Address(),
//                'allow_delete'   => true,
//                'allow_add'      => true,
//                'attr'           => array(
//                    'class' => 'row addresses'
//                )
//            ))
//        ;
//    }
//
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'SMTC\MainBundle\Entity\User'
//        ));
//    }
//
//    public function getName()
//    {
//        return 'user';
//    }
//    public function tc_inAction(){
//           $in = new EntradaTc();
//           $form = $this->createForm(new entradaType,$in)
//                   ->add('guardar', 'submit');                  ;
//          return $this->render('CassioEvalBundle:Default:subir.html.twig', array('form' => $form->createView(),));
//    }
//    public function ProbListAction(){
//           $problema = new Problema();
//           $form = $this->createForm(new ProblemaType,$problema)
//                   ->add('guardar', 'submit');                  ;
//          return $this->render('CassioEvalBundle:Default:subir.html.twig', array('form' => $form->createView(),));
//    }
}

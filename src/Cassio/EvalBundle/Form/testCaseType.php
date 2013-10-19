<?php
namespace Cassio\EvalBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
class testCaseType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $opcion)
    {
        $builder->add('id_problema')
                ->add('titulo')
                ->add('puntaje','text',array('attr' => array('onkeypress' =>'return menorDe(event)')))
                ->add('entrada')
                ->add('salida');
                
//        $builder->add('entradas', 'collection', array('type' => new entradaType()));
//        $builder->add('id_out', 'collection', array('type' => new testOutType()));
    }
    function getName() 
    {
        return 'test_form';   
    }
}
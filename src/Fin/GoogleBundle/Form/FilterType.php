<?php

namespace Fin\GoogleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DateTime;
use DateInterval;

class FilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $end=new DateTime();
        $begin=clone $end;
        $begin=$begin->sub(new DateInterval('P'.(string)((integer)$end->format('d')-1).'D'));
        $builder
            ->add('begin','date',array('widget' => 'single_text','label'=>'Начало периода','data'=>$begin))
            ->add('end','date',array('widget' => 'single_text','label'=>'Конец периода','data'=>$end))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fin_googlebundle_filter';
    }
}

<?php
namespace eclore\userBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class overviewBlockService extends BaseBlockService
{private $em;
    /**
     * {@inheritdoc}
     */
     public function buildEditForm(FormMapper $form, BlockInterface $block)
     {
     }
     
     public function __construct($name, EngineInterface $templating, EntityManager $entityManager)
    {
            parent::__construct($name, $templating);
            $this->em = $entityManager;
    }
    
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $data = array('proj'=>'ecloreuserBundle:Project',
        'pa'=>'ecloreuserBundle:ProjectApplication',
        'assoM'=>'ecloreuserBundle:AssociationMember',
        'instM'=>'ecloreuserBundle:InstitutionMember',
        'asso'=>'ecloreuserBundle:Association',
        'inst'=>'ecloreuserBundle:Institution',
        'young'=>'ecloreuserBundle:Young',
        'image'=>'ecloreuserBundle:Image');
        $count;
        foreach($data as $key=>$value)
            $count[$key]= $this->em->getRepository($value)
                ->createQueryBuilder('p')
                 ->select('COUNT(p)')
                 ->getQuery()
                 ->getSingleScalarResult();
        return $this->renderResponse($blockContext->getTemplate(), array(
            'block'     => $blockContext->getBlock(),
            'settings'  => $blockContext->getSettings(),
            'count' => $count
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Overview';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'template' => 'ecloreuserBundle:Block:overviewBlock.html.twig'
        ));
    }
}

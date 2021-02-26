<?php

namespace App\Controller\Admin;

use App\Entity\Classification;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class ClassificationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Classification::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('classification'))
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Classification')
            ->setEntityLabelInPlural('Classifications')
            ->setSearchFields(['title', 'code', 'description'])
            ->setPageTitle('edit', fn (Classification $classification) => sprintf('Editing <b>%s</b>', $classification->getTitle()))    
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setDefaultSort(['code' => 'ASC','title' => 'ASC']);
        ;
    }
 
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code');
        yield TextField::new('title');
        yield TextareaField::new('description')
            ->hideOnIndex();        
    }
}
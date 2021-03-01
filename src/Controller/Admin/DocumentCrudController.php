<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Document')
            ->setEntityLabelInPlural('Documents')
            ->setSearchFields(['title', 'slug', 'start_Date'])
            ->setPageTitle('edit', fn (Document $document) => sprintf('Editing <b>%s</b>', $document->getTitle()))    
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setDefaultSort(['start_date' => 'DESC']);
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('document'))
        ;
    }
    

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('classification');
        yield TextField::new('slug')
        ->hideOnIndex();
        yield TextField::new('title');
        yield TextField::new('version');
        yield TextField::new('state');
        yield TextField::new('uri')
        ->hideOnIndex();
        yield TextEditorField::new('text')
        ->hideOnIndex();
        
        $start_date = DateField::new('start_date')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        yield $start_date;
        $end_date = DateField::new('end_date')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        yield $end_date;
    }
}

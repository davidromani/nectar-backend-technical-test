<?php

declare(strict_types=1);

namespace App\Admin;

use App\Enum\SortOrderEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;

final class UserAdmin extends AbstractBaseAdmin
{
    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'user';
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        parent::configureDefaultSortValues($sortValues);
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderEnum::ASCENDING->value;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ->add('email')
            ->add('active')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'name',
                FieldDescriptionInterface::TYPE_STRING,
                [
                    'editable' => true,
                ]
            )
            ->add(
                'email',
                FieldDescriptionInterface::TYPE_STRING,
                [
                    'editable' => true,
                ]
            )
            ->add(
                'tasksAmount',
                FieldDescriptionInterface::TYPE_INTEGER,
                [
                    'editable' => false,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                'active',
                FieldDescriptionInterface::TYPE_BOOLEAN,
                [
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                ListMapper::TYPE_ACTIONS,
                [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                    ],
                    'header_style' => 'width:86px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with(
                'General',
                [
                    'class' => 'col-md-3',
                    'box_class' => 'box box-success',
                ]
            )
            ->add('name')
            ->add('email')
            ->add('password')
            ->end()
            ->with(
                'Controls',
                [
                    'class' => 'col-md-3',
                    'box_class' => 'box box-success',
                ]
            )
            ->add('active')
            ->end()
        ;
    }
}

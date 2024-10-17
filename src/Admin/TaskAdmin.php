<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\AbstractBase;
use App\Entity\User;
use App\Enum\SortOrderEnum;
use App\Enum\TaskStatusEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class TaskAdmin extends AbstractBaseAdmin
{
    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'task';
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        parent::configureDefaultSortValues($sortValues);
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderEnum::DESCENDING->value;
        $sortValues[DatagridInterface::SORT_BY] = 'date';
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'date',
                DateFilter::class,
                [
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => AbstractBase::DATE_PICKER_TYPE_FORMAT,
                    ],
                ]
            )
            ->add(
                'user',
                ModelFilter::class,
                [
                    'field_options' => [
                        'class' => User::class,
                    ],
                ]
            )
            ->add('title')
            ->add('description')
            ->add(
                'status',
                ChoiceFilter::class,
                [
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => TaskStatusEnum::getChoices(),
                        'required' => true,
                        'multiple' => false,
                        'expanded' => false,
                    ],
                ]
            )
            ->add('active')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'date',
                FieldDescriptionInterface::TYPE_DATE,
                [
                    'format' => AbstractBase::DATE_STRING_FORMAT,
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'user',
                FieldDescriptionInterface::TYPE_MANY_TO_ONE,
                [
                    'editable' => false,
                    'sortable' => true,
                    'associated_property' => 'name',
                    'route' => [
                        'name' => 'edit',
                    ],
                    'sort_field_mapping' => [
                        'fieldName' => 'name',
                    ],
                    'sort_parent_association_mappings' => [
                        [
                            'fieldName' => 'user',
                        ],
                    ],
                ]
            )
            ->add(
                'title',
                FieldDescriptionInterface::TYPE_STRING,
                [
                    'editable' => true,
                ]
            )
            ->add(
                'status',
                FieldDescriptionInterface::TYPE_HTML,
                [
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                    'template' => 'admin/task/task_status_list_field.html.twig',
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
            ->add('user')
            ->add('title')
            ->add(
                'description',
                TextareaType::class,
                [
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
            ->end()
            ->with(
                'Controls',
                [
                    'class' => 'col-md-3',
                    'box_class' => 'box box-success',
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => TaskStatusEnum::getChoices(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->add(
                'date',
                DatePickerType::class,
                [
                    'format' => AbstractBase::DATE_FORM_TYPE_FORMAT,
                    'required' => true,
                    'disabled' => false,
                    'row_attr' => [
                        'style' => 'margin-bottom:30px',
                    ],
                ]
            )
            ->add('active')
            ->end()
        ;
    }
}

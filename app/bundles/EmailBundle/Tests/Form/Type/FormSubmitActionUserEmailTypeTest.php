<?php

declare(strict_types=1);

/*
 * @copyright   2021 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\Tests\Form\Type;

use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\EmailBundle\Entity\Email;
use Mautic\EmailBundle\Form\Type\EmailSendType;
use Mautic\EmailBundle\Form\Type\EmailType;
use Mautic\EmailBundle\Form\Type\FormSubmitActionUserEmailType;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\ObjectMappingDAO;
use Mautic\StageBundle\Model\StageModel;
use Mautic\UserBundle\Form\Type\UserListType;
use PHPUnit\Framework\Assert;
use function PHPUnit\Framework\at;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormSubmitActionUserEmailTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockObject|TranslatorInterface
     */
    private $translator;

    /**
     * @var MockObject|EntityManager
     */
    private $entityManager;

    /**
     * @var MockObject|StageModel
     */
    private $stageModel;

    /**
     * @var MockObject|FormBuilderInterface
     */
    private $formBuilder;

    /**
     * @var EmailType
     */
    private $form;

    /**
     * @var CoreParametersHelper|MockObject
     */
    private $coreParametersHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formBuilder          = $this->createMock(FormBuilderInterface::class);
        $this->coreParametersHelper = $this->createMock(CoreParametersHelper::class);
        $this->form                 = new FormSubmitActionUserEmailType();
        $this->formBuilder->method('create')->willReturnSelf();
    }

    public function testBuildForm(): void
    {
        $options = [];

        $this->formBuilder->expects($this->exactly(2))
            ->method('add')
            ->withConsecutive(
                [
                    'useremail',
                    EmailSendType::class,
                    [
                        'label' => 'mautic.email.emails',
                        'attr'  => [
                            'class'   => 'form-control',
                            'tooltip' => 'mautic.email.choose.emails_descr',
                        ],
                        'update_select' => 'formaction_properties_useremail_email',
                    ],
                ],
                [
                    'user_id',
                    UserListType::class,
                    [
                        'label'      => 'mautic.email.form.users',
                        'label_attr' => ['class' => 'control-label'],
                        'attr'       => [
                            'class'   => 'form-control',
                            'tooltip' => 'mautic.core.help.autocomplete',
                        ],
                        'required'    => true,
                        'constraints' => new NotBlank(
                            [
                                'message' => 'mautic.core.value.required',
                            ]
                        ),
                    ],
                ]
            );

        $this->form->buildForm($this->formBuilder, $options);
    }
}

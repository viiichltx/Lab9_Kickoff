<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Adapter\Title\Validate;

use Gender;
use PrestaShop\PrestaShop\Adapter\AbstractObjectModelValidator;
use PrestaShop\PrestaShop\Core\Domain\Title\Exception\TitleConstraintException;
use PrestaShop\PrestaShop\Core\Exception\CoreException;

/**
 * Validates TaxRulesGroup properties using legacy object model validation
 */
class TitleValidator extends AbstractObjectModelValidator
{
    /**
     * @param Gender $title
     *
     * @throws TitleConstraintException
     * @throws CoreException
     */
    public function validate(Gender $title): void
    {
        $this->validateObjectModelLocalizedProperty(
            $title,
            'name',
            TitleConstraintException::class,
            TitleConstraintException::INVALID_NAME
        );
        $this->validateTitleProperty(
            $title,
            'type',
            TitleConstraintException::INVALID_TYPE
        );
    }

    /**
     * @param Gender $title
     * @param string $propertyName
     * @param int $errorCode
     *
     * @throws TitleConstraintException
     * @throws CoreException
     */
    private function validateTitleProperty(
        Gender $title,
        string $propertyName,
        int $errorCode = 0
    ): void {
        $this->validateObjectModelProperty(
            $title,
            $propertyName,
            TitleConstraintException::class,
            $errorCode
        );
    }
}

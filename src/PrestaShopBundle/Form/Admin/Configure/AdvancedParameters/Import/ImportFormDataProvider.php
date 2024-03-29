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

namespace PrestaShopBundle\Form\Admin\Configure\AdvancedParameters\Import;

use PrestaShop\PrestaShop\Core\Import\Configuration\ImportConfigInterface;
use PrestaShop\PrestaShop\Core\Import\File\FileFinder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ImportFormDataProvider is responsible for providing Import's 1st step form data.
 */
final class ImportFormDataProvider implements ImportFormDataProviderInterface
{
    /**
     * @param FileFinder $importFileFinder
     * @param RequestStack $requestStack
     */
    public function __construct(
        private readonly FileFinder $importFileFinder,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getData(ImportConfigInterface $importConfig)
    {
        return [
            'csv' => $this->getSelectedFile($importConfig),
            'entity' => $importConfig->getEntityType(),
            'iso_lang' => $importConfig->getLanguageIso(),
            'separator' => $importConfig->getSeparator(),
            'multiple_value_separator' => $importConfig->getMultipleValueSeparator(),
            'truncate' => $importConfig->truncate(),
            'regenerate' => $importConfig->skipThumbnailRegeneration(),
            'match_ref' => $importConfig->matchReferences(),
            'forceIDs' => $importConfig->forceIds(),
            'sendemail' => $importConfig->sendEmail(),
            'type_value' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $errors = [];
        if (empty($data['csv'])) {
            $errors[] = [
                'key' => 'To proceed, please upload a file first.',
                'domain' => 'Admin.Advparameters.Notification',
                'parameters' => [],
            ];
        } else {
            $this->requestStack->getSession()->set('csv', $data['csv']);
            $this->requestStack->getSession()->set('entity', $data['entity']);
            $this->requestStack->getSession()->set('iso_lang', $data['iso_lang']);
            $this->requestStack->getSession()->set('separator', $data['separator']);
            $this->requestStack->getSession()->set('multiple_value_separator', $data['multiple_value_separator']);
            $this->requestStack->getSession()->set('truncate', $data['truncate']);
            $this->requestStack->getSession()->set('match_ref', $data['match_ref']);
            $this->requestStack->getSession()->set('regenerate', $data['regenerate']);
            $this->requestStack->getSession()->set('forceIDs', $data['forceIDs']);
            $this->requestStack->getSession()->set('sendemail', $data['sendemail']);
        }

        return $errors;
    }

    /**
     * Get selected file after confirming that it is available in file system.
     *
     * @param ImportConfigInterface $importConfig
     *
     * @return string|null
     */
    private function getSelectedFile(ImportConfigInterface $importConfig)
    {
        $importFiles = $this->importFileFinder->getImportFileNames();
        $selectedFile = $importConfig->getFileName();
        if ($selectedFile && !in_array($selectedFile, $importFiles)) {
            $selectedFile = null;
        }

        return $selectedFile;
    }
}

<?php
/**
 * Copyright 2018 Payolution GmbH
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0 [^]
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TopConcepts\Payolution;

use OxidEsales\Eshop\Core\Registry;
use TopConcepts\Payolution\Client\ApiInterface;
use TopConcepts\Payolution\Client\CacheClient;
use TopConcepts\Payolution\Client\Client;
use TopConcepts\Payolution\Client\Type\ConfigType;
use TopConcepts\Payolution\Client\WebService;
use TopConcepts\Payolution\Client\WebServiceLog;
use TopConcepts\Payolution\Config\Configuration;
use TopConcepts\Payolution\Logger\OrderLogger;
use TopConcepts\Payolution\Manager\ConfigManager;
use TopConcepts\Payolution\Manager\FormManager;
use TopConcepts\Payolution\Manager\OrderManager;
use TopConcepts\Payolution\Utils\FormatterUtils;
use TopConcepts\Payolution\Utils\MiniDiUtils;
use TopConcepts\Payolution\Validation\ServiceValidation;

/**
 * Class Payolution_Services
 */
class PayolutionServices
{
    /**
     * @var MiniDiUtils
     */
    private $di;

    /**
     * PayolutionServices constructor.
     */
    public function __construct()
    {
        $this->di = oxNew(
          MiniDiUtils::class,
          [
            'services'         => [
              'factory' => [$this, '__servicesFactory'],
            ],
            'payolution_api'   => [
              'factory'   => [$this, '__payolutionApiFactory'],
            ],
            'config_manager'   => [
              'class'     => ConfigManager::class
            ],
            'ordering_manager' => [
              'class'     => OrderManager::class,
              'arguments' => [
                'payolution_api',
                'config_manager',
                'forms',
                'validation',
                'ordering_logger'
              ],
            ],
            'ordering_logger'  => [
              'class'     => OrderLogger::class
            ],
            'events'           => [
              'class'     => PayolutionEvents::class,
              'arguments' => ['services'],
            ],
            'admin_events'     => [
              'class'     => AdminEvents::class,
              'arguments' => ['services'],
            ],
            'forms'            => [
              'class'     => FormManager::class
            ],
            'validation'       => [
              'class'     => ServiceValidation::class,
              'arguments' => ['config_manager'],
            ],
            'formatter'        => [
              'class'     => FormatterUtils::class
            ],
          ]
        );
    }

    /**
     * @return ApiInterface
     */
    public function payolutionApi()
    {
        return $this->di->get('payolution_api');
    }

    /**
     * @return ConfigManager
     */
    public function configManager()
    {
        return $this->di->get('config_manager');
    }

    /**
     * @return OrderManager
     */
    public function orderingManager()
    {
        return $this->di->get('ordering_manager');
    }

    /**
     * @return PayolutionEvents
     */
    public function events()
    {
        return $this->di->get('events');
    }

    /**
     * @return AdminEvents
     */
    public function adminEvents()
    {
        return $this->di->get('admin_events');
    }

    /**
     * @return FormManager
     */
    public function forms()
    {
        return $this->di->get('forms');
    }

    /**
     * @return FormatterUtils
     */
    public function formatter()
    {
        return $this->di->get('formatter');
    }

    /**
     * @return object
     */
    public function validation()
    {
        return $this->di->get('validation');
    }

    /**
     * @return $this
     */
    public function __servicesFactory()
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function __payolutionApiFactory()
    {
        if ($this->configManager()->getConfig()->isLoggerEnabled()) {
            $ws = oxNew(WebServiceLog::class, $this->di->get('ordering_logger'));
        } else {
            $ws = oxNew(WebService::class);
        }

        $configuration = $this->configManager()->getConfig();

        $config = $this->createPayolutionApiConfig($configuration);

        $api = oxNew(Client::class, $ws, $config);

        return oxNew(CacheClient::class, $api);
    }

    /**
     * @param Configuration $config
     *
     * @return array
     */
    private function createPayolutionApiConfig(Configuration $config)
    {
        $result = [
          'channel'          => $config->getLogin(),
          'login'            => $config->getLogin(),
          'pass'             => $config->getPassword(),
          'sender'           => $config->getLogin(),
          'shopUrl'          => Registry::getConfig()->getShopUrl(),
        ];

        if ($config->liveServerEnabled()) {
            $result['test_mode'] = false;
        } else {

            $result['test_mode'] = true;
        }

        return $result;
    }
}

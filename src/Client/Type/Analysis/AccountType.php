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
namespace TopConcepts\Payolution\Client\Type\Analysis;

/**
 * Class AccountType
 * @package TopConcepts\Payolution\Client\Type\Analysis
 */
class AccountType implements AnalysisTypeInterface
{
    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $holder;

    /**
     * @var string
     */
    public $bic;

    /**
     * @var string
     */
    public $iban;

    /**
     * Method converts all object into associative array
     *
     * @return array
     */
    public function toArray()
    {
        return [
          'COUNTRY' => $this->country,
          'HOLDER'  => $this->holder,
          'BIC'     => $this->bic,
          'IBAN'    => $this->iban,
        ];
    }
}

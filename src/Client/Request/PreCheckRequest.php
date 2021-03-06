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
namespace TopConcepts\Payolution\Client\Request;

use TopConcepts\Payolution\Client\Type\AnalysisType;
use TopConcepts\Payolution\Client\Type\CustomerType;
use TopConcepts\Payolution\Client\Type\PaymentType;
use TopConcepts\Payolution\Payment\PaymentMethod;

/**
 * Class PreCheckRequest
 * @package TopConcepts\Payolution\Client\Request
 */
class PreCheckRequest extends PreAuthRequest
{
    /**
     * @param PaymentMethod $paymentMethod
     * @param CustomerType $customer
     * @param PaymentType $payment
     * @param AnalysisType[] $basketItems
     */
    public function __construct(PaymentMethod $paymentMethod, CustomerType $customer, PaymentType $payment, $basketItems)
    {
        parent::__construct(null, $paymentMethod, $customer, $payment, $basketItems);

        $this->transaction()->analysis->preCheck = 'TRUE';
        $this->transaction()->analysis->needToUseSessionId = true;
    }
}

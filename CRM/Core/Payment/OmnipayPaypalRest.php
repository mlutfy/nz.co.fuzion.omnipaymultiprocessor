<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5.5                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * Class CRM_Core_Payment_PaypalRest.
 *
 * In general the OmnipayMultiProcessor class copes with vagaries of
 * payment processors. However sometime they have anomalies that
 * can't be dealt with by metadata
 *
 * Omnipay supports token payments but not recurring
 */
class CRM_Core_Payment_OmnipayPaypalRest extends CRM_Core_Payment_OmnipayMultiProcessor {

  /**
   * @param array $params
   * @return \Omnipay\Common\Message\ResponseInterface
   */
  protected function doPreapproveForRecurring($params) {
    $plan = $this->gateway->createPlan(['description' => 'default'])->send();
    $response = $this->gateway->createSubscription($this->getCreditCardOptions(array_merge($params, [
      'action' => 'Authorize',
      'name' => 'blah',
      'start_date' => date('Y-m-d H:i:s', '+ 1 day')
    ])))
      ->send();
    return $response;
  }
}

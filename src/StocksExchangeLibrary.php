<?php
/**
 * 2018 [StocksExchange.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    StocksExchange.
 *  @copyright 2018 StocksExchange.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

require_once dirname(__FILE__).'/StocksExchange.class.php';
require_once dirname(__FILE__).'/../vendor/autoload.php';

try {
    StocksExchange::init();
} catch (Exception $e) {
    print_r($e);
}
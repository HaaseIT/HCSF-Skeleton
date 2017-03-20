<?php

/*
    HCSF - A multilingual CMS and Shopsystem
    Copyright (C) 2014  Marcus Haase - mail@marcus.haase.name

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

ini_set('display_errors', 0);
ini_set('xdebug.overload_var_dump', 0);
ini_set('xdebug.var_display_max_depth', 10);
ini_set('html_errors', 0);

error_reporting(E_ALL);

mb_internal_encoding('UTF-8');
header("Content-Type: text/html; charset=UTF-8");

if (ini_get('session.auto_start') == 1) {
    die('Please disable session.autostart for this to work.');
}

// set scale for bcmath
bcscale(6);

define("MINUTE", 60);
define("HOUR", MINUTE * 60);
define("DAY", HOUR * 24);
define("WEEK", DAY * 7);

define("DB_ADDRESSFIELDS", 'cust_id, cust_no, cust_email, cust_corp, cust_name, cust_street, cust_zip, cust_town, cust_phone, cust_cellphone, cust_fax, cust_country, cust_group, cust_active, cust_emailverified, cust_tosaccepted, cust_cancellationdisclaimeraccepted');
define("DB_ITEMFIELDS", 'itm_no, itm_name, itm_price, itm_vatid, itm_rg, itm_img, itm_group, itm_data, itm_weight, itml_name_override, itml_text1, itml_text2, itm_index');
define("DB_ITEMGROUPFIELDS", 'itmg_no, itmg_name, itmg_img, itmgt_shorttext, itmgt_details');

define("PATH_BASEDIR", __DIR__.'/../');
define("PATH_DOCROOT", PATH_BASEDIR.'web/');

define("PATH_CACHE", PATH_BASEDIR.'cache/');
define("DIRNAME_TEMPLATECACHE", 'templates');
define("PATH_TEMPLATECACHE", PATH_CACHE.DIRNAME_TEMPLATECACHE);
define("PATH_PURIFIERCACHE", PATH_CACHE.'htmlpurifier/');
define("DIRNAME_GLIDECACHE", 'glide');
define("PATH_GLIDECACHE", PATH_CACHE.DIRNAME_GLIDECACHE);

define("PATH_LOGS", PATH_BASEDIR.'hcsflogs/');
define("FILE_PAYPALLOG", 'ipnlog.txt');

const ENTITY_CUSTOMER = 'HaaseIT\HCSF\Entities\Customer\Customer';
const ENTITY_USERPAGE_LANG = 'HaaseIT\HCSF\Entities\UserpageLang';
const ENTITY_USERPAGE_BASE = 'HaaseIT\HCSF\Entities\UserpageBase';

require_once __DIR__.'/../vendor/autoload.php';

$HCSF = new \HaaseIT\HCSF\HCSF();

$P = $HCSF->init();

$aP = \HaaseIT\HCSF\Helper::generatePage($serviceManager, $P);

$response = new \Zend\Diactoros\Response();
$response = $response->withStatus($P->iStatus);

if (count($aP['headers'])) {
    foreach ($aP['headers'] as $header => $value) {
        $response = $response->withHeader($header, $value);
    }
}

if ($aP['customroottemplate'] != '') {
    $response->getBody()->write($serviceManager->get('twig')->render($aP['customroottemplate'], $aP));
} else {
    $response->getBody()->write($serviceManager->get('twig')->render(\HaaseIT\HCSF\HelperConfig::$core["template_base"], $aP));
}

$emitter = new \Zend\Diactoros\Response\SapiEmitter();
$emitter->emit($response);
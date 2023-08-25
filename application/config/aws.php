<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['aws_access_key'] = 'AKIAQAVTMB3SG4XXGPIH';
$config['aws_secret_key'] = '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';
$config['aws_region'] = 'ap-northeast-1';

require_once APPPATH . 'third_party/aws/aws-autoloader.php';
use Aws\S3\S3Client;

$client = S3Client::factory(array(
    'credentials' => array(
        'key'    => $config['aws_access_key'],
        'secret' => $config['aws_secret_key'],
    ),
    'region' => $config['aws_region'],
    'version' => 'latest',
));
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['aws_access_key'] = '';
$config['aws_secret_key'] = '';
$config['aws_region'] = '';

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
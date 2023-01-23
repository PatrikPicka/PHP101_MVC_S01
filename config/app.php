<?php
require_once ROOT . DS . 'config' . DS . 'security.php';

const DEBUG = true;

const PROOT = '/PHP101/04/';

const CORE_APP_FOLDER_NAME = 'core';

const BASE_CONTROLLER_NAMESPACE = 'App\\Controllers\\';
const DEFAULT_CONTROLLER_BASE_NAME = 'home';
const DEFAULT_CONTROLLER = 'HomeController';
const DEFAULT_ACTION_BASE_NAME = 'index';
const DEFAULT_ACTION = 'indexAction';
const DEFAULT_LAYOUT = 'default';

const SITE_TITLE = 'PHP 101 - MVC Framework';

const DB_HOST = '127.0.0.1';
const DB_NAME = 'mvc_01';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const SOFT_DELETE = true;
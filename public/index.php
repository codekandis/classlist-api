<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\Tiphy\Actions\ActionDispatcher;
use function error_reporting;
use function ini_set;
use const E_ALL;
use const E_STRICT;

/**
 * Represents the bootstrap script of the project.
 *
 * @package codekandis/classlist-api
 * @author  Christian Ramelow <info@codekandis.net>
 */
error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 'On' );
ini_set( 'html_errors', 'Off' );

require_once __DIR__ . '/../vendor/autoload.php';

$config           = ConfigurationRegistry::_()->getRoutesConfiguration();
$actionDispatcher = new ActionDispatcher( $config );
$actionDispatcher->dispatch();

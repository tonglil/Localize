<?php require __DIR__ . '/vendor/autoload.php';

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('locales')
    ->in($dir = __DIR__ . '/src');

$versions = GitVersionCollection::create($dir)
    ->add('master', 'master branch')
    ->add('1.0', '1.0 branch')
    ->addFromTags('v1.0.*');

$options = array(
    'versions'             => $versions,
    'title'                => 'Localize API',
    'build_dir'            => __DIR__ . '/doc/%version%',
    'cache_dir'            => __DIR__ . '/build/cache/Localize/%version%',
    'default_opened_level' => 2,
);

return new Sami($iterator, $options);

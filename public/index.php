<?php

declare(strict_types=1);

$container = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'boostrap.php';
$app = new Kernel($container);

$request = $_SERVER['REQUEST_URI'] ?? '';
$route = parse_url($request, PHP_URL_PATH);
parse_str(parse_url($request, PHP_URL_QUERY) ?? '', $params);

try {
    $app->run(
        $route ?? '',
        $params,
        $container->get(Config::ACTION_DEFAULT_HTTP->name)
    );
} catch (Throwable $exception) {
    /** @var Core\Stream\OutputInterface $output */
    $output = $container->get(Core\Stream\OutputInterface::class);
    $output->write(sprintf('<h1>%s</h1>', $exception::class));
    $output->write(sprintf('<b>%s</b>', $exception->getMessage()));
    $output->write('<hr />');
    $output->write(sprintf('<pre>%s</pre>', $exception->getTraceAsString()));
}
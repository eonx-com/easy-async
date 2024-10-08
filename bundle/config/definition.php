<?php
declare(strict_types=1);

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition) {
    $definition->rootNode()
        ->children()
            ->arrayNode('doctrine')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('close_persistent_connections')
                        ->canBeDisabled()
                        ->children()
                            ->floatNode('max_idle_time')
                                ->defaultValue(10.0)
                                ->info('Max idle time for persistent connections in seconds')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('messenger')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('middleware')
                        ->canBeDisabled()
                    ->end()
                    ->arrayNode('worker')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('stop_on_messages_limit')
                                ->canBeEnabled()
                                ->children()
                                    ->integerNode('min_messages')->end()
                                    ->integerNode('max_messages')->defaultNull()->end()
                                ->end()
                            ->end()
                            ->arrayNode('stop_on_time_limit')
                                ->canBeEnabled()
                                ->children()
                                    ->integerNode('min_time')->end()
                                    ->integerNode('max_time')->defaultNull()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
};

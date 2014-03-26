<?php namespace Belsrc\DbReflection;

    use Illuminate\Support\ServiceProvider;
    use Belsrc\DbReflection\Commands\ReflectDatabaseCommand;
    use Belsrc\DbReflection\Commands\ReflectTableCommand;
    use Belsrc\DbReflection\Commands\ReflectColumnCommand;

    class DbReflectionServiceProvider extends ServiceProvider {

        /**
         * Indicates if loading of the provider is deferred.
         *
         * @var boolean
         */
        protected $defer = false;

        /**
         * Bootstrap the application events.
         *
         * @return void
         */
        public function boot() {
            $this->package( 'belsrc/db-reflection' );
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register() {
            $this->registerClass();
            $this->registerDatabaseCommand();
            $this->registerTableCommand();
            $this->registerColumnCommand();
        }

        /**
         * Register the reflection classes for use in the application.
         *
         * @return void
         */
        protected function registerClass() {
            $this->app['db-reflection'] = $this->app->share( function( $app ) {
                return new DbReflection();
            });
        }

        /**
         * Register the reflect database command.
         *
         * @return void
         */
        protected function registerDatabaseCommand() {
            $this->app['reflect.database'] = $this->app->share( function( $app ) {
                return new ReflectDatabaseCommand();
            });

            $this->commands( 'reflect.database' );
        }

        /**
         * Register the reflect table command.
         *
         * @return void
         */
        protected function registerTableCommand() {
            $this->app['reflect.table'] = $this->app->share( function( $app ) {
                return new ReflectTableCommand();
            });

            $this->commands( 'reflect.table' );
        }

        /**
         * Register the reflect column command.
         *
         * @return void
         */
        protected function registerColumnCommand() {
            $this->app['reflect.column'] = $this->app->share( function( $app ) {
                return new ReflectColumnCommand();
            });

            $this->commands( 'reflect.column' );
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides() {
            return array( 'db-reflection' );
        }
    }

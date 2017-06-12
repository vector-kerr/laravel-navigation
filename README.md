# laravel-navigation

Generate contextual navigation trees using Events and Listeners.

The Navigation Service Provider should allow you to create highly
dynamic menu structures by making use of an event/listener system.
By passing through a `context` object of your choice, you can also
completely change the way that the menu generation works depending
on the value you pass in.


# Installation

1. Require the `laravel-navigation` package in your Laravel project.

    `composer require vector88/laravel-navigation`

1. Add the `NavigationServiceProvider` to the `providers` array in `config/app.php`:

    ```php
    'providers' => [
        ...
        Vector88\Navigation\NavigationServiceProvider::class,
        ...
    ],
    ```


# Usage

## Create an Event Listener

This listener will be executed whenever the Build Navigation event
is invoked. The following example adds a simple 'home' link to the
top level of the navigation structure for every Build Navigation event.

The Build Navigation event handler takes a `BuildNavigation` event object in the `handle` call, and you can add `NavigationItem` instances to the navigation tree by using the `add()` method of the `BuildNavigation` object.

```php
<?php

namespace App\Listeners;
use Vector88\Navigation\Events\BuildNavigation;
use Vector88\Navigation\Models\NavigationItem;


class AddHomeToNavigation {
    public function handle( BuildNavigation $e ) {
        $e->add( new NavigationItem( 'home', 'Home', url( '/' ) ) );
    }
}
```


## Register the Event Listener

The easiest way to register the Event Listener is by adding it
to the `$listen` associative array in your `App\Providers\EventServiceProvider` class. For example:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Vector88\Navigation\Events\BuildNavigation' => [
            'App\Listeners\AddHomeToNavigation',
            // ...
        ],
        // ...
    ];
}
```


## Invoke the Event

To build the navigation tree, call the `build()` function of the `Navigation` service.
Get the Navigation Service by using dependency injection, or by using the `make()` method.
When retrieving the `Navigation` service you should use the provided Contract rather than
the service class directly.

```php
<?php

public function processMenu( Vector88\Navigation\Contracts\Navigation $navigationService ) {
    $tree = $navigationService->build();
    // Do something with $tree
}

public function doSomethingDifferent() {
    $navigationService = $this->app->make( 'Vector88\Navigation\Contracts\Navigation' );
    $tree = $navigationService->build();
    // Do something else with $tree
}
```


You can also resolve and use the Navigation Service directly within a `blade` file by
using the `@inject` directive:

```blade
@inject( 'navigationService', 'Vector88\Navigation\Contracts\Navigation' )

<nav>
    @foreach( $navigationService->build() as $menuItem )
        @include( 'menu_item', [ 'item' => $menuItem ] )
    @endforeach
</nav>
```


# Navigation Context Object

The `BuildNavigation` event object can hold a `context` object. This object is just a
variable, and you can put whatever you like in it. The `$navigationService->build()` function
takes a single optional argument which will be passed through as the `context` object. This
`context` object can then be accessed by retrieving the `context` member of the `buildNavigation` event.

```php
<?php

$navigationService->build( "foo" );

// ...

public function handle( BuildNavigation $e ) {
    if( $e->context == "foo" ) {
        // ...
    }
}
```

This simple arrangement can allow you to pass basic variables like strings or integers through to the build navigation event listeners, which you can perform checks against. You can also pass complex objects like class instances and perform more in-depth tasks on that object if you prefer.

If you do not provide a context object when you call `build()`, then `$buildNavigation->context` is set to `null`.


# Navigation Item Object

The `NavigationItem` which is added to the `BuildNavigation` event object has a number of
members available for assignment:

```php
<?php

public $key;
public $label;
public $href;
public $sortIndex;
public $right;
```

## $key

The key is used to uniquely identify the item within the navigation structure. Additionally,
the key defines the navigation menu structure, using dots to define each level of navigation.
When the navigation structure is built, the navigation menu structure will be generated
based on these dots.

```php
<?php

$rootItem = new NavigationItem( 'root' );
$childItem = new NavigationItem( 'root.child' );
$anotherChildItem = new NavigationItem( 'root.anotherchild' );
$thirdLevelItem = new NavigationItem( 'root.anotherchild.third_level' );

$e->add( $rootItem );
$e->add( $childItem );
$e->add( $anotherChildItem );
$e->add( $thirdLevelItem );

// The above code will result in a structure like this:
//
// + root
//   + child
//   + anotherchild
//     + third_level
```


## $label

The label is the text that you would like to show the user for the menu item.
Note that this is just another member, so you may set it to numbers, class instances, ...,
if you wish, however if a label is not specified it will default to the value of the key at
the current depth.

```php
<?php
// label = "Main Menu"
$mainMenuItem = new NavigationItem( 'root', 'Main Menu' );

// label = "any_label"
$notSpecifiedItem = new NavigationItem( 'item.without.any_label' );
```


## $href

This is where you would like the menu item to link to. Again, it's just another member,
so you can put whatever you want in here, however a URL is usually a good choice.

```php
<?php
$homeItem = new NavigationItem( 'main.home', 'Home', url( '/' ) );
$loginItem = new NavigationItem( 'account.login', 'Sign In', route( 'login' ) );
$googleItem = new NavigationItem( 'search.google', 'Google Search', 'https://www.google.com.au' );
```


## $sortIndex

Specify an integer or float to sort the order of the menu items.
Note that because the result of building a menu is an associative array, the sort index
is simply passed along to the resulting associative array as a key-value pair, and must
be handled by the menu renderer if it's required.

```php
<?php
$sortedItem = new NavigationItem( 'main.third', 'Third Item', url( '/' ), 3 );

// or

$sortedItem->sortIndex = 3;
```


## $right

A boolean flag to indicate that this menu item should appear right-aligned. Again,
you can use this member for whatever you would like, however many menu rendering systems
(like bootstrap and semantic-ui) allow for right-aligned menu items, so this can be
helpful for that.

```php
<?php
$item->right = true;
```



# Author

Daniel 'Vector' Kerr <vector.kerr@gmail.com>

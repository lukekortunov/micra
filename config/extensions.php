<?php

return [
    // Return array of extensions class names, it will be executed and $container
    // will be passed into each of it.
    //
    // To make your own extension register in the DI container you have to implement
    // ExtensionInterface and add own services to the container, that will be passed
    // into the __invoke method.
    //
    // @see LukeKortunov\Micra\Contracts\ExtensionInterface
];
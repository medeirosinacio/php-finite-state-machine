<?php

use Medeirosinacio\SkeletonPhp\Example;

it('foo', function () {
    $example = new Example();

    $result = $example->foo();

    expect($result)->toBe('bar');
});

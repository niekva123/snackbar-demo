<?php

namespace App\Actions\Inventory;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\App\Models\Snackbar $snackbar, array $data)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\App\Models\Snackbar $snackbar, array $data)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\App\Models\Snackbar $snackbar, array $data)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \App\Models\Snackbar $snackbar, array $data)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \App\Models\Snackbar $snackbar, array $data)
 * @method static dispatchSync(\App\Models\Snackbar $snackbar, array $data)
 * @method static dispatchNow(\App\Models\Snackbar $snackbar, array $data)
 * @method static dispatchAfterResponse(\App\Models\Snackbar $snackbar, array $data)
 * @method static \App\Http\Resources\ItemResource run(\App\Models\Snackbar $snackbar, array $data)
 */
class CreateItem
{
}
namespace Lorisleiva\Actions\Concerns;

/**
 * @method void asController()
 */
trait AsController
{
}
/**
 * @method void asListener()
 */
trait AsListener
{
}
/**
 * @method void asJob()
 */
trait AsJob
{
}
/**
 * @method void asCommand(\Illuminate\Console\Command $command)
 */
trait AsCommand
{
}
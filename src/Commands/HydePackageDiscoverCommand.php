<?php

namespace Hyde\Framework\Commands;

use Hyde\Framework\Hyde;
use Illuminate\Foundation\Console\PackageDiscoverCommand as BaseCommand;
use Illuminate\Foundation\PackageManifest;

class HydePackageDiscoverCommand extends BaseCommand
{
    protected $hidden = true;

    public function handle(PackageManifest $manifest)
    {
        $manifest->manifestPath = Hyde::path('storage/framework/cache/packages.php');
        parent::handle($manifest);
    }
}

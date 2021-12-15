<?php

namespace App\Admin\Actions;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\PrinterCheck;

class PrinterCheckDownload extends RowAction
{
    
    /**
     * @return string
     */
	public function title()

    {
        return '下载';
    }

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */

    public function handle(Request $request)
    {
        //dump($this->getKey());

        $fileId = $this->getKey();
        
        
        $filePath = PrinterCheck::where('id',$fileId)->pluck('path')->first();

        $filename = basename($filePath);

        return $this->response()->download(url('uploads/'.$filePath));

    }

    /**
     * @return string|array|void
     */


    
    public function confirm()
    {
        // return ['Confirm?', 'contents'];
    }

    /**
     * @param Model|Authenticatable|HasPermissions|null $user
     *
     * @return bool
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return [];
    }
}

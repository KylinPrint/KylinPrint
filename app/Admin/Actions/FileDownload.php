<?php

namespace App\Admin\Actions;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use App\Models\File;

class FileDownload extends RowAction
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

        $filePath = File::where('id',$fileId)->pluck('path')->first();

        $filename = basename($filePath);

        return $this->response()->download(url('uploads/'.$filePath));

        // return $this->response()->download('uploads/'.$filePath);
        //路由重定向不生效？
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

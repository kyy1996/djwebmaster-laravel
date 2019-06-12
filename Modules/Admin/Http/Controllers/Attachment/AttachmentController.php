<?php

namespace Modules\Admin\Http\Controllers\Attachment;

use App\Model\Attachment;
use App\Model\Code;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 附件控制器
 * Class AttachmentController
 * @package Modules\Admin\Http\Controllers\Attachment
 */
class AttachmentController extends AdminController
{
    protected static $rules = [
        'postUpload' => [
            'file'      => 'required|file',
            'directory' => 'string',
        ],
    ];

    /**
     * 上传文件
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUpload(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        if (!$request->hasFile('file')) {
            Code::setCode(Code::ERR_PARAMETER);
            return $this->response();
        }
        $file       = $request->file('file');
        $directory  = trim($request->input('directory', ''));
        $attachment = Attachment::upload($file, $directory) ?: null;
        return $this->response($attachment);
    }
}

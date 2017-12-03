<?php

namespace App\Http\Controllers\Admin;

use Artisan;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('admin.config.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $configModel)
    {
        $data = $request->except('_token');
        if ($request->hasFile('QQ_QUN_OR_CODE')) {
            $file = upload('QQ_QUN_OR_CODE', 'uploads/images', false);
            $result = $file['status_code'] === 200 ? '/uploads/images/'.$file['data']['new_name']: '';
            $data['QQ_QUN_OR_CODE'] = $result;
        }
        $editData = [];
        foreach ($data as $k => $v) {
            $editData[] = [
                'name' => $k,
                'value' => $v
            ];
        }
        $result = $configModel->updateBatch($editData);
        if ($result) {
            // 更新缓存
            Cache::forget('config');
        }
        return redirect('admin/config/edit');
    }
}

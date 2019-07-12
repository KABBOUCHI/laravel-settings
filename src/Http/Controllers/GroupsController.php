<?php

namespace KABBOUCHI\Settings\Http\Controllers;

use Illuminate\Http\Request;
use KABBOUCHI\Settings\Settings;
use Illuminate\Routing\Controller;

class GroupsController extends controller
{
    public function index(Request $request)
    {
        $groups = call_user_func(Settings::$fieldsCallback, $request);
        $languages = call_user_func(Settings::$languages ?? function () {
            return ["en" => "English"];
        }, $request);

        return response([
            'groups'    => $groups,
            'languages' => $languages,
        ]);
    }

    public function show($group, Request $request)
    {
        return response(
            Settings::getGroup($group)->resolveFields()
        );
    }
}

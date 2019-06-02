<?php


namespace KABBOUCHI\Settings\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use KABBOUCHI\Settings\Fields\Group;
use KABBOUCHI\Settings\Settings;

class GroupsController extends controller
{

	public function index(Request $request)
	{
		$groups = call_user_func(Settings::$fieldsCallback, $request);
		$languages = call_user_func(Settings::$languages, $request);

		return response([
			'groups'    => $groups,
			'languages' => $languages
		]);
	}

	public function show($group, Request $request)
	{
		$groups = call_user_func(Settings::$fieldsCallback, $request);

		/** @var Group $desiredGroup */
		$desiredGroup = collect($groups)->first(function (Group $g) use ($group) {
			return $g->id() === $group;
		});

		return response(
			$desiredGroup->resolveFields()
		);
	}
}
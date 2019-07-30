<?php
/**
 * Contains the ChannelController class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\Address\Models\CountryProxy;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Gears\Facades\Settings;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Channel\Models\ChannelProxy;
use Vanilo\Framework\Contracts\Requests\CreateChannel;
use Vanilo\Framework\Contracts\Requests\UpdateChannel;

class ChannelController extends BaseController
{
    public function index()
    {
        return view('vanilo::channel.index', [
            'channels' => ChannelProxy::paginate(100)
        ]);
    }

    public function create()
    {
        $channel                = app(Channel::class);
        $channel->configuration = ['country_id' => Settings::get('appshell.default.country')];

        return view('vanilo::channel.create', [
            'channel'   => $channel,
            'countries' => $this->getCountries(),
        ]);
    }

    public function store(CreateChannel $request)
    {
        try {
            $channel = ChannelProxy::create($request->all());
            flash()->success(__(':name has been created', ['name' => $channel->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.channel.index'));
    }

    public function show(Channel $channel)
    {
        return view('vanilo::channel.show', ['channel' => $channel]);
    }

    public function edit(Channel $channel)
    {
        return view('vanilo::channel.edit', [
            'channel'   => $channel,
            'countries' => $this->getCountries(),
        ]);
    }

    public function update(Channel $channel, UpdateChannel $request)
    {
        try {
            $channel->update($request->all());

            flash()->success(__(':name has been updated', ['name' => $channel->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.channel.index'));
    }

    public function destroy(Channel $channel)
    {
        try {
            $name = $channel->name;
            $channel->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.channel.index'));
    }

    private function getCountries()
    {
        return CountryProxy::orderBy('name')->pluck('name', 'id');
    }
}

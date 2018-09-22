<?php
/**
 * Contains the TaxonomyController class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-09-22
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Category\Contracts\Taxonomy;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Framework\Contracts\Requests\CreateTaxonomy;
use Vanilo\Framework\Contracts\Requests\UpdateTaxonomy;

class TaxonomyController extends BaseController
{
    public function index()
    {
        return view('vanilo::taxonomy.index', [
            'taxonomies' => TaxonomyProxy::all()
        ]);
    }

    public function create()
    {
        return view('vanilo::taxonomy.create', [
            'taxonomy' => app(Taxonomy::class)
        ]);
    }

    public function store(CreateTaxonomy $request)
    {
        try {
            $taxonomy = TaxonomyProxy::create($request->except('images'));
            flash()->success(__(':name has been created', ['name' => $taxonomy->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.taxonomy.index'));
    }

    public function show(Taxonomy $taxonomy)
    {
        return view('vanilo::taxonomy.show', ['taxonomy' => $taxonomy]);
    }

    public function edit(Taxonomy $taxonomy)
    {
        return view('vanilo::taxonomy.edit', ['taxonomy' => $taxonomy]);
    }

    public function update(Taxonomy $taxonomy, UpdateTaxonomy $request)
    {
        try {
            $taxonomy->update($request->all());

            flash()->success(__(':name has been updated', ['name' => $taxonomy->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.taxonomy.index'));
    }

    public function destroy(Taxonomy $taxonomy)
    {
        try {
            $name = $taxonomy->name;
            $taxonomy->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.taxonomy.index'));
    }
}

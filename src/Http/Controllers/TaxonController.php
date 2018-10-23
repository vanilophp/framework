<?php
/**
 * Contains the TaxonController class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Hunor Kedves
 * @license     MIT
 * @since       2018-10-22
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Framework\Http\Requests\CreateTaxon;
use Vanilo\Framework\Http\Requests\UpdateTaxon;

class TaxonController extends BaseController
{
    public function create(Taxonomy $taxonomy)
    {
        return view('vanilo::taxon.create', [
            'taxons'   => Taxon::where('taxonomy_id', $taxonomy->id)->get()->pluck('name', 'id'),
            'taxonomy' => $taxonomy,
            'taxon'    => app(Taxon::class)
        ]);
    }

    public function store(Taxonomy $taxonomy, CreateTaxon $request)
    {
        try {
            $taxon = TaxonProxy::create(array_merge($request->all(), ['taxonomy_id' => $taxonomy->id]));
            flash()->success(__(':name has been created', ['name' => $taxon->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.taxonomy.show', $taxonomy));
    }

    public function edit(Taxonomy $taxonomy, Taxon $taxon)
    {
        return view('vanilo::taxon.edit', [
            'taxons'   => Taxon::where('taxonomy_id', $taxonomy->id)->get()->pluck('name', 'id'),
            'taxonomy' => $taxonomy,
            'taxon'    => $taxon
        ]);
    }

    public function update(Taxonomy $taxonomy, Taxon $taxon, UpdateTaxon $request)
    {
        try {
            $taxon->update($request->all());

            flash()->success(__(':name has been updated', ['name' => $taxon->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.taxonomy.show', $taxonomy));
    }

    public function destroy(Taxonomy $taxonomy, Taxon $taxon)
    {
        try {
            $name = $taxon->name;
            $taxon->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.taxonomy.show', $taxonomy));
    }
}

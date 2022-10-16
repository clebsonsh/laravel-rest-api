<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoiceFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $filter = new InvoiceFilter();
        $filterItems = $filter->transform($request);

        return new InvoiceCollection(Invoice::where($filterItems)->paginate()->withQueryString());
    }

    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        $invoices = [];
        foreach ($request->validated() as $invoice) {
            $obj['created_at'] = now();
            $obj['updated_at'] = now();

            $invoices[] = array_merge($invoice, $obj);
        }

        Invoice::insert($invoices);
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        //
    }
}
